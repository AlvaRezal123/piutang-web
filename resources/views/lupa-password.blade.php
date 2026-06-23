<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SIMPAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-purple-100 via-purple-50 to-white min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full">

    <div class="bg-[#f4f0ff] rounded-3xl shadow-2xl px-8 py-8 border border-[#e5dcff]">

        <div class="text-center mb-6">

            <img
                src="{{ asset('images/logo-partnerpulsa.png') }}"
                class="w-20 h-20 mx-auto mb-4">

            <h1 class="text-3xl font-black text-[#5628C7]">
                Lupa Password
            </h1>

            <p class="text-gray-500 mt-2">
                Masukkan email yang terdaftar untuk mendapatkan password baru.
            </p>

        </div>

        @if(session('success'))

            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-2xl mb-4">

                {{ session('success') }}

            </div>

        @endif

        @if(session('error'))

            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-2xl mb-4">

                {{ session('error') }}

            </div>

        @endif

        <form action="/lupa-password" method="POST">

            @csrf

            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    EMAIL
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    placeholder="Masukkan email"
                    class="w-full bg-white border-2 border-purple-100 rounded-2xl px-4 py-3 shadow-sm focus:border-[#5628C7] focus:ring-4 focus:ring-purple-200 outline-none">

            </div>

            <button
                type="submit"
                class="w-full py-3 bg-gradient-to-r from-[#5628C7] to-[#6d3ef0] text-white rounded-2xl font-bold">

                Kirim Password Baru

            </button>

        </form>

        <div class="text-center mt-6">

            <a
                href="/login"
                class="text-[#5628C7] font-semibold hover:underline">

                ← Kembali ke Login

            </a>

        </div>

    </div>

</div>

</body>
</html>