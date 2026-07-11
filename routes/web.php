<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgenController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PembayaranController;


Route::get('/', function () {
    return redirect('/login');
});

// ======================================
// FITUR REFERENSI AGEN PP (BARU)
// ======================================

// Cek ID Agen PP via AJAX (dipanggil dari form register)
Route::post('/agen/cek-id-agen-pp', [AgenController::class, 'cekIdAgenPP'])
    ->name('cek.id.agen.pp');

// Import Excel data Agen PP (admin)
Route::post('/agen/import-referensi', [AgenController::class, 'importReferensiAgenPP'])
    ->name('import.referensi.agen.pp');

// Halaman lihat data referensi (admin)
Route::get('/agen/referensi', [AgenController::class, 'referensiIndex'])
    ->name('referensi.index');

// Hapus data referensi (admin)
Route::delete('/agen/referensi/{id}', [AgenController::class, 'referensiDestroy'])
    ->name('referensi.destroy');
Route::get('/profil-agen', [AgenController::class, 'profil']);
Route::get('/agen/setujui/{id}', [AgenController::class, 'setujui']);
Route::get('/agen/tolak/{id}', [AgenController::class, 'tolak']);
//Route::get('/login', [AgenController::class, 'login']);
//Route::post('/login-proses', [AgenController::class, 'loginProses']);
Route::get('/dashboard', function () {return "Login Berhasil";});

Route::get('/hutang/create', [HutangController::class, 'create']);
Route::post('/hutang/store', [HutangController::class, 'store']);
Route::get('/owner/hutang', [HutangController::class, 'index']);
Route::get('/owner/hutang/setujui/{id}', [HutangController::class, 'setujui']);
Route::post('/owner/hutang/tolak/{id}',[HutangController::class, 'simpanTolak']);
Route::get('/admin/pencairan', [HutangController::class, 'pencairan']);
Route::get('/admin/berikan-saldo/{id}', [HutangController::class, 'berikanSaldo']);
Route::get('/admin/hutang', [HutangController::class, 'semuaHutang']);
Route::get('/admin/pengajuan-hutang', [HutangController::class, 'pengajuanHutangAdmin']); // TAMBAH INI
Route::get('/admin/hutang/detail/{id}', [HutangController::class, 'detailAdmin']);
Route::get('/hutang-saya',[HutangController::class, 'hutangSaya']); // menampilkan hutang yang sedang berlangsung pada agen


Route::get('/dashboard-agen',[AgenController::class, 'dashboard']);
Route::get('/dashboard-admin',[AgenController::class, 'dashboardAdmin']);
Route::get('/dashboard-owner',[HutangController::class,'dashboardOwner']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/proses-login', [LoginController::class, 'prosesLogin']);
Route::get('/logout', [LoginController::class, 'logout']);

// NOTIFIKASI ADMIN
Route::get('/admin/notifikasi', [AgenController::class, 'notifikasiAdmin']);
Route::get('/jumlah-notifikasi', [AgenController::class, 'jumlahNotifikasi']);
Route::post('/admin/notifikasi/baca', [AgenController::class, 'bacaNotif']);

// pembayaran agen
Route::resource('agen', AgenController::class);
Route::get('/pembayaran/create/{id}',[PembayaranController::class, 'create']);
Route::post('/pembayaran/store',[PembayaranController::class, 'store']);

// validasi pembayaran admin
Route::get('/pembayaran',[PembayaranController::class, 'riwayat']);
Route::get('/admin/pembayaran',[PembayaranController::class, 'index']);
Route::get('/admin/pembayaran/setujui/{id}',[PembayaranController::class, 'setujui']);
Route::get('/admin/pembayaran/tolak/{id}',[PembayaranController::class, 'tolak']);

//penolakan pengajuan hutang
Route::get('/owner/hutang/form-tolak/{id}',[HutangController::class, 'formTolak']);
Route::post('/owner/hutang/simpan-tolak/{id}',[HutangController::class, 'simpanTolak']);

//penolakan pengajuan pembayaran
Route::get('/admin/pembayaran/form-tolak/{id}',[PembayaranController::class, 'formTolak']);
Route::post('/admin/pembayaran/simpan-tolak/{id}',[PembayaranController::class, 'simpanTolak']);

//validasi agen
Route::get('/agen', [AgenController::class, 'index']);
Route::post('/profil-agen/update', [AgenController::class, 'updateProfil']);
Route::get('/agen/setujui/{id}', [AgenController::class, 'setujui']);
Route::get('/agen/tolak/{id}', [AgenController::class, 'tolak']);
Route::get('/agen/blokir/{id}', [AgenController::class, 'blokir']);
Route::get('/agen/aktifkan/{id}', [AgenController::class, 'aktifkan']);

//histroy agen 
Route::get('/owner/hutang/detail/{id}',[HutangController::class, 'detail']);

//pencairan saldo
Route::get('/admin/form-pencairan/{id}',[HutangController::class, 'formPencairan']);
Route::post('/admin/simpan-pencairan/{id}',[HutangController::class, 'simpanPencairan']);

//detail pada menu hutang saya
Route::get('/hutang/detail/{id}',[HutangController::class, 'detailSaya']);

//edit/update
Route::post('/agen/update/{id}',[AgenController::class, 'update']);


//penolakan registrasi
Route::post('/agen/simpan-tolak/{id}', [AgenController::class, 'simpanTolak']);

//lupa password
Route::get('/lupa-password', [LoginController::class, 'formLupaPassword']);
Route::post('/lupa-password', [LoginController::class, 'kirimPasswordBaru']);

//bikin password baru
Route::post('/profil-agen/update-password',[AgenController::class, 'updatePassword']);
Route::post('/admin/update-password', [AgenController::class, 'updatePasswordAdmin']);
Route::post('/owner/update-password', [AgenController::class, 'updatePasswordAdmin']);