<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMPAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-purple-100 via-purple-50 to-white min-h-screen flex items-center justify-center p-4">

<div class="max-w-3xl w-full">

        <div class="bg-[#f4f0ff] rounded-3xl shadow-2xl px-10 py-6 border border-[#e5dcff]">

            <!-- Logo -->
            <div class="flex justify-center mb-5">

                <div class="bg-white p-4 rounded-3xl shadow-lg border border-purple-100">

                    <img
                        src="{{ asset('images/logo-partnerpulsa.png') }}"
                        alt="Partner Pulsa"
                       class="w-20 h-20 object-contain"
                    >

                </div>

            </div>

            <!-- Header -->
            <div class="text-center">

                <h1 class="text-4xl font-black tracking-tight text-[#5628C7]">
                    SIMPAN
                </h1>

                <p class="text-xs uppercase tracking-[0.3em] text-purple-400 font-semibold mt-2">
                    Sistem Informasi Piutang Agen
                </p>

            </div>

            <!-- Welcome -->
            <div class="mt-5 text-center">
                <h2 class="text-2xl font-bold text-gray-800">
                    Selamat Datang Kembali 
                </h2>

                <p class="text-gray-500 mt-3 leading-relaxed">
                    Kelola pengajuan hutang, pembayaran, dan monitoring piutang agen
                    dalam satu sistem terintegrasi.
                </p>

            </div>

            <!-- Alert -->
            @if(session('error'))

                <div class="mt-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-2xl">

                    {{ session('error') }}

                </div>

            @endif

            <!-- Form -->
           <form
    action="/proses-login"
    method="POST"
    autocomplete="off"
    class="mt-6 max-w-md mx-auto">

                @csrf

                <!-- Email -->
                <div class="mb-5">

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        EMAIL
                    </label>

                    <input
                        type="email"
                        name="email"
                        autocomplete="new-email"
                        placeholder="Masukkan email"
                        class="w-full bg-white border-2 border-purple-100 rounded-2xl px-4 py-3 shadow-sm focus:border-[#5628C7] focus:ring-4 focus:ring-purple-200 transition duration-300 outline-none">

                </div>

                <!-- Password -->
                <div class="mb-2">

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        PASSWORD
                    </label>

                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Masukkan password"
                        class="w-full bg-white border-2 border-purple-100 rounded-2xl px-4 py-3 shadow-sm focus:border-[#5628C7] focus:ring-4 focus:ring-purple-200 transition duration-300 outline-none">

                </div>

                <!-- Lupa Password -->
                <div class="flex justify-end mb-6">

            <a
                href="/lupa-password"
                class="text-sm text-[#5628C7] hover:underline font-medium">
                Lupa Password?
            </a>

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full py-3 bg-gradient-to-r from-[#5628C7] to-[#6d3ef0] hover:shadow-xl hover:scale-[1.01] text-white rounded-2xl font-bold transition duration-300">

                    SIMPAN →

                </button>

            </form>

            <!-- Register -->
            <div class="text-center mt-6">

                <span class="text-gray-500">
                    Belum memiliki akun?
                </span>

                <a
                    href="/agen/create"
                    class="font-semibold text-[#5628C7] hover:underline">

                    Daftar sebagai Agen

                </a>

            </div>

        </div>

    </div>

</body>
</html>
