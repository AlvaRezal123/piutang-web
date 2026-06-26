<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Hutang;
use App\Models\Notifikasi;
use App\Models\Cicilan;
use App\Models\User;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;


class PembayaranController extends Controller
{
    // ==========================
    // FORM PEMBAYARAN
    // ==========================

    public function create($id)
{
    $hutang = Hutang::findOrFail($id);

    $cicilanAktif = Cicilan::where(
        'id_hutang',
        $hutang->id
    )
    ->where(
        'status',
        'belum'
    )
    ->orderBy(
        'cicilan_ke'
    )
    ->first();

    return view(
        'pembayaran.create',
        compact(
            'hutang',
            'cicilanAktif'
        )
    );
}
    // ==========================
    // SIMPAN PEMBAYARAN
    // ==========================

    public function store(Request $request)
    {
        $request->validate([
            'id_hutang' =>
            'required',

            'id_cicilan' =>
            'required',

            'jumlah_bayar' =>
            'required|numeric|min:1',

            'nama_pengirim' =>
            'required',

            'bank_pengirim' =>
            'required',

            'bukti_pembayaran' =>
            'required|image|mimes:jpg,jpeg,png'

        ], [

            'jumlah_bayar.required' =>
            'Jumlah pembayaran wajib diisi',

            'jumlah_bayar.numeric' =>
            'Jumlah pembayaran harus berupa angka',

            'nama_pengirim.required' =>
            'Nama pengirim wajib diisi',

            'bank_pengirim.required' =>
            'Bank pengirim wajib dipilih',

            'bukti_pembayaran.required' =>
            'Bukti pembayaran wajib diupload'

        ]);

        // upload bukti

        $bukti = null;

        if ($request->hasFile('bukti_pembayaran')) {

            $bukti =
                time() .
                '_bukti.' .
                $request->bukti_pembayaran->extension();

            $request->bukti_pembayaran->move(
                public_path('uploads'),
                $bukti
            );
        }
       $hutang = Hutang::with('agen')->findOrFail(
            $request->id_hutang
        );
        // ==========================
// CEK PEMBAYARAN PENDING
// ==========================

        $pembayaranPending = Pembayaran::where(
            'id_hutang',
            $request->id_hutang
        )
        ->where(
            'status',
            'pending'   
        )
        ->exists();

        if ($pembayaranPending) {

            return back()
                ->with(
                    'error',
                    'Masih ada pembayaran yang sedang menunggu verifikasi admin.'
                );

        }

        $admin = User::where(
            'role',
            'admin'
        )->first();

        if (
            $request->jumlah_bayar >
            $hutang->sisa_hutang
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jumlah pembayaran tidak boleh melebihi sisa hutang'
                );
        }

        // simpan pembayaran

        Pembayaran::create([


            'id_hutang' =>
            $request->id_hutang,

            'id_cicilan' =>
            $request->id_cicilan,

            'tanggal_pembayaran' =>
            now(),

            'jumlah_bayar' =>
            $request->jumlah_bayar,

            'nama_pengirim' =>
            $request->nama_pengirim,

            'bank_pengirim' =>
            $request->bank_pengirim,

            'bukti_pembayaran' =>
            $bukti,

            'status' =>
            'pending'
        ]);


// NOTIFIKASI UNTUK ADMIN
    Notifikasi::create([
    'id_user' => $admin->id, 
    'judul' => 'Pembayaran Baru',
    'pesan' =>
        'Agen ' .
        $hutang->agen->username .
        ' baru saja mengirim bukti pembayaran sebesar Rp' .
        number_format(
            $request->jumlah_bayar,
            0,
            ',',
            '.'
        ) .
        ' dan menunggu proses verifikasi.',
    'tipe' => 'pembayaran',
    'media' => 'web',
    'tanggal' => now(),
    'status_baca' => 'belum'
]);
        return redirect('/hutang-saya')
            ->with(
                'success',
                'Pembayaran berhasil dikirim dan menunggu validasi admin'
            );
    }

    // ==========================
    // ADMIN LIHAT PEMBAYARAN
    // ==========================

    public function index(Request $request)
    {
        $query = Pembayaran::with(
    'hutang.agen',
    'cicilan'
    );

        if ($request->filled('range_tanggal')) {

            $tanggal = explode(
                ' to ',
                $request->range_tanggal
            );

            // JIKA 1 TANGGAL
            if (count($tanggal) == 1) {

                $query->whereDate(
                    'tanggal_pembayaran',
                    $tanggal[0]
                );
            }

            // JIKA RANGE
            if (count($tanggal) == 2) {

                $query->whereBetween(
                    'tanggal_pembayaran',
                    [
                        trim($tanggal[0]),
                        trim($tanggal[1])
                    ]
                );
            }
        }

        $pembayaran = $query
            ->latest()
            ->get();

        return view(
            'pembayaran.index',
            compact('pembayaran')
        );
    }
    // ==========================
    // ADMIN SETUJUI PEMBAYARAN
    // ==========================

   public function setujui($id)
{
    $pembayaran = Pembayaran::findOrFail($id);

    $pembayaran->status = 'disetujui';

    $pembayaran->save();

    $cicilan = Cicilan::find(
        $pembayaran->id_cicilan
    );

    if ($cicilan) {

        $cicilan->status =
            'lunas';

        $cicilan->tanggal_lunas =
            now();

        $cicilan->save();
    }

  $hutang = Hutang::with('agen')->findOrFail(
    $pembayaran->id_hutang
);
   
    $admin = User::where(
        'role',
        'admin'
    )->first();

    // simpan status sebelum berubah
    $statusSebelumnya =
        $hutang->status;

    $hutang->sisa_hutang =
        $hutang->sisa_hutang -
        $pembayaran->jumlah_bayar;

    if ($hutang->sisa_hutang <= 0) {

        $hutang->sisa_hutang = 0;

        $hutang->status = 'lunas';

        // =====================
        // PENILAIAN KREDIT
        // =====================

        $agen = $hutang->agen;

        if (
            $agen &&
            $statusSebelumnya != 'terlambat'
        ) {

            $agen->riwayat_tepat_waktu += 1;

            if (
                $agen->riwayat_tepat_waktu >= 5
            ) {

                $agen->status_kredit =
                    'terpercaya';

                $agen->limit_pinjaman =
                    500000;
            }

            $agen->save();
        }
    }

    $hutang->save();
$owner = User::where('role', 'owner')->first();

if ($owner && $hutang->status == 'lunas') {

    Mail::to($owner->email)->send(

        new NotificationMail(

            $owner->username,

            'Hutang Agen Telah Lunas',

            'Halo ' . $owner->username . ',

Hutang milik agen ' .
$hutang->agen->username .
' telah dinyatakan LUNAS.

Silakan login ke Sistem Informasi Piutang apabila ingin melihat riwayat pembayaran.'

        )

    );


}

// NOTIFIKASI UNTUK ADMIN
Notifikasi::create([
    'id_user' => $admin->id,
    'judul' => 'Pembayaran Disetujui',
    'pesan' =>
        'Pembayaran milik agen ' .
        $hutang->agen->username .
        ' sebesar Rp' .
        number_format(
            $pembayaran->jumlah_bayar,
            0,
            ',',
            '.'
        ) .
        ' berhasil diverifikasi.',
    'tipe' => 'pembayaran',
    'media' => 'web',
    'tanggal' => now(),
    'status_baca' => 'belum'
]);

$user = \App\Models\User::find($hutang->agen->user_id);

if ($user) {

    Mail::to($user->email)->send(

        new NotificationMail(

            $user->username,

            'Pembayaran Berhasil Diverifikasi',

            $hutang->status == 'lunas'

            ?

            'Selamat!

Pembayaran Anda sebesar Rp' .
            number_format(
                $pembayaran->jumlah_bayar,
                0,
                ',',
                '.'
            ) .
            ' telah berhasil diverifikasi oleh Admin.

Seluruh hutang Anda telah LUNAS.

Terima kasih telah melakukan pembayaran tepat waktu.'

            :

            'Pembayaran Anda sebesar Rp' .
            number_format(
                $pembayaran->jumlah_bayar,
                0,
                ',',
                '.'
            ) .
            ' telah berhasil diverifikasi oleh Admin.

Sisa hutang Anda sekarang sebesar Rp' .
            number_format(
                $hutang->sisa_hutang,
                0,
                ',',
                '.'
            ) .
            '.

Terima kasih.'

        )

    );

}

    return back()->with(
        'success',
        'Pembayaran berhasil disetujui'
    );
}


    // ==========================
    // ADMIN TOLAK PEMBAYARAN
    // ==========================
    // ==========================
    // FORM TOLAK PEMBAYARAN
    // ==========================

    public function formTolak($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        return view(
            'pembayaran.tolak',
            compact('pembayaran')
        );
    }

    // ==========================
    // SIMPAN PENOLAKAN
    // ==========================

   public function simpanTolak(
    Request $request,
    $id
) {
    $request->validate([

        'alasan_penolakan' => 'required'

    ], [

        'alasan_penolakan.required' =>
            'Alasan penolakan wajib diisi'

    ]);

    // Ambil data pembayaran
    $pembayaran = Pembayaran::findOrFail($id);

    // Ambil data hutang beserta agen
    $hutang = Hutang::with('agen')->findOrFail(
        $pembayaran->id_hutang
    );
    
    $admin = User::where(
        'role',
        'admin'
    )->first();

    // Update status pembayaran
    $pembayaran->status = 'ditolak';

    $pembayaran->alasan_penolakan =
        $request->alasan_penolakan;

    $pembayaran->save();

    // NOTIFIKASI UNTUK ADMIN
    Notifikasi::create([
        'id_user' => $admin->id,
        'judul' => 'Pembayaran Ditolak',
        'pesan' =>
            'Pembayaran milik agen ' .
            $hutang->agen->username .
            ' telah berhasil ditolak.',
        'tipe' => 'pembayaran',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);

    $user = User::find($hutang->agen->user_id);

if ($user) {

    Mail::to($user->email)->send(

        new NotificationMail(

            $user->username,

            'Pembayaran Ditolak',

            'Halo ' . $user->username . ',

Mohon maaf, pembayaran Anda sebesar Rp' .
number_format(
    $pembayaran->jumlah_bayar,
    0,
    ',',
    '.'
) .
' belum dapat diverifikasi oleh Admin Partner Pulsa.

Alasan penolakan:

' . $request->alasan_penolakan . '

Silakan lakukan upload bukti pembayaran kembali sesuai petunjuk Admin.

Terima kasih.'

        )

    );

}

    return redirect('/admin/pembayaran')->with(
        'success',
        'Pembayaran berhasil ditolak'
    );
}
    public function riwayat()
    {
        $idAgen = session('id_agen');

        $pembayaran = \App\Models\Pembayaran::whereHas(
            'hutang',
            function ($q) use ($idAgen) {

                $q->where(
                    'id_agen',
                    $idAgen
                );
            }
        )
            ->latest()
            ->get();

        return view(
            'pembayaran.riwayat',
            compact('pembayaran')
        );
    }
}
