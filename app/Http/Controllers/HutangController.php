<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\Hutang;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Cicilan;

class HutangController extends Controller
{
    // FORM PENGAJUAN
    public function create()
    {
        $agen = Agen::find(session('id_agen'));

        // jika session tidak ada
        if (!$agen) {
            return redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        return view('hutang.create', compact('agen'));
    }

    // SIMPAN PENGAJUAN
    public function store(Request $request)
    {
        // ambil data agen login
        $agen = Agen::find(session('id_agen'));

        // cek apakah agen ditemukan
        if (!$agen) {

            return back()->with(
                'error',
                'Agen tidak ditemukan'
            );
        }

        // cek hutang aktif
        $hutangAktif = Hutang::where('id_agen', $agen->id)
            ->whereIn('status', [
                'pending',
                'disetujui',
                'berjalan',
                'terlambat'
            ])
            ->exists();

        if ($hutangAktif) {

            return back()->with(
                'error',
                'Anda masih memiliki hutang aktif'
            );
        }

        // cek limit pinjaman
        if ($request->jumlah_hutang > $agen->limit_pinjaman) {

            return back()->with(
                'error',
                'Pengajuan melebihi limit pinjaman'
            );
        }

        // tanggal pengajuan
        $tanggal_pengajuan = now();

        // menentukan jatuh tempo
        if ($request->metode == 'cash') {

            $tanggal_jatuh_tempo = now()->addMonth(1);

            $lama_tempo = '1 bulan';

        } else {

            $lama_tempo = $request->lama_tempo;

            if ($request->lama_tempo == '2 bulan') {

                $tanggal_jatuh_tempo = now()->addMonths(2);

            } else {

                $tanggal_jatuh_tempo = now()->addMonths(3);
            }
        }

        // simpan hutang
       $hutang = Hutang::create([

            'id_agen' => $agen->id,

            'jumlah_hutang' => $request->jumlah_hutang,

            'metode' => $request->metode,

            'lama_tempo' => $lama_tempo,

            'tanggal_pengajuan' => $tanggal_pengajuan,

            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,

            'sisa_hutang' => $request->jumlah_hutang,

            'status' => 'pending',

             'catatan_pengajuan' => $request->catatan_pengajuan
        ]);

        Notifikasi::create([

    'id_agen' => $agen->id,

    'judul' => 'Pengajuan Piutang',

    'pesan' =>
    'Pengajuan piutang sebesar Rp' .
    number_format(
        $hutang->jumlah_hutang,
        0,
        ',',
        '.'
    ) .
    ' telah berhasil dikirim dan sedang menunggu proses verifikasi.',

    'tipe' => 'pengajuan',

    'media' => 'web',

    'tanggal' => now(),

    'status_baca' => 'belum'
]);


     return redirect()->to('/hutang-saya')->with(
    'success',
    'Pengajuan hutang berhasil dikirim'
);
    }

    // OWNER LIHAT HUTANG
    public function index()
    {
        $hutang = Hutang::with('agen')->get();

        return view('hutang.index', compact('hutang'));
    }
    public function detail($id)
{
    // hutang yang sedang dipilih
    $hutang = Hutang::with('agen')
        ->findOrFail($id);

    // semua riwayat hutang agen tersebut
    $riwayat = Hutang::where(
        'id_agen',
        $hutang->id_agen
    )
    ->orderBy(
        'tanggal_pengajuan',
        'desc'
    )
    ->get();

    return view(
        'hutang.detail',
        compact(
            'hutang',
            'riwayat'
        )
    );
}

    // OWNER SETUJUI
    public function setujui($id)
    {
        $hutang = Hutang::findOrFail($id);
        $hutang->status = 'disetujui';
        $hutang->save();

        Notifikasi::create([
    'id_agen' => $hutang->id_agen,

    'judul' => 'Hutang Disetujui',

    'pesan' =>
    'Pengajuan piutang Anda telah disetujui dan siap diproses untuk pencairan saldo.',

    'tipe' => 'persetujuan',

    'media' => 'web',

    'tanggal' => now(),

    'status_baca' => 'belum'
]);

        return back()->with(
            'success',
            'Pengajuan berhasil disetujui owner'
        );
    }

    // OWNER TOLAK
    public function tolak($id)
    {
        $hutang = Hutang::findOrFail($id);

        $hutang->status = 'ditolak';

        $hutang->save();

        return back()->with(
            'success',
            'Pengajuan berhasil ditolak owner'
        );
    }
// FORM TOLAK HUTANG
public function formTolak($id)
{
    $hutang = Hutang::findOrFail($id);

    return view(
        'hutang.tolak',
        compact('hutang')
    );
}

// SIMPAN PENOLAKAN
public function simpanTolak(
    Request $request,
    $id
)
{
    $request->validate([

        'alasan_penolakan' =>
            'required'

    ], [

        'alasan_penolakan.required' =>
            'Alasan penolakan wajib diisi'

    ]);

    $hutang = Hutang::findOrFail($id);

    $hutang->status =
        'ditolak';

    $hutang->alasan_penolakan =
        $request->alasan_penolakan;

    $hutang->save();

    return redirect(
        '/owner/hutang'
    )->with(
        'success',
        'Pengajuan berhasil ditolak'
    );
}
    // HALAMAN PENCAIRAN ADMIN
public function pencairan()
{
    $hutang = Hutang::with('agen')
        ->whereIn('status', [
            'disetujui',
            'berjalan',
            'lunas'
        ])
        ->latest()
        ->get();

    return view(
        'hutang.pencairan',
        compact('hutang')
    );
}
    // ADMIN BERIKAN SALDO
    public function berikanSaldo($id)
    {
        $hutang = Hutang::findOrFail($id);

        $hutang->status = 'berjalan';

        $hutang->save();

        return back()->with(
            'success',
            'Saldo berhasil diberikan ke agen'
        );
    }

    // MONITORING HUTANG
    public function semuaHutang()
    {
        $hutang = Hutang::with('agen')->get();

        return view('hutang.semua', compact('hutang'));
    }
public function hutangSaya()
{
$id_agen = session('id_agen');


$hutang = Hutang::where(
    'id_agen',
    $id_agen
)->get();

foreach ($hutang as $h) {

    // cek pembayaran pending

    $pembayaranPending = Pembayaran::where(
        'id_hutang',
        $h->id
    )
    ->where(
        'status',
        'pending'
    )
    ->exists();

    $h->pembayaran_pending =
        $pembayaranPending;

    // ambil pembayaran terakhir

    $pembayaranTerakhir =
        Pembayaran::where(
            'id_hutang',
            $h->id
        )
        ->latest()
        ->first();

    $h->pembayaran_terakhir =
        $pembayaranTerakhir;
}

return view(
    'hutang.saya',
    compact('hutang')
);


}
// FORM PENCAIRAN
public function formPencairan($id)
{
    $hutang = Hutang::with('agen')
        ->findOrFail($id);

    return view(
        'hutang.form-pencairan',
        compact('hutang')
    );
}
// SIMPAN PENCAIRAN
public function simpanPencairan(
    Request $request,
    $id
)
{
    $request->validate([

        'bukti_pencairan' =>
            'required|image|mimes:jpg,jpeg,png',

        'keterangan_pencairan' =>
            'required'

    ]);

    $hutang = Hutang::findOrFail($id);

    // upload bukti

    $namaFile = time() . '_pencairan.' .
        $request->bukti_pencairan->extension();

    $request->bukti_pencairan->move(
        public_path('uploads'),
        $namaFile
    );

    $hutang->bukti_pencairan =
        $namaFile;

    $hutang->keterangan_pencairan =
        $request->keterangan_pencairan;

    $hutang->tanggal_pencairan =
        now();

    $hutang->status =
        'berjalan';

    $hutang->save();

    $jumlahBulan = 1;

if ($hutang->lama_tempo == '2 bulan') {

    $jumlahBulan = 2;

} elseif ($hutang->lama_tempo == '3 bulan') {

    $jumlahBulan = 3;
}

$nominalCicilan =
    $hutang->jumlah_hutang /
    $jumlahBulan;

for ($i = 1; $i <= $jumlahBulan; $i++) {

    Cicilan::create([

        'id_hutang' =>
            $hutang->id,

        'cicilan_ke' =>
            $i,

        'jumlah_cicilan' =>
            $nominalCicilan,

        'tanggal_jatuh_tempo' =>
            \Carbon\Carbon::parse(
                $hutang->tanggal_pengajuan
            )->addMonths($i),

        'status' =>
            'belum'

    ]);
}
 Notifikasi::create([
    'id_agen' => $hutang->id_agen,

    'judul' => 'Saldo Dicairkan',

    'pesan' =>
    'Saldo piutang telah berhasil dicairkan dan telah masuk ke akun Partner Pulsa Anda.',

    'tipe' => 'pencairan',

    'media' => 'web',

    'tanggal' => now(),

    'status_baca' => 'belum'
]);
    return redirect(
        '/admin/pencairan'
    )->with(
        'success',
        'Saldo berhasil dicairkan'
    );
}
public function detailSaya($id)
{
    $hutang = Hutang::with(
        'cicilan'
    )->findOrFail($id);

    $pembayaran = Pembayaran::where(
        'id_hutang',
        $id
    )
    ->orderBy(
        'tanggal_pembayaran',
        'desc'
    )
    ->get();

    return view(
        'hutang.detail-saya',
        compact(
            'hutang',
            'pembayaran'
        )
    );
}
public function dashboardOwner()
{
    $totalPengajuan = Hutang::count();

    $pending = Hutang::where(
        'status',
        'pending'
    )->count();

    $disetujui = Hutang::where(
        'status',
        'disetujui'
    )->count();

    $ditolak = Hutang::where(
        'status',
        'ditolak'
    )->count();

    $aktivitas = Hutang::with('agen')
        ->latest()
        ->take(5)
        ->get();

    return view(
        'dashboard.owner',
        compact(
            'totalPengajuan',
            'pending',
            'disetujui',
            'ditolak',
            'aktivitas'
        )
    );
}

}