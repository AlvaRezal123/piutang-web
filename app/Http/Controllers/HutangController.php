<?php

namespace App\Http\Controllers;
use App\Models\Agen;
use App\Models\Hutang;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Cicilan;
use App\Models\User;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;    


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


if ($request->metode == 'cash') {

    $lama_tempo = null;

} else {

    $lama_tempo = $request->lama_tempo;

}

$tanggal_jatuh_tempo = null;

        // simpan hutang
       $hutang = Hutang::create([

            'id_agen' => $agen->id,

            'jumlah_hutang' => $request->jumlah_hutang,

            'metode' => $request->metode,

            'lama_tempo' => $lama_tempo,

            'tanggal_pengajuan' => $tanggal_pengajuan,

            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,

            'sisa_hutang' => $request->jumlah_hutang,

            'status' => 'pending'

             
        ]);
       $owner = User::where('role', 'owner')->first();

if ($owner) {
  
    Mail::to($owner->email)->send(

        new NotificationMail(

            $owner->username,

            'Pengajuan Hutang Baru',

            'Halo ' . $owner->username . ',

Agen ' . $agen->username . ' telah mengajukan hutang sebesar Rp' .
number_format(
    $hutang->jumlah_hutang,
    0,
    ',',
    '.'
) . '.

Silakan login ke Sistem Informasi Piutang untuk melakukan proses persetujuan.'

        )

    );
}
        $admin = User::where('role', 'admin')->first();

       
if ($admin) {

    Notifikasi::create([

        'id_user' => $admin->id,

        'judul' => 'Pengajuan Hutang Baru',

        'pesan' =>
            'Agen ' .
            $agen->username .
            ' mengajukan hutang sebesar Rp' .
            number_format(
                $hutang->jumlah_hutang,
                0,
                ',',
                '.'
            ) .
            ' dan menunggu proses verifikasi.',

        'tipe' => 'pengajuan',

        'media' => 'web',

        'tanggal' => now(),

        'status_baca' => 'belum'

    ]);

}
     return redirect()->to('/hutang-saya')->with(
    'success',
    'Pengajuan hutang berhasil dikirim'
);
    }

    // OWNER LIHAT HUTANG
    public function index()
{
    $hutang = Hutang::with('agen')->latest()->get();

    // Card 1: Total Piutang yang sudah dicairkan
    $totalPiutang = Hutang::whereIn('status', ['berjalan', 'terlambat', 'lunas'])
        ->sum('jumlah_hutang');

    // Card 2: Sudah Kembali (lunas)
    $sudahKembali = Hutang::where('status', 'lunas')
        ->sum('jumlah_hutang');

    // Card 3: Belum Kembali (berjalan + terlambat) — pakai sisa_hutang bukan jumlah_hutang
    $belumKembali = Hutang::whereIn('status', ['berjalan', 'terlambat'])
        ->sum('sisa_hutang');

    // Card 4: Jumlah hutang terlambat (count, bukan nominal)
    $jumlahTerlambat = Hutang::where('status', 'terlambat')->count();

    return view('hutang.index', compact(
        'hutang',
        'totalPiutang',
        'sudahKembali',
        'belumKembali',
        'jumlahTerlambat'
    ));
}
    // OWNER SETUJUI
    public function setujui($id)
    {
        $hutang = Hutang::with('agen')->findOrFail($id);
        $userAgen = $hutang->agen->user;

        $admin = User::where(
            'role',
            'admin'
        )->first();
        $hutang->status = 'disetujui';
        $hutang->save();
        $user = User::find($hutang->agen->user_id);

if ($user) {

    Mail::to($user->email)->send(

        new NotificationMail(

            $user->username,

            'Pengajuan Hutang Disetujui',

            'Selamat!

Pengajuan hutang Anda telah disetujui oleh Owner Partner Pulsa.

Silakan menunggu proses pencairan saldo oleh Admin.'

        )

    );

    // NOTIFIKASI UNTUK AGEN
    Notifikasi::create([

        'id_user' => $user->id,

        'judul' => 'Pengajuan Hutang Disetujui',

        'pesan' =>
            'Pengajuan hutang Anda sebesar Rp' .
            number_format(
                $hutang->jumlah_hutang,
                0,
                ',',
                '.'
            ) .
            ' telah disetujui oleh Owner. Menunggu proses pencairan oleh Admin.',

        'tipe' => 'persetujuan',

        'media' => 'web',

        'tanggal' => now(),

        'status_baca' => 'belum'

    ]);

}



// NOTIFIKASI UNTUK ADMIN
if ($admin) {
    Notifikasi::create([
        'id_user' => $admin->id,
        'judul' => 'Pengajuan Disetujui',
        'pesan' =>
            'Owner baru saja menyetujui pengajuan hutang agen ' .
            $hutang->agen->username .
            '.',
        'tipe' => 'persetujuan',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);
}
    
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
    $hutang = Hutang::with('agen')->findOrFail($id);

return view(
    'hutang.tolak',
    compact('hutang')
);
}

// SIMPAN PENOLAKAN
public function simpanTolak(Request $request, $id)
{
    $request->validate([
        'alasan_penolakan' => 'required'
    ], [
        'alasan_penolakan.required' => 'Alasan penolakan wajib diisi'
    ]);

    $hutang = Hutang::with('agen')->findOrFail($id);
    $userAgen = $hutang->agen->user;
    $admin = User::where(
        'role',
        'admin'
    )->first();
    $hutang->status = 'ditolak';
    $hutang->alasan_penolakan = $request->alasan_penolakan;
    $hutang->save();
    $user = User::find($hutang->agen->user_id);

if ($user) {

    Mail::to($user->email)->send(

        new NotificationMail(

            $user->username,

            'Pengajuan Hutang Ditolak',

            'Halo ' . $user->username . ',

Mohon maaf, pengajuan hutang Anda belum dapat disetujui oleh Owner Partner Pulsa.

Alasan penolakan:

' . $request->alasan_penolakan . '

Silakan lakukan pengajuan kembali setelah memenuhi persyaratan yang berlaku.

Terima kasih.'

        )

    );

    // NOTIFIKASI UNTUK AGEN
    Notifikasi::create([

        'id_user' => $user->id,

        'judul' => 'Pengajuan Hutang Ditolak',

        'pesan' =>
            'Pengajuan hutang Anda ditolak oleh Owner. Alasan: ' .
            $request->alasan_penolakan,

        'tipe' => 'pengajuan',

        'media' => 'web',

        'tanggal' => now(),

        'status_baca' => 'belum'

    ]);

}

// NOTIFIKASI UNTUK ADMIN
if ($admin) {
    Notifikasi::create([
        'id_user' => $admin->id,
        'judul' => 'Pengajuan Ditolak',
        'pesan' =>
            'Owner baru saja menolak pengajuan hutang agen ' .
            $hutang->agen->username .
            '.',
        'tipe' => 'pengajuan',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);

}
    return redirect('/owner/hutang')
        ->with('success', 'Pengajuan berhasil ditolak');
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

    // Card 1: Total Piutang yang sudah dicairkan
    $totalPiutang = Hutang::whereIn('status', ['berjalan', 'terlambat', 'lunas'])
        ->sum('jumlah_hutang');

    // Card 2: Sudah Kembali (lunas)
    $sudahKembali = Hutang::where('status', 'lunas')
        ->sum('jumlah_hutang');

    // Card 3: Belum Kembali (berjalan + terlambat)
    $belumKembali = Hutang::whereIn('status', ['berjalan', 'terlambat'])
        ->sum('sisa_hutang');

    // Card 4: Jumlah hutang terlambat
    $jumlahTerlambat = Hutang::where('status', 'terlambat')->count();

    return view('hutang.semua', compact(
        'hutang',
        'totalPiutang',
        'sudahKembali',
        'belumKembali',
        'jumlahTerlambat'
    ));
}
public function hutangSaya()
{
    $idAgen = session('id_agen');

    $hutang = Hutang::where(
        'id_agen',
        $idAgen
    )
    ->latest()
    ->get();

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
public function simpanPencairan(Request $request, $id)
{
    $request->validate([
        'bukti_pencairan' => 'required|image|mimes:jpg,jpeg,png',
    ]);

    $hutang = Hutang::with('agen')->findOrFail($id);
    $userAgen = $hutang->agen->user;
    $admin = User::where('role', 'admin')->first();

    // Upload bukti
    $namaFile = time() . '_pencairan.' .
        $request->bukti_pencairan->extension();

    $request->bukti_pencairan->move(
        public_path('uploads'),
        $namaFile
    );

    $hutang->bukti_pencairan = $namaFile;

    // Tanggal pencairan
    $tanggalPencairan = now();

    $hutang->tanggal_pencairan = $tanggalPencairan;

    // Hitung tanggal jatuh tempo mulai dari tanggal pencairan
    if ($hutang->metode == 'cash') {

        $hutang->tanggal_jatuh_tempo = $tanggalPencairan->copy()->addMonth();

    } else {

        if ($hutang->lama_tempo == '2 bulan') {

            $hutang->tanggal_jatuh_tempo = $tanggalPencairan->copy()->addMonths(2);

        } else {

            $hutang->tanggal_jatuh_tempo = $tanggalPencairan->copy()->addMonths(3);

        }
    }

    $hutang->status = 'berjalan';

    $hutang->save();

    $user = User::find($hutang->agen->user_id);

    if ($user) {

        Mail::to($user->email)->send(

            new NotificationMail(

                $user->username,

                'Saldo Piutang Berhasil Dicairkan',

                'Halo ' . $user->username . ',

Saldo piutang Anda sebesar Rp' .
number_format(
    $hutang->jumlah_hutang,
    0,
    ',',
    '.'
) .
' telah berhasil dicairkan ke akun Partner Pulsa.

Silakan login ke aplikasi Partner Pulsa untuk mulai menggunakan saldo tersebut.

Terima kasih.'

            )

        );

        // NOTIFIKASI UNTUK AGEN
        Notifikasi::create([

            'id_user' => $user->id,

            'judul' => 'Saldo Piutang Dicairkan',

            'pesan' =>
                'Saldo piutang Anda sebesar Rp' .
                number_format(
                    $hutang->jumlah_hutang,
                    0,
                    ',',
                    '.'
                ) .
                ' telah berhasil dicairkan.',

            'tipe' => 'pencairan',

            'media' => 'web',

            'tanggal' => now(),

            'status_baca' => 'belum'

        ]);

    }

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

            'id_hutang' => $hutang->id,

            'cicilan_ke' => $i,

            'jumlah_cicilan' => $nominalCicilan,

            // Sekarang dihitung dari tanggal pencairan
            'tanggal_jatuh_tempo' =>
                \Carbon\Carbon::parse(
                    $hutang->tanggal_pencairan
                )->addMonths($i),

            'status' => 'belum'

        ]);
    }

    // NOTIFIKASI UNTUK ADMIN
    if ($admin) {

        Notifikasi::create([
            'id_user' => $admin->id,
            'judul' => 'Saldo Dicairkan',
            'pesan' =>
                'Saldo piutang milik agen ' .
                $hutang->agen->username .
                ' telah berhasil dicairkan.',
            'tipe' => 'pencairan',
            'media' => 'web',
            'tanggal' => now(),
            'status_baca' => 'belum'
        ]);

        $owner = User::where('role', 'owner')->first();

        if ($owner) {

            Mail::to($owner->email)->send(

                new NotificationMail(

                    $owner->username,

                    'Saldo Piutang Telah Dicairkan',

                    'Halo ' . $owner->username . ',

Saldo piutang milik agen ' .
$hutang->agen->username .
' sebesar Rp' .
number_format(
    $hutang->jumlah_hutang,
    0,
    ',',
    '.'
) .
' telah berhasil dicairkan oleh Admin.'

                )

            );

        }

    }

    return redirect('/admin/pencairan')->with(
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

public function detail($id)
{
    $hutang = Hutang::with('agen')->findOrFail($id);

    $riwayat = Hutang::where(
        'id_agen',
        $hutang->id_agen
    )
    ->orderBy(
        'tanggal_pengajuan',
        'desc'
    )
    ->get();

    // Ambil cicilan yang sedang aktif
    $cicilanAktif = null;

    if ($hutang->metode == 'cicil') {

        $cicilanAktif = Cicilan::where(
            'id_hutang',
            $hutang->id
        )
        ->whereIn(
            'status',
            [
                'belum',
                'terlambat'
            ]
        )
        ->orderBy(
            'cicilan_ke'
        )
        ->first();
    }

    return view(
        'hutang.detail',
        compact(
            'hutang',
            'riwayat',
            'cicilanAktif'
        )
    );
}
// HUTANG KHUSUS ADMIN
public function pengajuanHutangAdmin()
{
    $hutang = Hutang::with('agen')
        ->latest()
        ->get();

    return view('hutang.pengajuan-admin', compact('hutang'));
}
public function detailAdmin($id)
{
    $hutang = Hutang::with(['agen', 'cicilan'])->findOrFail($id);

    $riwayat = Hutang::where('id_agen', $hutang->id_agen)
        ->orderBy('created_at', 'desc')
        ->get();

    $pembayaran = Pembayaran::where('id_hutang', $id)
        ->orderBy('tanggal_pembayaran', 'desc')
        ->get();

    return view('hutang.detail-admin', compact('hutang', 'riwayat', 'pembayaran'));
}

public function dashboardOwner()
{
    // Statistik Dashboard
    $totalPengajuan = Hutang::count();

    $pending = Hutang::where('status', 'pending')->count();

    $disetujui = Hutang::where('status', 'disetujui')->count();

    $ditolak = Hutang::where('status', 'ditolak')->count();

    $terlambat = Hutang::where('status', 'terlambat')->count();

    $pengajuanHariIni = Hutang::whereDate('created_at', today())->count();

    // Aktivitas Terbaru
    $aktivitas = Hutang::with('agen')
        ->latest()
        ->take(5)
        ->get();

    // Antrian Approval
    $antrianPending = Hutang::where('status', 'pending')
        ->with('agen')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard.owner', compact(
        'totalPengajuan',
        'pending',
        'disetujui',
        'ditolak',
        'terlambat',
        'pengajuanHariIni',
        'aktivitas',
        'antrianPending'
    ));
}
}