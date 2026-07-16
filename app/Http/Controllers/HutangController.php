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
use App\Models\PeriodeCicilan;

class HutangController extends Controller
{

     // menampilkan halaman formulir pengajuan hutang
// menampilkan halaman formulir pengajuan hutang
public function create()
{
    $agen = Agen::find(session('id_agen'));

    if (!$agen) {
        return redirect('/login')
            ->with('error', 'Silakan login terlebih dahulu');
    }

    // Ambil daftar periode cicilan yang aktif
    $periode = PeriodeCicilan::where('status', 'aktif')
        ->orderBy('jumlah_bulan')
        ->get();

    return view(
        'hutang.create',
        compact(
            'agen',
            'periode'
        )
    );
}

    // Pengajuan Hutang Agen
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

// ================================
// CEK HAK AKSES CICILAN
// ================================
if (
    $request->metode == 'cicil' &&
    !$agen->akses_cicilan
) {
    return back()->with(
        'error',
        'Fasilitas pembayaran cicilan belum diaktifkan oleh owner.'
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
        // cek limit pinjaman berdasarkan status kredit
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
                    'Halo ' . $owner->username . 
                    ',Agen ' . $agen->username . ' telah mengajukan hutang sebesar Rp' .
                    number_format(
                        $hutang->jumlah_hutang,
                        0,
                        ',',
                        '.'
                    ) . '. Silakan login ke Sistem Informasi Piutang untuk melakukan proses persetujuan.'
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

  // menampilkan halaman Approval pengajuan hutang oleh owner
    public function index(request $request)
        {
            $query = Hutang::with('agen');

        if ($request->filled('search')) {
            $query->whereHas('agen', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $hutang = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
    // owner menyetujui pengajuan hutang yang diajukan oleh agen
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
            'Selamat! Pengajuan hutang Anda telah disetujui oleh Owner Partner Pulsa.
             Silakan menunggu proses pencairan saldo oleh Admin.'
        ));
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
    ]);}
        return back()->with(
            'success',
            'Pengajuan berhasil disetujui owner'
        );
    }

    // owner menolak pengajuan hutang yang telah di ajukan oleh agen
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
// Menampilkan formulir untuk mengisi alasan penolakan pengajuan hutang oleh owner
public function formTolak($id)
{
    $hutang = Hutang::with('agen')->findOrFail($id);

return view(
    'hutang.tolak',
    compact('hutang')
);
}

// menolak pengajuan hutang dengan menyimpan alasan penolakan, mengubah status menjadi ditolak
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
            Alasan penolakan: ' . $request->alasan_penolakan . '
            Silakan lakukan pengajuan kembali setelah memenuhi persyaratan yang berlaku. 
            Terima kasih.'
        ));

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
    ]);}

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
    ]);}
    return redirect('/owner/hutang')
        ->with('success', 'Pengajuan berhasil ditolak');
}

    // Menampilkan Halaman Pencairan Hutang (data yang sudah disetujui oleh si owner)
  public function pencairan(Request $request)
{
    $query = Hutang::with('agen')
        ->whereIn('status', [
            'disetujui',
            'berjalan',
            'lunas'
        ]);

    // Cari nama agen
    if ($request->filled('search')) {
        $query->whereHas('agen', function ($q) use ($request) {
            $q->where('username', 'like', '%' . $request->search . '%');
        });
    }

    // Filter Status
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    // Filter Tanggal
    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal_pengajuan', $request->tanggal);
    }

    // Filter Tahun
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_pengajuan', $request->tahun);
    }

    $hutang = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('hutang.pencairan', compact('hutang'));
}

    // melakukan pencairan saldo kepada agen setelah pengajuan hutang disetujui oleh owner
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

    //menampilkan seluruh data hutang beserta informasi agen yang terkait
  public function semuaHutang(request $request)
{
    $query = Hutang::with('agen');

if ($request->filled('bulan')) {
    $query->whereMonth('tanggal_pengajuan', $request->bulan);
}

if ($request->filled('tahun')) {
    $query->whereYear('tanggal_pengajuan', $request->tahun);
}

if ($request->filled('status') && $request->status != 'all') {
    $query->where('status', $request->status);
}

$hutang = $query
    ->latest()
    ->paginate(10)
    ->withQueryString();

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

// menu Monitoring Hutang untuk admin
public function monitoringAdmin(Request $request)
{
    $query = Hutang::with('agen');

    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_pengajuan', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_pengajuan', $request->tahun);
    }

    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    $hutang = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $totalPiutang = Hutang::whereIn('status', ['berjalan','terlambat','lunas'])
        ->sum('jumlah_hutang');

    $sudahKembali = Hutang::where('status','lunas')
        ->sum('jumlah_hutang');

    $belumKembali = Hutang::whereIn('status',['berjalan','terlambat'])
        ->sum('sisa_hutang');

    $jumlahTerlambat = Hutang::where('status','terlambat')->count();

    return view('hutang.semua-admin', compact(
        'hutang',
        'totalPiutang',
        'sudahKembali',
        'belumKembali',
        'jumlahTerlambat'
    ));
}

    //menampilkan seluruh riwayat pengajuan hutang milik agen pada role agen // halaman hutang saya agen
    public function hutangSaya (Request $request)
    {
        $idAgen = session('id_agen');

  $query = Hutang::where('id_agen', $idAgen);

if ($request->filled('tanggal')) {
    $query->whereDate('tanggal_pengajuan', $request->tanggal);
}

if ($request->filled('status') && $request->status != 'all') {
    $query->where('status', $request->status);
}

$hutang = $query
    ->latest()
    ->paginate(10)
    ->withQueryString();
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
    );}

// menampilkan halaman formulir pencairan untuk di eksekusi oleh admin
public function formPencairan($id)
{
    $hutang = Hutang::with('agen')
        ->findOrFail($id);

    return view(
        'hutang.form-pencairan',
        compact('hutang')
    );
}

// melakukan pencairan saldo piutang kepada agen setelah pengajuan hutang disetujui oleh owne
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

    $hutang->tanggal_jatuh_tempo =
        $tanggalPencairan->copy()->addMonth();

} else {

    $hutang->tanggal_jatuh_tempo =
        $tanggalPencairan->copy()->addMonths(
            (int) $hutang->lama_tempo
        );

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
        ' telah berhasil dicairkan ke akun Partner Pulsa. Silakan login ke aplikasi Partner Pulsa untuk mulai menggunakan saldo tersebut. Terima kasih.'
        ));

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
        ]); }
        Cicilan::where(
    'id_hutang',
    $hutang->id
)->delete();

$jumlahBulan = (int) $hutang->lama_tempo;

$nominalCicilan =
    floor($hutang->jumlah_hutang / $jumlahBulan);

$sisa =
    $hutang->jumlah_hutang -
    ($nominalCicilan * $jumlahBulan);

for ($i = 1; $i <= $jumlahBulan; $i++) {

    $nominal = $nominalCicilan;

    if ($i == $jumlahBulan) {

        $nominal += $sisa;

    }

    Cicilan::create([

        'id_hutang' => $hutang->id,

        'cicilan_ke' => $i,

        'jumlah_cicilan' => $nominal,

        'tanggal_jatuh_tempo' =>
            $tanggalPencairan
                ->copy()
                ->addMonths($i),

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
                // menampilkan informasi lengkap mengenai hutang yang dimiliki oleh agen, termasuk data cicilan dan riwayat pembayaran yang telah dilakukan pada role agen
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
// menampilkan informasi lengkap mengenai data hutang yang dipilih, beserta riwayat pengajuan hutang milik agen oleh admin
    public function detail(Request $request, $id)
{
    $hutang = Hutang::with('agen')->findOrFail($id);

    $query = Hutang::where('id_agen', $hutang->id_agen);

    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_pengajuan', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_pengajuan', $request->tahun);
    }

    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    $riwayat = $query
        ->orderBy('tanggal_pengajuan', 'desc')
        ->paginate(10)
        ->withQueryString();


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
        //menampilkan seluruh data pengajuan hutang yang diajukan oleh agen oleh admin pada role admin
     public function pengajuanHutangAdmin(Request $request)
{
    $query = Hutang::with('agen');

    // Search nama agen
    if ($request->filled('search')) {

        $query->whereHas('agen', function ($q) use ($request) {

            $q->where(
                'username',
                'like',
                '%' . $request->search . '%'
            );

        });

    }

    $hutang = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view(
        'hutang.pengajuan-admin',
        compact('hutang')
    );
}
    // menampilkan informasi lengkap mengenai pengajuan hutang yang dipilih oleh admin
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

    // Menampilkan dashboard owner
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
    ->orderBy('updated_at', 'desc')
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