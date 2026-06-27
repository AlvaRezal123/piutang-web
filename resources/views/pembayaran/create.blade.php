@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Pembayaran Hutang
    </h1>

    <p class="text-gray-500 mt-2">
        Lakukan pembayaran dan unggah bukti transfer untuk proses validasi.
    </p>

</div>

<!-- ALERT -->
@if(session('success'))

<div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700">
    {{ session('success') }}
</div>

@endif

@if(session('error'))

<div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
    {{ session('error') }}
</div>

@endif

@if($errors->any())

<div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200">

    <ul class="list-disc ml-5 text-red-600">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

<!-- INFO HUTANG -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-5">
        Informasi Hutang
    </h2>
@if($hutang->metode == 'cash')

<div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6">

    <p class="font-semibold text-green-700">
        Pembayaran Penuh
    </p>

    <p class="mt-2">
        Nominal:
        Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}
    </p>

    <p>
        Jatuh Tempo:
        {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
    </p>

</div>

@elseif($cicilanAktif)

<div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">

    <p class="font-semibold text-blue-800">
        Cicilan Aktif
    </p>

    <p class="mt-2">
        Cicilan ke-{{ $cicilanAktif->cicilan_ke }}
    </p>

    <p>
        Nominal:
        Rp{{ number_format($cicilanAktif->jumlah_cicilan,0,',','.') }}
    </p>

    <p>
        Jatuh Tempo:
        {{ \Carbon\Carbon::parse($cicilanAktif->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
    </p>

</div>

@endif
    <div class="grid md:grid-cols-3 gap-6">

        <div>

            <p class="text-sm text-gray-500">
                Sisa Hutang
            </p>

            <p class="text-3xl font-bold text-[#5628C7] mt-2">
                Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Status
            </p>

            <span class="inline-flex mt-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                {{ ucfirst($hutang->status) }}

            </span>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Metode Hutang
            </p>

            <p class="font-semibold mt-2">
                {{ ucfirst($hutang->metode) }}
            </p>

        </div>

    </div>

</div>

<!-- FORM -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <form
        action="/pembayaran/store"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <input
            type="hidden"
            name="id_hutang"
            value="{{ $hutang->id }}">

        <input
            type="hidden"
            name="id_cicilan"
            value="{{ $cicilanAktif->id }}">
        <div class="grid md:grid-cols-2 gap-6">

            <!-- JUMLAH BAYAR -->
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jumlah Bayar
                </label>

                <input
                    type="text"
                    value="Rp{{ number_format($cicilanAktif->jumlah_cicilan,0,',','.') }}"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded-xl px-4 py-3">

                <input
                    type="hidden"
                    name="jumlah_bayar"
                    value="{{ $cicilanAktif->jumlah_cicilan }}">
                <p
                    id="warning_hutang"
                    class="text-red-500 text-sm mt-2 hidden">

                    Jumlah pembayaran melebihi sisa hutang

                </p>

            </div>

            <!-- NAMA PENGIRIM -->
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Pemilik Rekening
                </label>

                <input
                    type="text"
                    name="nama_pengirim"
                    required
                    placeholder="Masukkan nama pemilik rekening"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3">

            </div>

            <!-- METODE PEMBAYARAN -->
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Metode Pembayaran
                </label>
<select
    id="bank_pengirim"
    name="bank_pengirim"
    required
    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#5628C7] focus:border-[#5628C7]">

                    <option value="">💳 Pilih Metode Pembayaran</option>

                    <optgroup label="🏦 Transfer Bank">
                        <option>BCA</option>
                        <option>BRI</option>
                        <option>BNI</option>
                        <option>Mandiri</option>
                        <option>BSI</option>
                        <option>SeaBank</option>
                        <option>Bank Jago</option>
                    </optgroup>

                    <optgroup label="📱 E-Wallet">
                        <option>DANA</option>
                        <option>GoPay</option>
                        <option>OVO</option>
                        <option>ShopeePay</option>
                    </optgroup>

                    <optgroup label="⚡ Lainnya">
                        <option value="QRIS">QRIS</option>
                        <option value="lainnya">✏️ Lainnya...</option>
                    </optgroup>

                </select>
                <div id="bank_lain_container" class="mt-3 hidden">

                    <input
                        type="text"
                        id="bank_lain"
                        name="bank_lain"
                        placeholder="Contoh: CIMB Niaga, Neo Bank, LINE Bank..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#5628C7]">

                </div>
            </div>

            <!-- BUKTI PEMBAYARAN -->
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Bukti Pembayaran
                </label>

                <label
                    id="upload_area"
                    for="bukti_pembayaran"
                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-purple-50 hover:border-[#5628C7] transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-14 h-14 text-[#5628C7] mb-3"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M7 18a4.6 4.6 0 01-2-8.7A5 5 0 0110.2 4a5 5 0 014.7 3.2A4 4 0 0118 18H7zm5-8v6m0 0l-2-2m2 2l2-2" />

                    </svg>

                    <p class="font-semibold text-gray-700">
                        Klik di sini untuk memilih bukti pembayaran
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Format JPG, JPEG, PNG
                    </p>

                    <p
                        id="nama_file"
                        class="mt-3 text-[#5628C7] font-semibold hidden">
                    </p>

                </label>

                <input
                    type="file"
                    id="bukti_pembayaran"
                    name="bukti_pembayaran"
                    accept=".jpg,.jpeg,.png"
                    class="hidden">

                <!-- Preview -->
                <div
                    id="preview_container"
                    class="hidden mt-5">

                    <p class="font-semibold text-gray-700 mb-2">
                        Preview Bukti Pembayaran
                    </p>

                    <img
                        id="preview_image"
                        class="rounded-xl border border-gray-300 shadow-md max-h-72">

                </div>

            </div>
              </div>
            <!-- BUTTON -->
            <div class="mt-8 flex justify-end">

                <button
                    type="submit"
                    id="btn_submit"
                    class="bg-[#5628C7] hover:bg-[#4b22b0] text-white px-8 py-3 rounded-xl font-semibold">

                    Kirim Pembayaran

                </button>

            </div>

    </form>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==========================
        // METODE PEMBAYARAN
        // ==========================

        const selectBank = document.getElementById('bank_pengirim');
        const container = document.getElementById('bank_lain_container');
        const inputBank = document.getElementById('bank_lain');

        if (selectBank) {

            selectBank.addEventListener('change', function() {

                if (this.value === 'lainnya') {

                    container.classList.remove('hidden');
                    inputBank.required = true;

                } else {

                    container.classList.add('hidden');
                    inputBank.required = false;
                    inputBank.value = '';

                }

            });

        }

        // ==========================
        // UPLOAD BUKTI PEMBAYARAN
        // ==========================

        const inputFile = document.getElementById('bukti_pembayaran');
        const namaFile = document.getElementById('nama_file');
        const previewContainer = document.getElementById('preview_container');
        const previewImage = document.getElementById('preview_image');

        if (inputFile) {

            inputFile.addEventListener('change', function() {

                if (this.files.length > 0) {

                    const file = this.files[0];

                    namaFile.classList.remove('hidden');
                    namaFile.innerHTML = "✅ " + file.name;

                    const reader = new FileReader();

                    reader.onload = function(e) {

                        previewContainer.classList.remove('hidden');
                        previewImage.src = e.target.result;

                    };

                    reader.readAsDataURL(file);

                }

            });

        }

    });
</script>

@endsection