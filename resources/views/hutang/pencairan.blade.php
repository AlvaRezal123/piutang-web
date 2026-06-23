@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Pencairan Saldo
        </h1>

        <p class="text-gray-500 mt-2">
            Monitoring dan proses pencairan saldo agen.
        </p>

    </div>

</div>

<!-- CARD STATISTIK -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <p class="text-sm text-gray-500">
            Menunggu Pencairan
        </p>

        <h2 class="text-3xl font-bold text-yellow-500 mt-2">
            {{ $hutang->where('status','disetujui')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <p class="text-sm text-gray-500">
            Sudah Dicairkan
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-2">
            {{ $hutang->where('status','berjalan')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <p class="text-sm text-gray-500">
            Hutang Lunas
        </p>

        <h2 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $hutang->where('status','lunas')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <p class="text-sm text-gray-500">
            Total Nominal
        </p>

        <h2 class="text-2xl font-bold text-[#5628C7] mt-2">
            Rp{{ number_format($hutang->sum('jumlah_hutang'),0,',','.') }}
        </h2>

    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

    <!-- TOOLBAR -->
    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Pencairan
        </h2>

        <div class="flex gap-3">

            <input
                type="text"
                id="searchInput"
                placeholder="Cari Agen..."
                class="border border-gray-300 rounded-xl px-4 py-2">

            <select
                id="filterStatus"
                class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="all">
                    Semua Status
                </option>

                <option value="disetujui">
                    Menunggu
                </option>

                <option value="berjalan">
                    Dicairkan
                </option>

                <option value="lunas">
                    Lunas
                </option>

            </select>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b text-sm text-gray-500">

                    <th class="text-left py-4">
                        Agen
                    </th>

                    <th class="text-left py-4">
                        Nominal
                    </th>

                    <th class="text-left py-4">
                        Metode
                    </th>

                    <th class="text-left py-4">
                        Pengajuan
                    </th>

                    <th class="text-left py-4">
                        Status
                    </th>

                    <th class="text-left py-4">
                        Tgl Cair
                    </th>

                    <th class="text-left py-4">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($hutang as $h)

                <tr
                    class="border-b pencairan-row"
                    data-status="{{ $h->status }}"
                    data-agen="{{ strtolower($h->agen->username) }}">

                    <td class="py-5">

                        <div>

                            <p class="font-semibold text-gray-800">
                                {{ $h->agen->username }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $h->agen->id_agen_pp }}
                            </p>

                        </div>

                    </td>

                    <td>

                        <span class="font-semibold">

                            Rp{{ number_format($h->jumlah_hutang,0,',','.') }}

                        </span>

                    </td>

                    <td>

                        {{ ucfirst($h->metode) }}

                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}

                    </td>

                    <td>

                        @if($h->status == 'disetujui')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                                Menunggu

                            </span>

                        @elseif($h->status == 'berjalan')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                Dicairkan

                            </span>

                        @else

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                                Lunas

                            </span>

                        @endif

                    </td>

                    <td>

                        {{ $h->tanggal_pencairan
                            ? \Carbon\Carbon::parse($h->tanggal_pencairan)->format('d M Y')
                            : '-' }}

                    </td>

                    <td>

                        @if($h->status == 'disetujui')

                            <a
                                href="/admin/form-pencairan/{{ $h->id }}"
                                class="bg-[#5628C7] text-white px-4 py-2 rounded-xl text-sm font-semibold">

                                Proses

                            </a>

                        @else

                            @if($h->bukti_pencairan)

                                <a
                                    href="{{ asset('uploads/'.$h->bukti_pencairan) }}"
                                    target="_blank"
                                    class="text-[#5628C7] font-semibold">

                                    Lihat Bukti

                                </a>

                            @else

                                -

                            @endif

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-10 text-gray-500">

                        Belum ada data pencairan

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

const searchInput =
document.getElementById('searchInput');

const filterStatus =
document.getElementById('filterStatus');

function filterData(){

    let keyword =
        searchInput.value.toLowerCase();

    let status =
        filterStatus.value;

    document
    .querySelectorAll('.pencairan-row')
    .forEach(function(row){

        let nama =
            row.dataset.agen;

        let rowStatus =
            row.dataset.status;

        let cocokNama =
            nama.includes(keyword);

        let cocokStatus =
            status === 'all'
            || rowStatus === status;

        row.style.display =
            cocokNama && cocokStatus
            ? ''
            : 'none';

    });

}

searchInput.addEventListener(
    'keyup',
    filterData
);

filterStatus.addEventListener(
    'change',
    filterData
);

</script>

@endsection