@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Proses Pencairan Saldo
    </h1>

    <p class="text-gray-500 mt-2">
        Upload bukti transfer dan selesaikan proses pencairan dana agen.
    </p>

</div>

<!-- RINGKASAN PENCAIRAN -->
<div class="grid md:grid-cols-2 gap-6 mb-8">

    <!-- CARD 1: INFO AGEN & JUMLAH -->
    <div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Agen
        </p>
        <h2 class="text-2xl font-bold text-gray-800 mt-1">
            {{ $hutang->agen->username }}
        </h2>
        <p class="text-sm text-[#5628C7] font-semibold mt-1">
            ID Agen: {{ $hutang->agen->id_agen_pp }}
        </p>

        <div class="border-t border-gray-100 mt-6 pt-6">

            <p class="text-sm text-gray-500">
                Jumlah Pencairan
            </p>
            <h2 class="text-3xl font-bold text-green-600 mt-1">
                Rp{{ number_format($hutang->jumlah_hutang,0,',','.') }}
            </h2>

        </div>

    </div>

    <!-- CARD 2: DETAIL PENGAJUAN -->
    <div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500 mb-1">
            Metode
        </p>
        <h2 class="text-2xl font-bold text-blue-600 mt-1">
            {{ $hutang->metode == 'cash' ? 'Pembayaran Penuh' : 'Cicilan ' . $hutang->lama_tempo . ' Bulan' }}
        </h2>

        <div class="border-t border-gray-100 mt-6 pt-6 grid grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">
                    Tanggal Pengajuan
                </p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">
                    Jatuh Tempo
                </p>
                <p class="font-semibold text-red-500 mt-1">
                    {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
                </p>
            </div>

        </div>

    </div>

</div>

<!-- FORM PENCAIRAN -->
<div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Form Pencairan Dana
    </h2>

    @if($errors->any())

        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">

            <ul class="text-red-600 text-sm space-y-1">

                @foreach($errors->all() as $error)

                    <li>• {{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="/admin/simpan-pencairan/{{ $hutang->id }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

  <div class="grid md:grid-cols-2 gap-8">

    <!-- ===================== -->
    <!-- UPLOAD -->
    <!-- ===================== -->

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-3">
            Upload Bukti Transfer
        </label>

        <label
            id="upload_area"
            for="bukti_pencairan"
            class="flex flex-col items-center justify-center h-72 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-purple-50 hover:border-[#5628C7] transition-all duration-300">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-12 h-12 text-[#5628C7] mb-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M7 18a4.6 4.6 0 01-2-8.7A5 5 0 0110.2 4a5 5 0 014.7 3.2A4 4 0 0118 18H7zm5-8v6m0 0l-2-2m2 2l2-2"/>

            </svg>

            <p class="font-semibold text-gray-700">
                Klik atau Drag & Drop
            </p>

            <p class="text-sm text-gray-500 mt-2">
                JPG, JPEG, PNG
            </p>

            <p
                id="nama_file"
                class="mt-4 text-[#5628C7] font-semibold hidden">
            </p>

        </label>

        <input
            type="file"
            id="bukti_pencairan"
            name="bukti_pencairan"
            accept=".jpg,.jpeg,.png"
            class="hidden"
            required>

    </div>


    <!-- ===================== -->
    <!-- PREVIEW -->
    <!-- ===================== -->

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-3">
            Preview Bukti Transfer
        </label>

        <div
            id="preview_container"
            class="h-72 border border-purple-100 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden">

            <div id="preview_empty" class="text-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-16 h-16 text-gray-300 mx-auto mb-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M3 16l5-5a2 2 0 012.828 0L16 16m-2-2l1-1a2 2 0 012.828 0L21 16m-6-8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                </svg>

                <p class="text-gray-400">
                    Preview akan muncul di sini
                </p>

            </div>

            <img
                id="preview"
                class="hidden w-full h-full object-contain">

        </div>

    </div>

</div>
<br>
        <!-- Tombol -->
        <div class="flex justify-end gap-3">

            <a
                href="/admin/pencairan"
                class="px-6 py-3 rounded-2xl border border-gray-300 text-gray-700 font-semibold">

                Kembali

            </a>

            <button
                type="submit"
                class="bg-[#5628C7] hover:bg-[#4720aa] text-white px-8 py-3 rounded-2xl font-semibold transition">

                Cairkan Dana

            </button>

        </div>

    </form>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputFile = document.getElementById('bukti_pencairan');
    const uploadArea = document.getElementById('upload_area');
    const namaFile = document.getElementById('nama_file');
    const preview = document.getElementById('preview');
    const previewEmpty = document.getElementById('preview_empty');

    // ==========================
    // Tampilkan Preview
    // ==========================

    function tampilkanPreview(file) {

        // tampilkan nama file
        namaFile.classList.remove('hidden');
        namaFile.innerHTML = "✅ " + file.name;

        const reader = new FileReader();

        reader.onload = function (e) {

            preview.src = e.target.result;

            preview.classList.remove('hidden');
            previewEmpty.classList.add('hidden');

        };

        reader.readAsDataURL(file);
    }

    // ==========================
    // Klik Upload
    // ==========================

    inputFile.addEventListener('change', function () {

        if (this.files.length > 0) {

            tampilkanPreview(this.files[0]);

        }

    });

    // ==========================
    // Drag Over
    // ==========================

    ['dragenter', 'dragover'].forEach(eventName => {

        uploadArea.addEventListener(eventName, function (e) {

            e.preventDefault();
            e.stopPropagation();

            uploadArea.classList.add(
                'border-[#5628C7]',
                'bg-purple-50',
                'scale-[1.01]'
            );

        });

    });

    // ==========================
    // Drag Leave
    // ==========================

    ['dragleave', 'drop'].forEach(eventName => {

        uploadArea.addEventListener(eventName, function (e) {

            e.preventDefault();
            e.stopPropagation();

            uploadArea.classList.remove(
                'border-[#5628C7]',
                'bg-purple-50',
                'scale-[1.01]'
            );

        });

    });

    // ==========================
    // Drop File
    // ==========================

    uploadArea.addEventListener('drop', function (e) {

        const files = e.dataTransfer.files;

        if (files.length > 0) {

            inputFile.files = files;

            tampilkanPreview(files[0]);

        }

    });

});
</script>
@endsection