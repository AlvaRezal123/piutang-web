<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Agen;
use App\Models\User;
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
    );
}

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
        );
}
    // ======================================
    // HALAMAN REGISTER
    // ======================================

    public function create()
    {
        return view('agen.create');
    }

    // ======================================
    // PROSES REGISTER
    // ======================================

public function store(Request $request)
{
// =========================
// CEK DATA LAMA BERDASARKAN
// ID AGEN PP
// =========================

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

    // =========================
    // JIKA DITOLAK
    // HAPUS DATA LAMA
    // =========================

    if ($agenLama->status == 'ditolak') {

        User::where(
            'id',
            $agenLama->user_id
        )->delete();

        $agenLama->delete();
    }
}
    // =========================
    // VALIDASI INPUT
    // =========================

    $request->validate([

        'id_agen_pp' =>
            'required|unique:agen,id_agen_pp',

       'username' =>
             'required|regex:/^[A-Za-z\s]+$/|unique:users,username',

        'email' =>
            'required|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:users,email',

        'password' =>
            'required|min:6',

        'no_hp' =>
        'required|digits_between:10,12',

        'alamat' =>
            'required',

        'nik' =>
              'required|digits:16|unique:agen,nik',


        'foto_ktp' =>
            'required|image|mimes:jpg,jpeg,png',

        'foto_selfie_ktp' =>
            'required|image|mimes:jpg,jpeg,png',

        // opsional
        'foto_toko_fisik' =>
            'nullable|image|mimes:jpg,jpeg,png',

    ], [

        // =========================
        // PESAN ERROR
        // =========================

        'id_agen_pp.required' =>
            'ID Agen wajib diisi',

        'id_agen_pp.unique' =>
            'ID Agen PP sudah digunakan',

        'username.required' =>
            'Username wajib diisi',

        'username.unique' =>
            'Username sudah digunakan',

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

        'no_hp.required' =>
            'No HP wajib diisi',

        'alamat.required' =>
            'Alamat wajib diisi',

        'nik.required' =>
            'NIK wajib diisi',

        'nik.unique' =>
            'NIK sudah terdaftar',

        'foto_ktp.required' =>
            'Foto KTP wajib diupload',

        'foto_selfie_ktp.required' =>
            'Foto selfie KTP wajib diupload',

        'username.regex' =>
    'Username hanya boleh berisi huruf',

        'no_hp.digits_between' =>
    'Nomor HP harus 10 sampai 12 digit angka',

        'nik.digits' =>
    'NIK harus terdiri dari 16 digit angka',
    ]);

    // =========================
    // UPLOAD FOTO KTP
    // =========================

    $fotoKtp = null;

    if ($request->hasFile('foto_ktp')) {

        $fotoKtp = time() . '_ktp.' .
            $request->foto_ktp->extension();

        $request->foto_ktp->move(
            public_path('uploads'),
            $fotoKtp
        );
    }

    // =========================
    // UPLOAD SELFIE KTP
    // =========================

    $fotoSelfie = null;

    if ($request->hasFile('foto_selfie_ktp')) {

        $fotoSelfie = time() . '_selfie.' .
            $request->foto_selfie_ktp->extension();

        $request->foto_selfie_ktp->move(
            public_path('uploads'),
            $fotoSelfie
        );
    }

    // =========================
    // UPLOAD FOTO TOKO
    // =========================

    $fotoToko = null;

    if ($request->hasFile('foto_toko_fisik')) {

        $fotoToko = time() . '_toko.' .
            $request->foto_toko_fisik->extension();

        $request->foto_toko_fisik->move(
            public_path('uploads'),
            $fotoToko
        );
    }

    // =========================
    // SIMPAN USERS
    // =========================

    $user = User::create([

        'username' => $request->username,

        'name' => $request->username,

        'email' => $request->email,

        'password' => Hash::make($request->password),

        'role' => 'agen',

        'status' => 'pending'
    ]);

    // =========================
    // SIMPAN AGEN
    // =========================

    Agen::create([

        'user_id' => $user->id,

        'id_agen_pp' => $request->id_agen_pp,

        'username' => $request->username,

        'password' => Hash::make($request->password),

        'nama_usaha' => $request->nama_usaha,

        'no_hp' => $request->no_hp,

        'alamat' => $request->alamat,

        'nik' => $request->nik,

        'foto_ktp' => $fotoKtp,

        'foto_selfie_ktp' => $fotoSelfie,

        'foto_toko_fisik' => $fotoToko,

        'limit_pinjaman' => 150000,

        'status_kredit' => 'baru',

        'status' => 'pending'
    ]);
 $admin = User::where('role', 'admin')->first();

if ($admin) {

    Notifikasi::create([

        'id_user' => $admin->id,

        'judul' => 'Registrasi Agen Baru',

        'pesan' =>
            $request->username .
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

    public function index()
    {
        $agen = Agen::all();
        $agen = Agen::latest()->get();

        return view('agen.index', compact('agen'));
    }

    // ======================================
    // SETUJUI AGEN
    // ======================================

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
            new AgenDisetujuiMail($agen)
        );
    
    return redirect('/agen')
        ->with(
            'success',
            'Agen berhasil disetujui'
        );
}
    // ======================================
    // TOLAK AGEN
    // ======================================

    public function tolak($id)
    {
        // cari data agen
        $agen = Agen::findOrFail($id);

        // update status agen
        $agen->status = 'ditolak';

        $agen->save();

        // update status users
        User::where(
            'id',
            $agen->user_id
        )->update([
            'status' => 'ditolak'
        ]);

        return redirect('/agen');
    }
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

// ======================================
// AKTIFKAN KEMBALI AGEN
// ======================================

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
private function cekKeterlambatan()
{
    $cicilanTerlambat = Cicilan::where(
        'status',
        'belum'
    )
    ->whereDate(
        'tanggal_jatuh_tempo',
        '<',
        now()
    )
    ->get();

    foreach ($cicilanTerlambat as $cicilan) {

        $hutang = Hutang::find(
            $cicilan->id_hutang
        );

        if (!$hutang) {
            continue;
        }

        $hutang->status = 'terlambat';
        $hutang->save();

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

    // ==================================
    // AKTIVITAS VALIDASI AGEN
    // ==================================

    $aktivitasAgen = Agen::where(
        'status',
        'aktif'
    )
    ->latest()
    ->take(5)
    ->get();

    // ==================================
    // AKTIVITAS PENCAIRAN
    // ==================================

    $aktivitasPencairan = Hutang::with('agen')
        ->whereNotNull('tanggal_pencairan')
        ->latest()
        ->take(5)
        ->get();

    // ==================================
    // AKTIVITAS PEMBAYARAN
    // ==================================

    $aktivitasPembayaran = Pembayaran::with('hutang.agen')
        ->where('status', 'disetujui')
        ->latest()
        ->take(5)
        ->get();

    // ==================================
    // GABUNG SEMUA AKTIVITAS
    // ==================================

    $aktivitas = collect();

    foreach ($aktivitasAgen as $a) {

        $aktivitas->push([

            'icon' => '✅',

            'pesan' =>
                'Admin memvalidasi akun agen ' .
                $a->username,

            'tanggal' =>
                $a->updated_at

        ]);
    }

    foreach ($aktivitasPencairan as $h) {

        $aktivitas->push([

            'icon' => '💰',

            'pesan' =>
                'Admin mencairkan dana Rp' .
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
                'Admin memvalidasi pembayaran Rp' .
                number_format(
                    $p->jumlah_bayar,
                    0,
                    ',',
                    '.'
                ) .
                ' dari ' .
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


public function notifikasiAdmin()
{
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
                ]
            );
        }

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
        }
    }
        
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
public function profil()
{
    $agen = Agen::find(session('id_agen'));

    return view('agen.profil', compact('agen'));
}

}