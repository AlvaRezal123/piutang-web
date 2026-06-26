<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // =========================
    // HALAMAN LOGIN
    // =========================

    public function index()
    {
        return view('login');
    }

    // =========================
    // PROSES LOGIN
    // =========================

    public function prosesLogin(Request $request)
    {
        // =========================
        // VALIDASI
        // =========================

        $request->validate([

            'email' => 'required|email',

            'password' => 'required'

        ], [

            'email.required' =>
                'Email wajib diisi',

            'email.email' =>
                'Format email tidak valid',

            'password.required' =>
                'Password wajib diisi'
        ]);

        // =========================
        // CARI USER BERDASARKAN EMAIL
        // =========================

        $user = User::where(
            'email',
            $request->email
        )->first();

// =========================
// CEK EMAIL & PASSWORD
// =========================

if (!$user) {

    return back()->with(
        'error',
        'Email atau password salah'
    );
}

// akun lama (password masih plain text)
if ($request->password == $user->password) {

    // akun lama (plain text)

}
elseif (
    str_starts_with($user->password, '$2y$')
    &&
    Hash::check(
        $request->password,
        $user->password
    )
) {

    // akun baru (bcrypt)

}
else {

    return back()->with(
        'error',
        'Email atau password salah'
    );
}



       // =========================
// CEK STATUS AKUN
// =========================

if ($user->status == 'pending') {

    return back()->with(
        'error',
        'Akun Anda sedang dalam proses verifikasi admin.'
    );
}

if ($user->status == 'ditolak') {

    return back()->with(
        'error',
        'Pendaftaran akun Anda ditolak. Silakan lakukan registrasi kembali dengan data yang sesuai.'
    );
}

if ($user->status == 'diblokir') {

    return back()->with(
        'error',
        'Akun Anda telah diblokir. Silakan hubungi admin Partner Pulsa.'
    );
}

        // =========================
        // SIMPAN SESSION
        // =========================

        session([

            'id_user' => $user->id,

            'role' => $user->role
        ]);
      

        // =========================
        // REDIRECT ADMIN
        // =========================

        if ($user->role == 'admin') {

            return redirect('/dashboard-admin');
        }
    

        // =========================
        // REDIRECT OWNER
        // =========================

        if ($user->role == 'owner') {

            return redirect('/dashboard-owner');
        }

        // =========================
        // REDIRECT AGEN
        // =========================

        if ($user->role == 'agen') {

            // cari data agen
            $agen = Agen::where(
                'user_id',
                $user->id
            )->first();

            // simpan session agen
            session([
                'id_agen' => $agen->id
            ]);

            return redirect('/dashboard-agen');
        }

        // =========================
        // DEFAULT
        // =========================

        return back()->with(
            'error',
            'Role tidak ditemukan'
        );
    }

    // =========================
    // LOGOUT
    // =========================

    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }

    public function formLupaPassword()
{
    return view('lupa-password');
}

public function kirimPasswordBaru(Request $request)
{
    $request->validate([

        'email' => 'required|email'

    ]);

    $user = User::where(
        'email',
        $request->email
    )->first();

    if (!$user) {

        return back()->with(
            'error',
            'Email tidak ditemukan'
        );
    }

    // generate password baru
    $passwordBaru =
        Str::random(8);

    // update password
    $user->password =
        Hash::make(
            $passwordBaru
        );

    $user->save();

    // kirim email
    Mail::to($user->email)
        ->send(
            new ResetPasswordMail(
                $passwordBaru
            )
        );

    return back()->with(
        'success',
        'Password baru berhasil dikirim ke email Anda'
    );
}
}