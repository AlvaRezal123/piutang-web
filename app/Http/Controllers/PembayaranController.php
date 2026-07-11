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
    // menampilkan formulir pembayaran hutang kepada agen
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
// saat agen klik "Simpan", sistm memvalidasi data pembayaran, memastikan tidak terdapat pembayaran lain yang masih menunggu verifikasi serta nominal pembayaran tidak melebihi sisa hutang.
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

            'bank_tujuan' =>
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

            'bank_tujuan.required' =>
            'Rekening tujuan wajib dipilih',

            'bukti_pembayaran.required' =>
            'Bukti pembayaran wajib diupload'

        ]);
        // Jika memilih "Lainnya", gunakan nama bank yang diketik
        $bankPengirim = $request->bank_pengirim;

        if ($request->bank_pengirim == 'lainnya') {
            $bankPengirim = $request->bank_lain;
        }
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

        // CEK PEMBAYARAN PENDING
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
            $bankPengirim,

            'bank_tujuan' =>
            $request->bank_tujuan,

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
    'status_baca' => 'belum']);
        return redirect('/hutang-saya')
            ->with(
                'success',
                'Pembayaran berhasil dikirim dan menunggu validasi admin'
            );
    }

    // sistem menampilkan seluruh data pembayaran hutang beserta informasi agen, hutang, dan cicilan pada admin
    public function index(Request $request)
    {
        $query = Pembayaran::with(
            'hutang.agen',
            'hutang.cicilan',
            'cicilan'
        );
    // Filter berdasarkan nama agen
    if ($request->filled('cari_agen')) {

        $query->whereHas('hutang.agen', function ($q) use ($request) {
            $q->where(
                'username',
                'like',
                '%' . $request->cari_agen . '%'
            );

        });
}
    // Filter berdasarkan tanggal
    if ($request->filled('tanggal')) {

        $query->whereDate(
            'tanggal_pembayaran',
            $request->tanggal
        );
    }
    // Filter Tahun
    if ($request->filled('tahun')) {

        $query->whereYear(
            'tanggal_pembayaran',
            $request->tahun
        );
    }
    // Filter Status
    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }
    $pembayaran = $query
        ->latest()
        ->get();
    $pembayaranData = $pembayaran->mapWithKeys(function ($p) {
        return [
            $p->id => [
                'agen' => $p->hutang->agen->username,
                'idAgen' => $p->hutang->agen->id_agen_pp ?? '-',
                'idHutang' => $p->hutang->id,
                'tanggalPengajuan' => $p->hutang->tanggal_pengajuan
                    ? \Carbon\Carbon::parse($p->hutang->tanggal_pengajuan)->format('d M Y')
                    : '-',
                'metode' => $p->hutang->metode,
                'lamaTempo' => $p->hutang->lama_tempo,
                'totalHutang' => number_format($p->hutang->jumlah_hutang, 0, ',', '.'),
                'sisaHutang' => number_format($p->hutang->sisa_hutang, 0, ',', '.'),
                'idCicilanTerpilih' => $p->id_cicilan,
                'statusPembayaranIni' => $p->status,
                'bankPengirim' => $p->bank_pengirim,
                'bankTujuan' => $p->bank_tujuan,
                'cicilan' => $p->hutang->cicilan->map(function ($c) {

                    return [
                        'id' => $c->id,
                        'cicilanKe' => $c->cicilan_ke,
                        'jumlah' => 'Rp' . number_format($c->jumlah_cicilan, 0, ',', '.'),
                        'jatuhTempo' => $c->tanggal_jatuh_tempo
                            ? \Carbon\Carbon::parse($c->tanggal_jatuh_tempo)->format('d M Y')
                            : '-',
                        'status' => $c->status,
                        'tanggalLunas' => $c->tanggal_lunas
                            ? \Carbon\Carbon::parse($c->tanggal_lunas)->format('d M Y')
                            : null,
                                ];

                            })->values(),
                        ],
                    ];

                });

                return view(
                    'pembayaran.index',
                    compact('pembayaran', 'pembayaranData')
                );
            }

 //memverifikasi dan menyetujui pembayaran yang diajukan oleh agen.
 // Setelah pembayaran disetujui, sistem akan memperbarui status pembayaran, status cicilan, sisa hutang, status hutang,
 // serta melakukan penilaian status kredit agen apabila seluruh hutang telah lunas
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
        
        // PENILAIAN KREDIT
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
            '. Terima kasih.'
        )
    );
    // NOTIFIKASI UNTUK AGEN
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Pembayaran Diverifikasi',
        'pesan' =>
            $hutang->status == 'lunas'
            ?
            'Pembayaran Anda sebesar Rp' .
            number_format(
                $pembayaran->jumlah_bayar,
                0,
                ',',
                '.'
            ) .
            ' telah diverifikasi. Seluruh hutang Anda telah LUNAS.'
            :
            'Pembayaran Anda sebesar Rp' .
            number_format(
                $pembayaran->jumlah_bayar,
                0,
                ',',
                '.'
            ) .
            ' telah diverifikasi. Sisa hutang Anda Rp' .
            number_format(
                $hutang->sisa_hutang,
                0,
                ',',
                '.'
            ) . '.',

        'tipe' => 'pembayaran',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);
        }

    return back()->with(
        'success',
        'Pembayaran berhasil disetujui'
    );
}


     //menampilkan formulir penolakan pembayaran yang akan digunakan admin
    // dalam memberikan alasan penolakan terhadap pembayaran yang diajukan agen
    public function formTolak($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        return view(
            'pembayaran.tolak',
            compact('pembayaran')
        );
    }

    //Menolak pembayaran, menyimpan alasan penolakan, serta mengirim email dan notifikasi kepada agen.
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
    ' belum dapat diverifikasi oleh Admin Partner Pulsa. Alasan penolakan:
    ' . $request->alasan_penolakan . '
    Silakan lakukan upload bukti pembayaran kembali sesuai petunjuk Admin.
    Terima kasih.'
        )
    );
    // NOTIFIKASI UNTUK AGEN
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Pembayaran Ditolak',
        'pesan' =>
            'Pembayaran Anda sebesar Rp' .
            number_format(
                $pembayaran->jumlah_bayar,
                0,
                ',',
                '.'
            ) .
            ' ditolak. Alasan: ' . $request->alasan_penolakan,
        'tipe' => 'pembayaran',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);
}
    return redirect('/admin/pembayaran')->with(
        'success',
        'Pembayaran berhasil ditolak'
    );
}
// menampilkan seluruh riwayat pembayaran yang telah dilakukan oleh agen
    public function riwayat()
    {
        $idAgen = session('id_agen');

        $pembayaran = \App\Models\Pembayaran::with(
            'hutang.cicilan',
            'cicilan'
        )
            ->whereHas(
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

        // Data untuk modal detail cicilan, disiapkan di sini
        // supaya di Blade cukup satu kali @json() tanpa perlu
        // menyisipkan @foreach di dalam blok <script>.
        $pembayaranData = $pembayaran->mapWithKeys(function ($p) {

            return [
                $p->id => [
                    'idHutang' => $p->hutang->id,
                    'tanggalPengajuan' => $p->hutang->tanggal_pengajuan
                        ? \Carbon\Carbon::parse($p->hutang->tanggal_pengajuan)->format('d M Y')
                        : '-',
                    'metode' => $p->hutang->metode,
                    'lamaTempo' => $p->hutang->lama_tempo,
                    'totalHutang' => number_format($p->hutang->jumlah_hutang, 0, ',', '.'),
                    'sisaHutang' => number_format($p->hutang->sisa_hutang, 0, ',', '.'),
                    'idCicilanTerpilih' => $p->id_cicilan,

                    // FIX: status pembayaran ini sendiri (pending | disetujui | ditolak).
                    // Dipakai di modal detail supaya baris cicilan yang terkait
                    // pembayaran yang ditolak/pending tidak ikut menampilkan
                    // status "Lunas" milik cicilan secara global (yang bisa saja
                    // sudah lunas lewat pengajuan lain yang disetujui belakangan).
                    'statusPembayaranIni' => $p->status,

                    'cicilan' => $p->hutang->cicilan->map(function ($c) {

                        return [
                            'id' => $c->id,
                            'cicilanKe' => $c->cicilan_ke,
                            'jumlah' => 'Rp' . number_format($c->jumlah_cicilan, 0, ',', '.'),
                            'jatuhTempo' => $c->tanggal_jatuh_tempo
                                ? \Carbon\Carbon::parse($c->tanggal_jatuh_tempo)->format('d M Y')
                                : '-',
                            'status' => $c->status,
                            'tanggalLunas' => $c->tanggal_lunas
                                ? \Carbon\Carbon::parse($c->tanggal_lunas)->format('d M Y')
                                : null,
                        ];

                    })->values(),
                ],
            ];

        });

        return view(
            'pembayaran.riwayat',
            compact('pembayaran', 'pembayaranData')
        );
    }
}