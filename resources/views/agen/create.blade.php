<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                id="formRegister"
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

    <!-- ID Agen PP + Tombol Cek -->
    <div class="bg-white border-2 border-purple-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-purple-500">
            ID Agen PP
        </span>

        <div class="flex gap-2 mt-1">

            <input
                type="text"
                id="id_agen_pp_input"
                name="id_agen_pp"
                value="{{ old('id_agen_pp') }}"
                placeholder="Masukkan ID Agen PP"
                class="w-full outline-none bg-transparent">

            <button
                type="button"
                id="btnCekId"
                onclick="cekIdAgenPP()"
                class="shrink-0 bg-[#5628C7] hover:bg-[#4c20bb] text-white text-sm font-semibold px-4 py-2 rounded-xl">

                Cek ID

            </button>

        </div>

    </div>

    <!-- Pesan hasil cek -->
    <div id="cekIdMessage" class="hidden text-sm rounded-2xl p-3"></div>

    <!-- Preview data dari referensi (muncul setelah ID valid) -->
    <div id="previewAgenPP" class="hidden bg-purple-50 border border-purple-200 rounded-2xl p-4 space-y-2">

        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide">
            Data Ditemukan
        </p>

        <div class="text-sm text-gray-700">
            <span class="text-gray-400">Username:</span>
            <span id="previewUsername" class="font-semibold"></span>
        </div>

        <div class="text-sm text-gray-700">
            <span class="text-gray-400">No HP:</span>
            <span id="previewNoHp" class="font-semibold"></span>
        </div>

        <div class="text-sm text-gray-700">
            <span class="text-gray-400">Alamat:</span>
            <span id="previewAlamat" class="font-semibold"></span>
        </div>

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

           <div class="flex justify-between items-center mt-6">

    <a
        href="/login"
        class="text-[#5628C7] font-semibold hover:underline">

        ← Kembali ke Login

    </a>

    <button
        type="button"
        id="btnNextStep1"
        onclick="nextStep(2)"
        disabled
        class="bg-gray-300 text-gray-500 cursor-not-allowed px-6 py-3 rounded-xl font-semibold">

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

    <!-- Alamat (read-only, otomatis dari data referensi Partner Pulsa) -->
    <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-3 shadow-sm">

        <span class="text-xs font-semibold text-gray-500">
            Alamat Usaha (otomatis dari data Partner Pulsa)
        </span>

        <p id="step3AlamatPreview" class="w-full mt-1 text-gray-700">
            -
        </p>

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

let idAgenPPVerified = false;

function cekIdAgenPP()
{
    const idAgenPP = document.getElementById('id_agen_pp_input').value.trim();
    const messageBox = document.getElementById('cekIdMessage');
    const previewBox = document.getElementById('previewAgenPP');
    const btnCek = document.getElementById('btnCekId');
    const btnNext = document.getElementById('btnNextStep1');

    if (idAgenPP === '') {
        showCekMessage('ID Agen PP tidak boleh kosong.', false);
        return;
    }

    btnCek.disabled = true;
    btnCek.innerText = 'Mengecek...';

    fetch('{{ route("cek.id.agen.pp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_agen_pp: idAgenPP })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {

        btnCek.disabled = false;
        btnCek.innerText = 'Cek ID';

        if (ok && data.status === 'ok') {

            showCekMessage('ID Agen PP ditemukan.', true);

            document.getElementById('previewUsername').innerText = data.username;
            document.getElementById('previewNoHp').innerText = data.no_hp;
            document.getElementById('previewAlamat').innerText = data.alamat;
            document.getElementById('step3AlamatPreview').innerText = data.alamat;

            previewBox.classList.remove('hidden');

            idAgenPPVerified = true;

            btnNext.disabled = false;
            btnNext.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnNext.classList.add('bg-[#5628C7]', 'hover:bg-[#4c20bb]', 'text-white');

        } else {

            showCekMessage(data.message ?? 'ID Agen PP tidak ditemukan.', false);

            previewBox.classList.add('hidden');

            idAgenPPVerified = false;

            btnNext.disabled = true;
            btnNext.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnNext.classList.remove('bg-[#5628C7]', 'hover:bg-[#4c20bb]', 'text-white');
        }
    })
    .catch(() => {

        btnCek.disabled = false;
        btnCek.innerText = 'Cek ID';

        showCekMessage('Terjadi kesalahan, coba lagi.', false);
    });
}

function showCekMessage(text, success)
{
    const messageBox = document.getElementById('cekIdMessage');

    messageBox.innerText = text;
    messageBox.classList.remove('hidden', 'bg-red-50', 'text-red-600', 'bg-green-50', 'text-green-600');

    if (success) {
        messageBox.classList.add('bg-green-50', 'text-green-600');
    } else {
        messageBox.classList.add('bg-red-50', 'text-red-600');
    }
}

function nextStep(step)
{
    if (step === 2 && !idAgenPPVerified) {
        showCekMessage('Silakan cek ID Agen PP terlebih dahulu.', false);
        return;
    }

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