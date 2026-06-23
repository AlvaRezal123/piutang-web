<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Agen - SIMPAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-purple-100 via-purple-50 to-white min-h-screen flex items-center justify-center p-4">

<div class="max-w-3xl w-full">

    <div class="bg-white rounded-3xl shadow-2xl border border-purple-100 overflow-hidden">

        <div class="p-8">

            <!-- Logo -->
            <div class="flex justify-center">

                <div class="bg-purple-50 p-4 rounded-3xl border border-purple-100">

                    <img
                        src="{{ asset('images/logo-partnerpulsa.png') }}"
                        alt="Partner Pulsa"
                        class="w-20 h-20 object-contain">

                </div>

            </div>
         
            <!-- Header -->
            <div class="text-center mt-4">

                <h1 class="text-4xl font-black text-[#5628C7]">
                    Registrasi Agen
                </h1>

                <p class="text-gray-500 mt-2">
                    Lengkapi data untuk menjadi mitra Partner Pulsa
                </p>

            </div>

            <!-- Error -->
            @if ($errors->any())

            <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4">

                <ul class="text-red-600 text-sm">

                    @foreach ($errors->all() as $error)

                        <li>• {{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            @endif

            <!-- Success -->
            @if(session('success'))

            <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-600">

                {{ session('success') }}

            </div>

            @endif

            <!-- Progress -->
            <div class="mt-8">

                <div class="flex items-center justify-center">

                    <div
                        id="circle1"
                        class="w-10 h-10 rounded-full bg-[#5628C7] text-white flex items-center justify-center font-bold">

                        1

                    </div>

                    <div class="w-20 h-1 bg-purple-200"></div>

                    <div
                        id="circle2"
                        class="w-10 h-10 rounded-full bg-purple-200 text-gray-500 flex items-center justify-center font-bold">

                        2

                    </div>

                    <div class="w-20 h-1 bg-purple-200"></div>

                    <div
                        id="circle3"
                        class="w-10 h-10 rounded-full bg-purple-200 text-gray-500 flex items-center justify-center font-bold">

                        3

                    </div>

                </div>

                <p
                    id="stepText"
                    class="text-center mt-4 text-sm text-gray-500">

                    Langkah 1 dari 3

                </p>

            </div>

            <!-- Form -->
            <form
                action="{{ route('agen.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8">

                @csrf

                <!-- STEP 1 -->
                <div id="step1">

                  <div class="mb-8">

<div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-3xl p-6">

    <div class="flex items-center gap-4">

        <div class="w-14 h-14 rounded-2xl bg-[#5628C7] flex items-center justify-center text-white text-2xl shadow-lg">
    🛡️

        </div>

        <div>

            <span class="inline-block text-xs font-bold tracking-widest text-purple-600 uppercase">

                Langkah 1

            </span>

            <h2 class="text-2xl font-black text-gray-800 mt-1">

                Buat Akun Anda

            </h2>

            <p class="text-gray-500 text-sm mt-1">

                Lengkapi data akun yang akan digunakan untuk login ke sistem SIMPAN Partner Pulsa.

            </p>

        </div>

    </div>

</div>


</div>

<div class="space-y-4">

    <!-- ID Agen -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            ID Agen PP
        </span>

        <input
            type="text"
            name="id_agen_pp"
            value="{{ old('id_agen_pp') }}"
            placeholder="Masukkan ID Agen PP"
            class="w-full mt-1 outline-none bg-transparent">

    </div>

    <!-- Username -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Username
        </span>

        <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            placeholder="Masukkan username"
            autocomplete="off"
            class="w-full mt-1 outline-none bg-transparent">

    </div>

    <!-- Email -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Email
        </span>

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="contoh@gmail.com"
            autocomplete="off"
            class="w-full mt-1 outline-none bg-transparent">

    </div>

    <!-- Password -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Password
        </span>

        <input
            type="password"
            name="password"
            placeholder="Minimal 6 karakter"
            autocomplete="new-password"
            class="w-full mt-1 outline-none bg-transparent">

    </div>

    <!-- No HP -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Nomor HP
        </span>

        <input
            type="text"
            name="no_hp"
            value="{{ old('no_hp') }}"
            placeholder="08xxxxxxxxxx"
            class="w-full mt-1 outline-none bg-transparent">

    </div>
           <div class="flex justify-between items-center mt-6">

    <a
        href="/login"
        class="text-[#5628C7] font-semibold hover:underline">

        ← Kembali ke Login

    </a>

    <button
        type="button"
        onclick="nextStep(2)"
        class="bg-[#5628C7] hover:bg-[#4c20bb] text-white px-6 py-3 rounded-xl font-semibold">

        Selanjutnya →

    </button>

</div>

</div>

</div>

           

              <!-- STEP 2 -->
<div id="step2" class="hidden">
    <div class="pt-4">

    <!-- Header -->
    <div class="mb-8">

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-3xl p-6">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl bg-[#5628C7] flex items-center justify-center text-white text-2xl shadow-lg">

                    🪪

                </div>

                <div>

                    <span class="inline-block text-xs font-bold tracking-widest text-purple-600 uppercase">

                        Langkah 2

                    </span>

                    <h2 class="text-2xl font-black text-gray-800 mt-1">

                        Verifikasi Identitas

                    </h2>

                    <p class="text-gray-500 text-sm mt-1">

                        Pastikan data identitas sesuai dengan KTP yang masih berlaku untuk mempercepat proses verifikasi.

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Form -->
    <div class="space-y-4">

        <!-- NIK -->
        <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

            <span class="text-xs font-semibold text-purple-500">
                Nomor Induk Kependudukan (NIK)
            </span>

            <input
                type="text"
                name="nik"
                value="{{ old('nik') }}"
                placeholder="Masukkan 16 digit NIK"
                class="w-full mt-1 outline-none bg-transparent">

        </div>

        <!-- Upload KTP -->
        <div class="bg-white border-2 border-purple-200 rounded-2xl p-4 shadow-sm">

            <span class="text-xs font-semibold text-purple-500 block mb-3">
                Foto KTP
            </span>

            <input
                type="file"
                name="foto_ktp"
                class="w-full text-sm">

            <p class="text-xs text-gray-400 mt-2">
                Upload foto KTP yang jelas dan tidak buram.
            </p>

        </div>

        <!-- Upload Selfie -->
        <div class="bg-white border-2 border-purple-200 rounded-2xl p-4 shadow-sm">

            <span class="text-xs font-semibold text-purple-500 block mb-3">
                Foto Selfie Dengan KTP
            </span>

            <input
                type="file"
                name="foto_selfie_ktp"
                class="w-full text-sm">

            <p class="text-xs text-gray-400 mt-2">
                Pastikan wajah dan KTP terlihat jelas.
            </p>

        </div>

        <!-- Info -->
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4">

            <p class="text-sm text-purple-700">

                ℹ️ Langkah selanjutnya Anda akan diminta melengkapi informasi usaha dan foto toko fisik.

            </p>

        </div>

    </div>

    <!-- Button -->
    <div class="flex justify-between mt-8">

        <button
            type="button"
            onclick="prevStep(1)"
            class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl font-semibold transition">

            ← Kembali

        </button>

        <button
            type="button"
            onclick="nextStep(3)"
            class="bg-[#5628C7] hover:bg-[#4c20bb] text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition">

            Selanjutnya →

        </button>

    </div>
     </div>
       </div>

                <!-- STEP 3 -->
              <div id="step3" class="hidden">

                   <div class="mb-8">

    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-3xl p-6">

        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-[#5628C7] flex items-center justify-center text-white text-2xl shadow-lg">

                🏪

            </div>

            <div>

                <span class="inline-block text-xs font-bold tracking-widest text-purple-600 uppercase">

                    Langkah 3

                </span>

                <h2 class="text-2xl font-black text-gray-800 mt-1">

                    Informasi Usaha

                </h2>

                <p class="text-gray-500 text-sm mt-1">

                    Lengkapi data usaha dan foto toko fisik untuk proses validasi agen.

                </p>

            </div>

        </div>

    </div>

</div>

     <div class="space-y-4">

    <!-- Nama Usaha -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Nama Usaha
        </span>

        <input
            type="text"
            name="nama_usaha"
            value="{{ old('nama_usaha') }}"
            placeholder="Contoh: Alva Cell"
            class="w-full mt-1 outline-none bg-transparent">

    </div>

    <!-- Alamat -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            Alamat Usaha
        </span>

        <textarea
            name="alamat"
            rows="4"
            placeholder="Masukkan alamat lengkap usaha"
            class="w-full mt-1 outline-none bg-transparent resize-none">{{ old('alamat') }}</textarea>

    </div>

    <!-- Foto Toko -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-4 shadow-sm">

        <span class="text-xs font-semibold text-purple-500 block mb-3">
            Foto Toko Fisik
        </span>

        <input
            type="file"
            name="foto_toko_fisik"
            class="w-full text-sm">

        <p class="text-xs text-gray-400 mt-2">
            Upload foto toko atau tempat usaha Anda.
        </p>

    </div>

    <!-- Info -->
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4">

        <p class="text-sm text-green-700">

            ✅ Pastikan seluruh data sudah benar sebelum mengirim registrasi.

        </p>

    </div>

</div>

                    <div class="flex justify-between mt-6">

                        <button
                            type="button"
                            onclick="prevStep(2)"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl font-semibold">

                            ← Kembali

                        </button>

                        <button
                            type="submit"
                            class="bg-[#5628C7] hover:bg-[#4c20bb] text-white px-6 py-3 rounded-xl font-semibold shadow-lg">

                            Daftar Sekarang 🚀

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

function nextStep(step)
{
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.add('hidden');

    document.getElementById('step' + step).classList.remove('hidden');

    updateProgress(step);
}

function prevStep(step)
{
    nextStep(step);
}

function updateProgress(step)
{
    const circles = [
        document.getElementById('circle1'),
        document.getElementById('circle2'),
        document.getElementById('circle3')
    ];

    circles.forEach(circle => {

        circle.classList.remove('bg-[#5628C7]');
        circle.classList.remove('text-white');

        circle.classList.add('bg-purple-200');
        circle.classList.add('text-gray-500');
    });

    for(let i = 1; i <= step; i++)
    {
        const circle = document.getElementById('circle' + i);

        circle.classList.remove('bg-purple-200');
        circle.classList.remove('text-gray-500');

        circle.classList.add('bg-[#5628C7]');
        circle.classList.add('text-white');
    }

    document.getElementById('stepText').innerHTML =
        'Langkah ' + step + ' dari 3';
}

</script>

</body>
</html>