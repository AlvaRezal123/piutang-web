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
@if($cicilanAktif)

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

        <!-- METODE -->
        <div>

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Metode Pembayaran
            </label>

            <select
                name="bank_pengirim"
                required
                class="w-full border border-gray-300 rounded-xl px-4 py-3">

                <option value="">
                    Pilih Metode Pembayaran
                </option>

                <optgroup label="Bank">

                    <option value="BCA">BCA</option>
                    <option value="BRI">BRI</option>
                    <option value="BNI">BNI</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BSI">BSI</option>
                    <option value="SeaBank">SeaBank</option>
                    <option value="Bank Jago">Bank Jago</option>

                </optgroup>

                <optgroup label="E-Wallet">

                    <option value="DANA">DANA</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="ShopeePay">ShopeePay</option>

                </optgroup>

                <option value="QRIS">
                    QRIS
                </option>

            </select>

        </div>

        <!-- BUKTI -->
        <div>

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Bukti Pembayaran
            </label>

            <input
                type="file"
                name="bukti_pembayaran"
                class="w-full border border-gray-300 rounded-xl px-4 py-3">

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


@endsection