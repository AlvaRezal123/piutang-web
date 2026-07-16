@extends('layouts.agen')
@section('content')

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">
        Pengajuan Hutang
    </h1>
    <p class="text-gray-500 mt-2">
        Ajukan pinjaman sesuai limit yang tersedia.
    </p>
</div>

<!-- ALERT -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
   {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
    {{ session('error') }}
</div>
@endif

<!-- LIMIT & STATUS -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <!-- LIMIT PINJAMAN -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center">
                <i class="ti ti-wallet text-3xl text-yellow-600"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500">
                    Limit Pinjaman
                </p>
                <h2 class="text-3xl font-bold text-[#5628C7] mt-1">
                    Rp{{ number_format($agen->limit_pinjaman,0,',','.') }}
                </h2>
            </div>
        </div>
    </div>

    <!-- STATUS KREDIT -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">
        <div class="flex items-center gap-4">
            <i class="ti ti-shield-check text-yellow-600 text-3xl
                @if($agen->status_kredit == 'terpercaya')
                    text-green-600
                @elseif($agen->status_kredit == 'bermasalah')
                    text-red-600
                @else
                    text-blue-600
                @endif"></i>

            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <p class="text-sm font-bold text-gray-500">
                        Status Kredit
                    </p>
                    <span class="
                        px-3 py-1 rounded-full text-xs font-semibold
                        @if($agen->status_kredit == 'terpercaya')
                            bg-green-100 text-green-700
                        @elseif($agen->status_kredit == 'bermasalah')
                            bg-red-100 text-red-700
                        @else
                            bg-blue-100 text-blue-700
                        @endif
                    ">
                        {{ ucfirst($agen->status_kredit) }}
                    </span>
                </div>

                <p class="text-gray-500 mt-2">
                    @if($agen->status_kredit == 'terpercaya')
                        Riwayat pembayaran baik dan tepat waktu.
                    @elseif($agen->status_kredit == 'bermasalah')
                        Terdapat riwayat keterlambatan pembayaran.
                    @else
                        Agen baru dan belum memiliki riwayat kredit.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FASILITAS CICILAN -->
<div class="grid lg:grid-cols-12 gap-6 mb-8 flex-1">
    <div class="lg:col-span-4">
        <div class="bg-white rounded-3xl border border-purple-100 shadow-sm h-full overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center">
                    <i class="ti ti-credit-card text-2xl text-yellow-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Fasilitas Pembayaran Cicilan
                    </h2>
                    <p class="text-sm text-gray-500">
                        Penggunaan metode pembayaran cicilan memerlukan persetujuan owner.
                    </p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">

                @if($agen->status_permohonan_cicilan == 'belum')

                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                        <i class="ti ti-lock mr-2"></i>
                        Belum Aktif
                    </span>

                    <p class="mt-4 text-gray-600 leading-7">
                        Saat ini Anda hanya dapat menggunakan metode
                        <b>Pembayaran Penuh</b>.
                        Apabila ingin menggunakan metode <b>Cicilan</b>,
                        silakan ajukan permohonan terlebih dahulu.
                    </p>

                    <div class="mt-6">
                        <form action="/agen/ajukan-cicilan" method="POST">
                            @csrf
                            <button class="bg-[#5628C7] hover:bg-[#4b22b0] text-white px-6 py-3 rounded-xl font-semibold">
                                Ajukan Permohonan
                            </button>
                        </form>
                    </div>

                @elseif($agen->status_permohonan_cicilan == 'pending')

                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                        <i class="ti ti-hourglass mr-2"></i>
                        Menunggu Persetujuan
                    </span>

                    <p class="mt-4 text-gray-600 leading-7">
                        Permohonan Anda sedang diproses oleh owner.
                        Setelah disetujui, metode pembayaran
                        <b>Cicilan</b> akan tersedia pada form pengajuan hutang.
                    </p>

                @elseif($agen->status_permohonan_cicilan == 'disetujui')

                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                        <i class="ti ti-circle-check mr-2"></i>
                        Aktif
                    </span>

                    <p class="mt-4 text-gray-600 leading-7">
                        Selamat!
                        Fasilitas pembayaran cicilan telah diaktifkan.
                        Anda sekarang dapat memilih metode pembayaran
                        <b>Cicilan</b> pada form pengajuan hutang.
                    </p>

                @else

                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                        <i class="ti ti-circle-x mr-2"></i>
                        Ditolak
                    </span>

                    <p class="mt-4 text-gray-600 leading-7">
                        Permohonan fasilitas cicilan Anda belum disetujui oleh owner.
                        Anda masih dapat menggunakan metode
                        <b>Pembayaran Penuh</b> atau mengajukan permohonan kembali.
                    </p>

                    <div class="mt-6">
                        <form action="/agen/ajukan-cicilan" method="POST">
                            @csrf
                            <button class="bg-[#5628C7] hover:bg-[#4b22b0] text-white px-6 py-3 rounded-xl font-semibold">
                                Ajukan Kembali
                            </button>
                        </form>
                    </div>

                @endif

            </div>
        </div>
    </div>

    <div class="lg:col-span-8">

        <div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm h-full">
            <form action="/hutang/store" method="POST" id="formHutang">
                @csrf

                <!-- JUMLAH HUTANG -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Masukkan Jumlah Pengajuan Hutang
                    </label>
                    <input
                        type="text"
                        id="jumlah_hutang"
                        placeholder="Masukkan jumlah hutang"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#5628C7]"
                        required>
                    <input
                        type="hidden"
                        name="jumlah_hutang"
                        id="jumlah_hutang_real">
                    <p
                        id="warning_limit"
                        class="text-red-500 text-sm mt-2 hidden">
                        Jumlah hutang melebihi limit pinjaman
                    </p>
                </div>

                <!-- METODE -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Metode Pembayaran
                    </label>
                    <select
                        name="metode"
                        id="metode"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3">

                        <option value="cash">
                            Pembayaran Penuh
                        </option>

                        @if($agen->akses_cicilan)
                            <option value="cicil">
                                Cicilan
                            </option>
                        @endif

                    </select>
                </div>

                <!-- CICILAN -->
                <div id="tempo_div" class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lama Cicilan
                    </label>
                    <select
                        name="lama_tempo"
                        id="lama_tempo"
                        class="w-full rounded-2xl border px-4 py-3">

                        @foreach($periode as $index => $p)
                            <option
                                value="{{ $p->jumlah_bulan }}"
                                {{ $index == 0 ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- PREVIEW CICILAN -->
                <div id="preview_cicilan" class="hidden mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3">
                        Ringkasan Cicilan
                    </h3>
                    <div
                        id="preview_content"
                        class="flex gap-4 overflow-x-auto pb-2">
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex gap-3">
                    <a
                        href="/hutang-saya"
                        class="px-6 py-3 rounded-xl bg-gray-100 font-semibold">
                        Batal
                    </a>
                    <button
                        type="submit"
                        id="btn_submit"
                        class="px-6 py-3 rounded-xl bg-[#5628C7] text-white font-semibold hover:bg-[#4b22b0] transition">
                        Ajukan Hutang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PERINGATAN LIMIT -->
<div id="modalLimit" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-6 text-center">

        <div class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-4">
            <i class="ti ti-alert-triangle text-3xl text-red-600"></i>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2">
            Pengajuan Melebihi Limit
        </h2>

        <p class="text-gray-500 mb-1">
            Jumlah hutang yang Anda ajukan melebihi limit pinjaman yang tersedia.
        </p>

        <p class="text-gray-800 font-semibold mb-6">
            Limit Anda saat ini: <span id="modalLimitAngka">Rp0</span>
        </p>

        <button
            type="button"
            onclick="tutupModalLimit()"
            class="w-full px-6 py-3 rounded-xl bg-[#5628C7] hover:bg-[#4b22b0] text-white font-semibold">
            Mengerti
        </button>
    </div>
</div>

<script>
const metode = document.getElementById('metode');
const tempoDiv = document.getElementById('tempo_div');

function cekMetode() {
    if (metode.value == 'cash') {
        tempoDiv.style.display = 'none';
    } else {
        tempoDiv.style.display = 'block';
    }
}

cekMetode();
metode.addEventListener('change', cekMetode);

const inputRupiah = document.getElementById('jumlah_hutang');
const inputReal = document.getElementById('jumlah_hutang_real');
const warning = document.getElementById('warning_limit');
const btnSubmit = document.getElementById('btn_submit');
const formHutang = document.getElementById('formHutang');
const modalLimit = document.getElementById('modalLimit');
const modalLimitAngka = document.getElementById('modalLimitAngka');
const limitPinjaman = parseInt("{{ $agen->limit_pinjaman }}");

modalLimitAngka.textContent = 'Rp' + limitPinjaman.toLocaleString('id-ID');

function bukaModalLimit() {
    modalLimit.classList.remove('hidden');
    modalLimit.classList.add('flex');
}

function tutupModalLimit() {
    modalLimit.classList.remove('flex');
    modalLimit.classList.add('hidden');
}

inputRupiah.addEventListener('input', function () {
    let angka = this.value.replace(/[^,\d]/g, '');
    inputReal.value = angka;
    let hasil = '';
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    hasil += split[0].substr(0, sisa);
    let ribuan = split[0]
        .substr(sisa)
        .match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        hasil += separator + ribuan.join('.');
    }

    this.value = 'Rp ' + hasil;

    if (parseInt(angka) > limitPinjaman) {
        inputRupiah.classList.add('border-red-500');
        warning.classList.remove('hidden');
    } else {
        inputRupiah.classList.remove('border-red-500');
        warning.classList.add('hidden');
    }
});

// Cegat submit form: kalau melebihi limit, jangan kirim & tampilkan modal
formHutang.addEventListener('submit', function (e) {
    let angka = parseInt(inputReal.value || 0);

    if (!angka || angka > limitPinjaman) {
        e.preventDefault();
        bukaModalLimit();
    }
});

const lamaTempo = document.getElementById('lama_tempo');
const previewCicilan = document.getElementById('preview_cicilan');
const previewContent = document.getElementById('preview_content');

function tampilkanSimulasi() {
    let jumlah = parseInt(inputReal.value || 0);

    if (!jumlah) {
        previewCicilan.classList.add('hidden');
        return;
    }

    let html = '';

    // ========================
    // PEMBAYARAN PENUH
    // ========================
    if (metode.value === 'cash') {
        html = `
        <div class="min-w-[320px] bg-green-50 border border-green-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                    ✓
                </div>
                <h4 class="font-bold text-green-700 text-lg">
                    Pembayaran Penuh
                </h4>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">
                        Nominal Pembayaran
                    </p>
                    <p class="font-bold text-2xl text-gray-800">
                        Rp${jumlah.toLocaleString('id-ID')}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Informasi Tempo
                    </p>
                    <p class="font-semibold text-amber-600">
                        Ditentukan setelah saldo dicairkan Admin
                    </p>
                </div>
            </div>
        </div>
        `;

        previewContent.innerHTML = html;
        previewCicilan.classList.remove('hidden');
        return;
    }

    // ========================
    // CICILAN
    // ========================
    let bulan = parseInt(lamaTempo.value) || 1;
    let nominal = Math.ceil(jumlah / bulan);
    html = '';
    for (let i = 1; i <= bulan; i++) {
        html += `
        <div class="min-w-[320px] bg-purple-50 border border-purple-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-[#5628C7] text-white rounded-full flex items-center justify-center font-bold">
                    ${i}
                </div>
                <h4 class="font-bold text-[#5628C7] text-lg">
                    Cicilan Ke-${i}
                </h4>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">
                        Nominal Cicilan
                    </p>
                    <p class="font-bold text-2xl text-gray-800">
                        Rp${nominal.toLocaleString('id-ID')}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Informasi Tempo
                    </p>
                    <p class="font-semibold text-amber-600">
                        Ditentukan setelah saldo dicairkan Admin
                    </p>
                </div>
            </div>
        </div>
        `;
    }

    previewContent.innerHTML = html;
    previewCicilan.classList.remove('hidden');
}

metode.addEventListener('change', function () {
    if (this.value === 'cicil') {
        lamaTempo.selectedIndex = 0;
    }
    tampilkanSimulasi();
});

lamaTempo.addEventListener('change', tampilkanSimulasi);
inputRupiah.addEventListener('input', tampilkanSimulasi);
</script>

@endsection