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

    <div class="grid md:grid-cols-2 gap-6">

        <!-- DETAIL CASH / CICILAN AKTIF -->
        @if($hutang->metode == 'cash')

        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-start gap-4">

            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-green-600 shadow-sm shrink-0">
                <i class="ti ti-cash text-xl"></i>
            </div>

            <div>
                <p class="font-semibold text-green-700">
                    Pembayaran Penuh
                </p>

                <p class="mt-2 text-gray-700">
                    Nominal:
                    <span class="font-semibold">Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}</span>
                </p>

                <p class="text-gray-700">
                    Jatuh Tempo:
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</span>
                </p>
            </div>

        </div>

        @elseif($cicilanAktif)

        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-blue-700 shadow-sm shrink-0">
                <i class="ti ti-credit-card-pay text-xl"></i>
            </div>

            <div>
                <p class="font-semibold text-blue-800">
                    Cicilan Aktif
                </p>

                <p class="mt-2 text-gray-700">
                    Cicilan ke-{{ $cicilanAktif->cicilan_ke }}
                </p>

                <p class="text-gray-700">
                    Nominal:
                    <span class="font-semibold">Rp{{ number_format($cicilanAktif->jumlah_cicilan,0,',','.') }}</span>
                </p>

                <p class="text-gray-700">
                    Jatuh Tempo:
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($cicilanAktif->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</span>
                </p>
            </div>

        </div>

        @endif

        <!-- SISA HUTANG -->
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5 flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-[#5628C7] shadow-sm shrink-0">
                <i class="ti ti-wallet text-xl"></i>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Sisa Hutang
                </p>

                <p class="text-3xl font-bold text-[#5628C7] mt-1">
                    Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}
                </p>
            </div>

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

            <!-- BANK TUJUAN -->
            <div class="md:col-span-2">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Transfer ke Rekening
                </label>

                <select
                    id="bank_tujuan"
                    name="bank_tujuan"
                    required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#5628C7] focus:border-[#5628C7]">

                    <option value="">🏦 Pilih Rekening Tujuan</option>
                    <option value="BCA" data-rekening="0961435187">BCA - 0961435187</option>
                    <option value="Mandiri" data-rekening="1800014088662">Mandiri - 1800014088662</option>
                    <option value="BNI" data-rekening="1886994256">BNI - 1886994256</option>
                    <option value="BRI" data-rekening="010601003836301">BRI - 010601003836301</option>

                </select>

                <div
                    id="rekening_tujuan_card"
                    class="hidden mt-3 bg-purple-50 border border-purple-200 rounded-2xl p-4 flex items-center justify-between gap-4">

                    <div>

                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">
                            No. Rekening
                        </p>

                        <p
                            id="rekening_tujuan_nomor"
                            class="text-lg font-bold text-[#5628C7] mt-1">
                            -
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            a.n. PT PARTNER PULSA INDONESIA
                        </p>

                    </div>

    
                </div>

            </div>

            <!-- METODE PEMBAYARAN -->
            <div class="md:col-span-2">

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
            <div class="md:col-span-2">

                <div class="grid md:grid-cols-2 gap-6">

                    <!-- UPLOAD -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bukti Pembayaran
                        </label>

                        <label
                            id="upload_area"
                            for="bukti_pembayaran"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-purple-50 hover:border-[#5628C7] transition">

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

                            <p class="font-semibold text-gray-700 text-center px-4">
                                Klik di sini untuk memilih bukti pembayaran
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Format JPG, JPEG, PNG
                            </p>

                            <p
                                id="nama_file"
                                class="mt-3 text-[#5628C7] font-semibold hidden text-center px-4">
                            </p>

                        </label>

                        <input
                            type="file"
                            id="bukti_pembayaran"
                            name="bukti_pembayaran"
                            accept=".jpg,.jpeg,.png"
                            class="hidden">

                    </div>

                    <!-- PREVIEW -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Preview Bukti Pembayaran
                        </label>

                        <div
                            id="preview_placeholder"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 text-center px-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-10 h-10 text-gray-300 mb-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18v18H3V3z" />

                            </svg>

                            <p class="text-sm text-gray-400">
                                Preview akan muncul di sini
                            </p>

                        </div>

                        <div
                            id="preview_container"
                            class="hidden w-full h-64">

                            <img
                                id="preview_image"
                                class="w-full h-64 object-contain rounded-2xl border border-gray-300 shadow-md bg-gray-50">

                        </div>

                    </div>

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
        // BANK TUJUAN (REKENING PERUSAHAAN)
        // ==========================

        const selectTujuan = document.getElementById('bank_tujuan');
        const kartuTujuan = document.getElementById('rekening_tujuan_card');
        const nomorTujuan = document.getElementById('rekening_tujuan_nomor');
        const btnSalin = document.getElementById('btn_copy_rekening');

        if (selectTujuan) {

            selectTujuan.addEventListener('change', function() {

                const opsiTerpilih = this.options[this.selectedIndex];
                const rekening = opsiTerpilih.getAttribute('data-rekening');

                if (rekening) {

                    nomorTujuan.textContent = rekening;
                    kartuTujuan.classList.remove('hidden');

                } else {

                    kartuTujuan.classList.add('hidden');
                    nomorTujuan.textContent = '-';

                }

            });

        }

      
            });

    

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
        const previewPlaceholder = document.getElementById('preview_placeholder');
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

                        previewPlaceholder.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        previewImage.src = e.target.result;

                    };

                    reader.readAsDataURL(file);

                }

            });

        }

  
</script>

@endsection