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

<!-- INFO AGEN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Nama Agen
        </p>

        <h2 class="text-xl font-bold text-gray-800 mt-2">
            {{ $hutang->agen->username }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            ID Agen
        </p>

        <h2 class="text-xl font-bold text-[#5628C7] mt-2">
            {{ $hutang->agen->id_agen_pp }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Jumlah Pencairan
        </p>

        <h2 class="text-xl font-bold text-green-600 mt-2">
            Rp{{ number_format($hutang->jumlah_hutang,0,',','.') }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Metode
        </p>

        <h2 class="text-xl font-bold text-blue-600 mt-2">
            {{ ucfirst($hutang->metode) }}
        </h2>

    </div>

</div>

<!-- DETAIL -->
<div class="grid md:grid-cols-2 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Tanggal Pengajuan
        </p>

        <h2 class="text-lg font-bold mt-2">
            {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Jatuh Tempo
        </p>

        <h2 class="text-lg font-bold text-red-500 mt-2">
            {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
        </h2>

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

        <!-- Upload Bukti -->
        <div class="mb-6">

            <label class="block text-sm font-semibold text-gray-700 mb-2">

                Upload Bukti Transfer

            </label>

            <input
                type="file"
                name="bukti_pencairan"
                id="bukti_pencairan"
                required
                class="w-full border border-gray-300 rounded-2xl px-4 py-3">

        </div>

        <!-- Preview -->
        <div class="mb-6">

            <img
                id="preview"
                class="hidden max-h-80 rounded-2xl border border-purple-100 shadow-sm">

        </div>

        <!-- Keterangan -->
     

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

document
.getElementById('bukti_pencairan')
.addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){

            const preview =
                document.getElementById('preview');

            preview.src =
                event.target.result;

            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }

});

</script>

@endsection