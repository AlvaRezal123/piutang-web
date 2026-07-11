<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Agen;
use App\Models\User;
use App\Models\ReferensiAgenPP;
use App\Imports\ReferensiAgenPPImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Models\Hutang;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgenDitolakMail;
use App\Mail\AgenDisetujuiMail;
use App\Models\Cicilan;

class AgenController extends Controller
{
// Mengupdate Profile data agen ( admin dapat mengubah data agen)
public function update(Request $request, $id)
{   
    $agen = Agen::findOrFail($id);
    $request->validate([
        'username' =>
            'required|unique:users,username,' . $agen->user_id,
        'no_hp' =>
            'required',
        'alamat' =>
            'required',
        'nama_usaha' =>
            'nullable'
    ]);
    $fotoToko = $agen->foto_toko_fisik;

if ($request->hasFile('foto_toko_fisik')) {
    $fotoToko =
        time() . '_toko.' .
        $request->foto_toko_fisik->extension();

    $request->foto_toko_fisik->move(
        public_path('uploads'),
        $fotoToko
    );}

    $agen->update([
        'username' => $request->username,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'nama_usaha' => $request->nama_usaha,
        'foto_toko_fisik' => $fotoToko
    ]);

    User::where(
        'id',
        $agen->user_id
    )->update([
        'username' => $request->username,

        'name' => $request->username
    ]);

    return redirect('/agen')
        ->with(
            'success',
            'Data agen berhasil diperbarui'
        );}

        // Menampilkan halaman form pendaftaran agen
    public function create()
    {
        return view('agen.create');
    }
        // Mengecek apakah ID agen PP ada? sudah digunakan? atau belum lewat tabel referensi
    public function cekIdAgenPP(Request $request)
    {
        $referensi = ReferensiAgenPP::find($request->id_agen_pp);
        if (!$referensi) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'ID Agen PP tidak ditemukan'
            ], 404);
        }
        $agenLama = Agen::where('id_agen_pp', $request->id_agen_pp)->first();
        if ($agenLama && $agenLama->status != 'ditolak') {
            $pesan = match($agenLama->status) {
                'pending' => 'Pendaftaran dengan ID ini masih dalam proses verifikasi admin.',
                'aktif' => 'ID ini sudah terdaftar dan aktif. Silakan login.',
                'diblokir' => 'Akun dengan ID ini telah diblokir. Silakan hubungi admin.',
                default => 'ID ini sudah digunakan.',
            };
            return response()->json([
                'status' => 'used',
                'message' => $pesan
            ], 409);
        }
        return response()->json([
            'status' => 'ok',
            'username' => $referensi->username,
            'alamat' => $referensi->alamat,
            'no_hp' => $referensi->no_hp,
        ]);
    }
    // Fungsi untuk mengimpor excel menjadi database
    public function importReferensiAgenPP(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file.required' => 'File Excel wajib diupload',
            'file.mimes' => 'Format file harus xlsx, xls, atau csv',
        ]);
        Excel::import(new ReferensiAgenPPImport, $request->file('file'));
        return back()->with('success', 'Data referensi Agen PP berhasil diimport');
    }
    // Menampilkan data yang telah diimport dari excel (referensi)
    public function referensiIndex()
    {
        $referensi = ReferensiAgenPP::latest()->get();
        $idTerpakai = Agen::whereIn('status', ['pending', 'aktif', 'diblokir'])
            ->pluck('status', 'id_agen_pp');
        return view('agen.referensi', compact('referensi', 'idTerpakai'));
    }
    // Menghapus data pada data referensi yang telah diimport dari excel jika berstatus pending, aktif, atau diblokir maka tidak dapat di hapus
    public function referensiDestroy($id)
    {
        $referensi = ReferensiAgenPP::findOrFail($id);
        $sedangDipakai = Agen::where('id_agen_pp', $id)
            ->whereIn('status', ['pending', 'aktif', 'diblokir'])
            ->exists();
        if ($sedangDipakai) {
            return back()->withErrors([
                'hapus' => 'Data ini sedang digunakan oleh agen terdaftar, tidak bisa dihapus.'
            ]);}
        $referensi->delete();
        return back()->with('success', 'Data referensi berhasil dihapus');
    }

    // Pendafataran agen
public function store(Request $request)
{
$agenLama = Agen::where(
    'id_agen_pp',
    $request->id_agen_pp
)->first();

if ($agenLama) {
    if ($agenLama->status == 'pending') {

        return back()->withErrors([
            'id_agen_pp' =>
            'Pendaftaran Anda masih dalam proses verifikasi admin.'
        ])->withInput();
    }
    if ($agenLama->status == 'aktif') {

        return back()->withErrors([
            'id_agen_pp' =>
            'Akun sudah terdaftar. Silakan login.'
        ])->withInput();
    }
    if ($agenLama->status == 'diblokir') {

        return back()->withErrors([
            'id_agen_pp' =>
            'Akun Anda telah diblokir. Silakan hubungi admin.'
        ])->withInput();
    }
 // jika sebelumnya agen tersebut di tolak saat proses registrasi nya maka data tersebut terhapus sehinggga agen dapat melakukan pendaftaran baru
    if ($agenLama->status == 'ditolak') {
        User::where(
            'id',
            $agenLama->user_id
        )->delete();
        $agenLama->delete();
    }
}
// Melakukan pengecekan apakah ID Agen PP tersebut terdaftar di data Partner Pulsa (tabel referensi)
    $referensi = ReferensiAgenPP::find($request->id_agen_pp);
    if (!$referensi) {
        return back()->withErrors([
            'id_agen_pp' => 'ID Agen PP tidak ditemukan di data Partner Pulsa'
        ])->withInput();
    }
// validasi input (keseuaian data yang harus diinputkan)
    $request->validate([

        'id_agen_pp' =>
            'required|unique:agen,id_agen_pp',
        'email' =>
            'required|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:users,email',
        'password' =>
            'required|min:6',
        'nik' =>
              'required|digits:16|unique:agen,nik',
        'foto_ktp' =>
            'required|image|mimes:jpg,jpeg,png',
        'foto_selfie_ktp' =>
            'required|image|mimes:jpg,jpeg,png',
        'foto_toko_fisik' =>
            'nullable|image|mimes:jpg,jpeg,png',
    ], [
// Menampilkan Pesan Eror jika data yang di masukan tidak valid sesuai yang di atas
        'id_agen_pp.required' =>
            'ID Agen wajib diisi',
        'id_agen_pp.unique' =>
            'ID Agen PP sudah digunakan',
        'email.required' =>
            'Email wajib diisi',
        'email.regex' =>
            'Periksa Email Kembali',
        'email.unique' =>
            'Email sudah digunakan',
        'password.required' =>
            'Password wajib diisi',
        'password.min' =>
            'Password minimal 6 karakter',
        'nik.required' =>
            'NIK wajib diisi',
        'nik.unique' =>
            'NIK sudah terdaftar',
        'foto_ktp.required' =>
            'Foto KTP wajib diupload',
        'foto_selfie_ktp.required' =>
            'Foto selfie KTP wajib diupload',
        'nik.digits' =>
    'NIK harus terdiri dari 16 digit angka',
    ]);
// Fungsi untuk megupload foto ktp
    $fotoKtp = null;

    if ($request->hasFile('foto_ktp')) {

        $fotoKtp = time() . '_ktp.' .
            $request->foto_ktp->extension();

        $request->foto_ktp->move(
            public_path('uploads'),
            $fotoKtp
        );
    }
// fungsi untuk mengupload selfie ktp
    $fotoSelfie = null;

    if ($request->hasFile('foto_selfie_ktp')) {

        $fotoSelfie = time() . '_selfie.' .
            $request->foto_selfie_ktp->extension();

        $request->foto_selfie_ktp->move(
            public_path('uploads'),
            $fotoSelfie
        );
    }
// fungsi untuk mengupload foto toko fisik
    $fotoToko = null;

    if ($request->hasFile('foto_toko_fisik')) {

        $fotoToko = time() . '_toko.' .
            $request->foto_toko_fisik->extension();

        $request->foto_toko_fisik->move(
            public_path('uploads'),
            $fotoToko
        );
    }
// menyimpan agen yang baru mendaftar dan statusnya pending
    $user = User::create([
        'username' => $referensi->username,
        'name' => $referensi->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'agen',
        'status' => 'pending'
    ]);

// menyimpan seluruh data agen ke tabel agen
    Agen::create([
        'user_id' => $user->id,
        'id_agen_pp' => $referensi->id_agen_pp,
        'username' => $referensi->username,
        'password' => Hash::make($request->password),
        // Disimpan sementara, hanya untuk dikirim sekali lewat
        // email saat akun disetujui admin, lalu di-null-kan lagi.
        'password_plain' => $request->password,
        'nama_usaha' => $request->nama_usaha,
        'no_hp' => $referensi->no_hp,
        'alamat' => $referensi->alamat,
        'nik' => $request->nik,
        'foto_ktp' => $fotoKtp,
        'foto_selfie_ktp' => $fotoSelfie,
        'foto_toko_fisik' => $fotoToko,
        'limit_pinjaman' => 150000,
        'status_kredit' => 'baru',
        'status' => 'pending'
    ]);
 $admin = User::where('role', 'admin')->first();

 // Mengirimkan notifikasi ke admin bahwa ada agen baru mendaftar dan perlu validasi
if ($admin) {
    Notifikasi::create([
        'id_user' => $admin->id,
        'judul' => 'Registrasi Agen Baru',
        'pesan' =>
            $referensi->username .
            ' mendaftar sebagai agen baru dan menunggu validasi admin.',
        'tipe' => 'pengajuan',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);
}
    return back()->with(
        'success',
        'Registrasi berhasil, menunggu validasi admin'
    );
}
// menampikan seluruh data agen yang terdaftar
    public function index()
    {
        $agen = Agen::all();
        $agen = Agen::latest()->get();
        return view('agen.index', compact('agen'));
    }

    // fungsi untuk menyetujui agen sehingga nanti statusnya aktif serta pengiriman email kepada agen berupa username dan passwordnya
   public function setujui($id)
{
    $agen = Agen::findOrFail($id);
    $userAgen = User::find($agen->user_id);
    $admin = User::where('role', 'admin')->first();
    $agen->status = 'aktif';
    $agen->approved_at = now();
    $agen->save();
    User::where(
        'id',
        $agen->user_id
    )->update([
        'status' => 'aktif'
    ]);

    $user = User::find(
        $agen->user_id
    );

    Mail::to($user->email)
        ->send(
            new AgenDisetujuiMail($agen, $user)
        );

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Akun Disetujui',
        'pesan' =>
            'Selamat! Akun agen Anda telah disetujui dan dapat digunakan.',
        'tipe' => 'persetujuan',
        'media' => 'web',
        'tanggal' => now(),
        'status_baca' => 'belum'
    ]);

    // Password asli sudah terkirim lewat email, jadi hapus lagi
    // dari database supaya tidak tersimpan dalam bentuk plain text.
    $agen->password_plain = null;
    $agen->save();
    return redirect('/agen')
        ->with(
            'success',
            'Agen berhasil disetujui'
        );
}

// Menolak registrasi agen yang baru saja mendaftar
    public function tolak($id)
    {
        $agen = Agen::findOrFail($id);
        $agen->status = 'ditolak';
        $agen->save();
        User::where(
            'id',
            $agen->user_id
        )->update([
            'status' => 'ditolak'
        ]);
        return redirect('/agen');
    }
// Memblokir agen yang awal statusnya aktif
    public function blokir($id)
{
    $agen = Agen::findOrFail($id);

    $agen->status = 'diblokir';
    $agen->save();

    User::where('id', $agen->user_id)
        ->update([
            'status' => 'diblokir'
        ]);

    return redirect('/agen');
}
// Mengaktifkan kembali agen yang status awalnya di blokir
public function aktifkan($id)
{
    $agen = Agen::findOrFail($id);
    $agen->status = 'aktif';
    $agen->save();
    User::where('id', $agen->user_id)
        ->update([
            'status' => 'aktif'
        ]);
    return redirect('/agen');
}

//  Pemeriksaan Keterlambatan Pembayaran (pengecekan apakah ada yang terlambat dalam pembayaran dan nantinya sistem akan mengubah status kreditnya)
private function cekKeterlambatan()
{
    $cicilanTerlambat = Cicilan::where(
        'status',
        'belum'
    )
    ->whereDate(
        'tanggal_jatuh_tempo',
        '<',
        now()->toDateString()
    )
    ->get();
    foreach ($cicilanTerlambat as $cicilan) {

        // UPDATE STATUS CICILAN
        $cicilan->status = 'terlambat';
        $cicilan->save();    

        // CARI DATA HUTANG
        $hutang = Hutang::find(
            $cicilan->id_hutang
        );

        if (!$hutang) {
            continue;
        }
        // UPDATE STATUS HUTANG
        $hutang->status = 'terlambat';
        $hutang->save();

        // UPDATE STATUS KREDIT AGEN
        $agen = Agen::find(
            $hutang->id_agen
        );
        if ($agen) {
            $agen->status_kredit =
                'bermasalah';
            $agen->limit_pinjaman =
                150000;
            $agen->riwayat_tepat_waktu =
                0;
            $agen->save();
        }
    }
}

// Menampilkan dashboard agen seperti card, riwayat, aktifitas dan lainnya
public function dashboard()
{
    $idAgen = session('id_agen');
    $this->cekKeterlambatan();
    $agen = Agen::find($idAgen);

    $hutangAktif = Hutang::where('id_agen', $idAgen)
        ->whereIn('status', [
            'pending',
            'disetujui',
            'berjalan',
            'terlambat'
        ])
        ->latest()
        ->first();

    $jumlahHutangAktif = $hutangAktif
        ? $hutangAktif->sisa_hutang
        : 0;

    $tanggalJatuhTempo = null;
    $sisaHari = null;

    if ($hutangAktif) {
        $tanggalJatuhTempo =
            $hutangAktif->tanggal_jatuh_tempo;

       $sisaHari = (int) now()->startOfDay()->diffInDays(
    Carbon::parse($tanggalJatuhTempo)->startOfDay(),
    false
    );
    }

    $aktivitas = Notifikasi::where(
        'id_user',
        session('id_user')
    )
    ->latest()
    ->take(5)
    ->get();

        $totalPengajuanHutang = Hutang::where(
        'id_agen',
        $idAgen
    )->count();

    $totalPengajuanPembayaran = Pembayaran::whereHas(
        'hutang',
        function ($q) use ($idAgen) {
            $q->where('id_agen', $idAgen);
        }
    )->count();

    $totalUangDipinjam = Hutang::where(
        'id_agen',
        $idAgen
    )
    ->whereIn('status', [
        'disetujui',
        'berjalan',
        'lunas',
        'terlambat'
    ])
    ->sum('jumlah_hutang');

        return view(
            'dashboard.agen',
        compact(
        'agen',
        'hutangAktif',
        'jumlahHutangAktif',
        'tanggalJatuhTempo',
        'sisaHari',
        'aktivitas',
        'totalPengajuanHutang',
        'totalPengajuanPembayaran',
        'totalUangDipinjam'

    )
        );
    }
// Menampilkan dashboard admin seperti card, riwayat, aktifitas dan lainnya
public function dashboardAdmin()
{
    $agenPending = Agen::where(
        'status',
        'pending'
    )->count();

    $agenAktif = Agen::where(
        'status',
        'aktif'
    )->count();

    $pencairanPending = Hutang::where(
        'status',
        'disetujui'
    )->count();

    $pembayaranPending = Pembayaran::where(
        'status',
        'pending'
    )->count();

    // AKTIVITAS VALIDASI AGEN

    $aktivitasAgen = Agen::where(
        'status',
        'aktif'
    )
    ->latest()
    ->take(5)
    ->get();

    // AKTIVITAS PENCAIRAN

    $aktivitasPencairan = Hutang::with('agen')
        ->whereNotNull('tanggal_pencairan')
        ->latest()
        ->take(5)
        ->get();

    // AKTIVITAS PEMBAYARAN

    $aktivitasPembayaran = Pembayaran::with('hutang.agen')
        ->where('status', 'disetujui')
        ->latest()
        ->take(5)
        ->get();


    // GABUNG SEMUA AKTIVITAS

    $aktivitas = collect();
    foreach ($aktivitasAgen as $a) {
        $aktivitas->push([
            'icon' => '✅',
            'pesan' =>
                'Anda berhasil memvalidasi akun agen ' .
                $a->username,
            'tanggal' =>
                $a->updated_at
        ]);
    }

    foreach ($aktivitasPencairan as $h) {
        $aktivitas->push([
            'icon' => '💰',
            'pesan' =>
                'Anda berhasil mencairkan dana Rp' .
                number_format(
                    $h->jumlah_hutang,
                    0,
                    ',',
                    '.'
                ) .
                ' kepada ' .
                $h->agen->username,

            'tanggal' =>
                $h->updated_at
        ]);
    }
    foreach ($aktivitasPembayaran as $p) {
        $aktivitas->push([
            'icon' => '💳',
            'pesan' =>
                'Validasi pembayaran sejumlah Rp' .
                number_format(
                    $p->jumlah_bayar,
                    0,
                    ',',
                    '.'
                ) .
                ' berhasil dari ' .
                $p->hutang->agen->username,
            'tanggal' =>
                $p->updated_at
        ]);
    }

    $aktivitas = $aktivitas
        ->sortByDesc('tanggal')
        ->take(8);

    // TAMBAH INI
    $pengajuanHutang = Hutang::with('agen')
        ->latest()
        ->take(5)
        ->get();  

    return view(
        'dashboard.admin',
        compact(
            'agenPending',
            'agenAktif',
            'pencairanPending',
            'pembayaranPending',
            'aktivitas',
            'pengajuanHutang'
        )
    );
}

// Notifikasi yang tampil pada admin

public function notifikasiAdmin()
{
    //Mengecek hutang yang sedang berjalan atau terlambat
    $hutangTempo = Hutang::with('agen')
        ->whereIn('status', [
            'berjalan',
            'terlambat'
        ])
        ->get();

    foreach ($hutangTempo as $h) {

        $selisih = now()->startOfDay()->diffInDays(
            \Carbon\Carbon::parse(
                $h->tanggal_jatuh_tempo
            )->startOfDay(),
            false
        );
       // Menghitung selisih hari dengan tanggal jatuh tempo (H-1, H+1, H+3, H+7, H+14, H+30)

        // H-1
        if ($selisih == 1) {
            Notifikasi::firstOrCreate(
                [
                    'judul' => 'Jatuh Tempo',
                    'pesan' =>
                        'Hutang ' .
                        $h->agen->username .
                        ' akan jatuh tempo besok'
                ],
                [
                    'id_user' => User::where('role','admin')->first()->id,
                    'tipe' => 'keterlambatan',
                    'media' => 'web',
                    'tanggal' => now(),
                    'status_baca' => 'belum'
                ]);}

        // H+1, H+3, H+7, H+14, H+30
        if (
            $selisih == -1 ||
            $selisih == -3 ||
            $selisih == -7 ||
            $selisih == -14 ||
            $selisih == -30
        ) {
            Notifikasi::firstOrCreate(
                [
                    'judul' => 'Keterlambatan',
                    'pesan' =>
                        'Hutang ' .
                        $h->agen->username .
                        ' terlambat ' .
                        abs($selisih) .
                        ' hari'
                ],
                [
                    'id_user' => User::where('role','admin')->first()->id,
                    'tipe' => 'keterlambatan',
                    'media' => 'web',
                    'tanggal' => now(),
                    'status_baca' => 'belum'
                ]
            );
        }}
   // menampilkan notifikasi di role admin     
    $admin = User::where('role','admin')->first();
        $notifikasi = Notifikasi::where(
            'id_user',
            $admin->id
)
    ->latest()
    ->limit(20)
    ->get();

return view(
    'notifikasi.index',
    compact('notifikasi')
);
            }

//Menghitung Jumlah Notifikasi Belum Dibaca (yang nantinya menampilkan badge di sidebae admin)
public function jumlahNotifikasi()
{
    return response()->json([
        'jumlah' =>
        Notifikasi::where(
    'id_user',
    session('id_user')
        )
        ->where(
            'status_baca',
            'belum'
        )->count()

    ]);
}

// mengubah status notifikasi menjadi dibaca (khususnya di role admin)
public function bacaNotif()
{
    $jumlah = Notifikasi::where('id_user', session('id_user'))
        ->where('status_baca', 'belum')
        ->update([
            'status_baca' => 'dibaca'
        ]);
    return response()->json([
        'success' => true,
        'updated' => $jumlah,
        'id_user' => session('id_user')
    ]);
}

// Menolak pendaftaran agen baru yang disertai alasan dan nantinya akan dikirimkan email
public function simpanTolak(Request $request, $id)
{
    $request->validate([
        'alasan_penolakan' => 'required'
    ]);
    $agen = Agen::findOrFail($id);
    $agen->status = 'ditolak';
    $agen->alasan_penolakan =
        $request->alasan_penolakan;
    $agen->save();
    User::where(
        'id',
        $agen->user_id
    )->update([
        'status' => 'ditolak'
    ]);
    // KIRIM EMAIL
    $user = User::find($agen->user_id);
    Notifikasi::create([
    'id_user' => $user->id,
    'judul' => 'Registrasi Ditolak',
    'pesan' =>
        'Registrasi Anda ditolak oleh Admin. Alasan: ' .
        $request->alasan_penolakan,
    'tipe' => 'persetujuan',
    'media' => 'web',
    'tanggal' => now(),
    'status_baca' => 'belum'
]);
    Mail::to($user->email)
        ->send(
            new AgenDitolakMail($agen)
        );
    return redirect('/agen')
        ->with(
            'success',
            'Agen berhasil ditolak'
        );
}

//menampilkan profil agen berdasarkan id agen yang sedang login
public function profil()
{
    $agen = Agen::find(session('id_agen'));

    return view('agen.profil', compact('agen'));
}

// agen dapat mengupdate profil agen sendiri
public function updateProfil(Request $request)
{
    $agen = Agen::findOrFail(session('id_agen'));
    $request->validate([
        'email' => 'required|email|unique:users,email,' . $agen->user_id,
        'no_hp' => 'required|digits_between:10,12',
        'alamat' => 'required',
        'nama_usaha' => 'nullable',
        'foto_toko_fisik' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    $fotoToko = $agen->foto_toko_fisik;
    if ($request->hasFile('foto_toko_fisik')) {
        $fotoToko = time() . '_toko.' . $request->foto_toko_fisik->extension();
        $request->foto_toko_fisik->move(
            public_path('uploads'),
            $fotoToko
        );
    }
    $agen->update([
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'nama_usaha' => $request->nama_usaha,
        'foto_toko_fisik' => $fotoToko,
    ]);
    User::where('id', $agen->user_id)
        ->update([
            'email' => $request->email,
        ]);

    return back()->with(
        'success',
        'Profil berhasil diperbarui.'
    );
}

//agen mengubah password nya sendiri
public function updatePassword(Request $request)
{
    $request->validate([
    'password_lama' => 'required',
    'password_baru' => 'required|min:6',
    'password_baru_confirmation' => 'required|same:password_baru'
], [
    'password_lama.required' => 'Password lama wajib diisi.',
    'password_baru.required' => 'Password baru wajib diisi.',
    'password_baru.min' => 'Password baru minimal 6 karakter.',
    'password_baru_confirmation.required' => 'Konfirmasi password wajib diisi.',
    'password_baru_confirmation.same' => 'Konfirmasi password tidak cocok.',
]);

    $agen = Agen::findOrFail(session('id_agen'));
    $user = User::find($agen->user_id);

    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->with('error', 'Password lama tidak sesuai.');
    }
    $user->password = Hash::make($request->password_baru);
    $user->save();
    $agen->password = Hash::make($request->password_baru);
    $agen->save();
    return back()->with('success', 'Password berhasil diubah.');
}

// admin mengubah password nya sendiri
public function updatePasswordAdmin(Request $request)
{
    $request->validate([
    'password_lama' => 'required',
    'password_baru' => 'required|min:6',
    'password_baru_confirmation' => 'required|same:password_baru'
], [
    'password_lama.required' => 'Password lama wajib diisi.',
    'password_baru.required' => 'Password baru wajib diisi.',
    'password_baru_confirmation.required' => 'Konfirmasi password wajib diisi.',
    'password_baru_confirmation.same' => 'Konfirmasi password tidak cocok.',
]);
    $user = User::find(session('id_user'));
    $cocok = false;
    if ($request->password_lama == $user->password) {
        $cocok = true;
    } elseif (
        str_starts_with($user->password, '$2y$')
        && Hash::check($request->password_lama, $user->password)
    ) {
        $cocok = true;
    }
    if (!$cocok) {
        return back()->with('error', 'Password lama tidak sesuai.');
    }
    $user->password = Hash::make($request->password_baru);
    $user->save();
    return back()->with('success', 'Password berhasil diubah.');
}
}