<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Hutang;
use App\Models\Notifikasi;
use App\Models\Cicilan;

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

            'keterangan_pembayaran' =>
            'nullable',

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
        $hutang = Hutang::findOrFail(
            $request->id_hutang
        );

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

            'keterangan_pembayaran' =>
            $request->keterangan_pembayaran,

            'bukti_pembayaran' =>
            $bukti,

            'status' =>
            'pending'
        ]);
        Notifikasi::create([

            'id_agen' => $hutang->id_agen,

            'judul' => 'Pembayaran Dikirim',

           'pesan' =>
    'Bukti pembayaran sebesar Rp' .
    number_format(
        $request->jumlah_bayar,
        0,
        ',',
        '.'
    ) .
    ' telah berhasil dikirim dan sedang menunggu verifikasi admin.',

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

        $hutang = Hutang::findOrFail(
            $pembayaran->id_hutang
        );

        $hutang->sisa_hutang =
            $hutang->sisa_hutang -
            $pembayaran->jumlah_bayar;

        if ($hutang->sisa_hutang <= 0) {

            $hutang->sisa_hutang = 0;

            $hutang->status = 'lunas';
        }

        $hutang->save();
        Notifikasi::create([

            'id_agen' =>
            $hutang->id_agen,

        
            'judul' => 'Pembayaran Diverifikasi',

            'pesan' =>
    'Pembayaran sebesar Rp' .
    number_format(
        $pembayaran->jumlah_bayar,
        0,
        ',',
        '.'
    ) .
    ' telah berhasil diverifikasi dan sisa piutang Anda telah diperbarui.',

            'tipe' =>
            'persetujuan',

            'media' =>
            'web',

            'tanggal' =>
            now(),

            'status_baca' =>
            'belum'
        ]);

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

            'alasan_penolakan' =>
            'required'

        ], [

            'alasan_penolakan.required' =>
            'Alasan penolakan wajib diisi'

        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->status =
            'ditolak';

        $pembayaran->alasan_penolakan =
            $request->alasan_penolakan;

        $pembayaran->save();
        Notifikasi::create([

            'id_agen' =>
            $pembayaran->hutang->id_agen,

           'judul' =>
    'Verifikasi Pembayaran Ditolak',

            'pesan' =>
    'Verifikasi pembayaran belum dapat disetujui. Silakan periksa keterangan yang diberikan admin.',

            'tipe' =>
            'pembayaran',

            'media' =>
            'web',

            'tanggal' =>
            now(),

            'status_baca' =>
            'belum'
        ]);

        return redirect(
            '/admin/pembayaran'
        )->with(
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
