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

<!-- LIMIT -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <p class="text-sm text-gray-500">
        Limit Pinjaman
    </p>

    <h2 class="text-3xl font-bold text-[#5628C7] mt-2">
        Rp{{ number_format($agen->limit_pinjaman,0,',','.') }}
    </h2>

</div>

<!-- FORM -->
<div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

    <form action="/hutang/store" method="POST">

        @csrf

        <!-- JUMLAH HUTANG -->
        <div class="mb-6">

            <label class="block text-sm font-semibold text-gray-700 mb-2">

                Jumlah Hutang

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
                    Cash
                </option>

                <option value="cicil">
                    Cicil
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
                    2 Bulan
                </option>

                <option value="3 bulan">
                    3 Bulan
                </option>

            </select>

        </div>

        <!-- CATATAN -->
        <div class="mb-8">

            <label class="block text-sm font-semibold text-gray-700 mb-2">

                Catatan Pengajuan (Opsional)

            </label>

            <textarea
                name="catatan_pengajuan"
                rows="5"
                placeholder="Tambahkan catatan jika diperlukan"
                class="w-full border border-gray-300 rounded-xl px-4 py-3"></textarea>

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

</script>

@endsection