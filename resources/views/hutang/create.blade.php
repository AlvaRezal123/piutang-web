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

            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center text-2xl">
                💳
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

            <div class="
                w-14 h-14 rounded-2xl flex items-center justify-center text-2xl

                @if($agen->status_kredit == 'terpercaya')
                    bg-green-100
                @elseif($agen->status_kredit == 'bermasalah')
                    bg-red-100
                @else
                    bg-blue-100
                @endif
            ">
                🛡️
            </div>

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


<!-- FORM -->
<div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

    <form action="/hutang/store" method="POST">

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

                <option value="cicil">
                    Cicilan
                </option>

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
                class="w-full border border-gray-300 rounded-xl px-4 py-3">

                <option value="2 bulan">
                    Tempo 2 Bulan
                </option>

                <option value="3 bulan">
                    Tempo 3 Bulan
                </option>

            </select>

        </div>

<!-- PREVIEW CICILAN -->
<div id="preview_cicilan" class="hidden mb-6">

    <h3 class="font-semibold text-gray-800 mb-3">
        Simulasi Cicilan
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

const limitPinjaman = "{{ $agen->limit_pinjaman }}";

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

        btnSubmit.disabled = true;

    } else {

        inputRupiah.classList.remove('border-red-500');

        warning.classList.add('hidden');

        btnSubmit.disabled = false;

    }

});
const lamaTempo =
    document.getElementById('lama_tempo');

const previewCicilan =
    document.getElementById('preview_cicilan');

const previewContent =
    document.getElementById('preview_content');

function tampilkanSimulasi() {

    if (
        metode.value !== 'cicil'
    ) {
        previewCicilan.classList.add('hidden');
        return;
    }

    let jumlah =
        parseInt(
            inputReal.value || 0
        );

    if (!jumlah) {
        previewCicilan.classList.add('hidden');
        return;
    }

    let bulan =
        lamaTempo.value === '2 bulan'
        ? 2
        : 3;

    let nominal =
        Math.ceil(jumlah / bulan);

    let html = '';

    for (
        let i = 1;
        i <= bulan;
        i++
    ) {

        let tanggal =
            new Date();

        tanggal.setMonth(
            tanggal.getMonth() + i
        );

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
                Jatuh Tempo
            </p>

            <p class="font-semibold text-red-500">
                ${tanggal.toLocaleDateString('id-ID')}
            </p>
        </div>

    </div>

</div>
`;
    }

    previewContent.innerHTML =
        html;

    previewCicilan.classList.remove(
        'hidden'
    );
}

metode.addEventListener(
    'change',
    tampilkanSimulasi
);

lamaTempo.addEventListener(
    'change',
    tampilkanSimulasi
);

inputRupiah.addEventListener(
    'input',
    tampilkanSimulasi
);

</script>

@endsection