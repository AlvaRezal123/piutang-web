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

<!-- STATISTIK -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-8">

    <!-- TOTAL NOMINAL HUTANG -->
    <div class="lg:col-span-2 bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-8 text-white shadow-sm flex flex-col justify-center">

        <p class="text-sm uppercase tracking-wider text-white/80">
            Total Nominal Hutang
        </p>

        <h2
         class="text-5xl font-bold mt-4 break-words">
            Rp{{ number_format($hutang->sum('jumlah_hutang'),0,',','.') }}
        </h2>

        <p class="mt-4 text-white/80 leading-relaxed">
            Akumulasi seluruh nominal hutang yang telah diajukan oleh agen.
        </p>

    </div>

    <!-- MENUNGGU PENCAIRAN -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">

        <div class="absolute top-0 left-0 right-0 h-1 bg-[#EF9F27]"></div>

        <div class="w-10 h-10 rounded-lg bg-[#FAEEDA] flex items-center justify-center mb-3">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-[#854F0B]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>

            </svg>

        </div>

        <p class="text-xs uppercase tracking-wide font-bold">
            Menunggu Pencairan
        </p>

        <h2 class="text-4xl font-semibold text-[#BA7517] mt-2">
            {{ $hutang->where('status','disetujui')->count() }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Siap dicairkan
        </p>

    </div>

    <!-- SUDAH DICAIRKAN -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">

        <div class="absolute top-0 left-0 right-0 h-1 bg-[#639922]"></div>

        <div class="w-10 h-10 rounded-lg bg-[#EAF3DE] flex items-center justify-center mb-3">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-[#3B6D11]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 6v6m0 0l3-3m-3 3l-3-3M5.25 15.75A2.25 2.25 0 017.5 13.5h9a2.25 2.25 0 012.25 2.25v2.25A2.25 2.25 0 0116.5 20.25h-9a2.25 2.25 0 01-2.25-2.25v-2.25z"/>

            </svg>

        </div>

        <p class="text-xs uppercase tracking-wide font-bold">
            Sudah Dicairkan
        </p>

        <h2 class="text-4xl font-semibold text-[#639922] mt-2">
            {{ $hutang->where('status','berjalan')->count() }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Saldo telah diberikan
        </p>

    </div>

    <!-- HUTANG LUNAS -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">

        <div class="absolute top-0 left-0 right-0 h-1 bg-[#3B82F6]"></div>

        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mb-3">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-blue-700"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

            </svg>

        </div>

        <p class="text-xs uppercase tracking-wide font-bold">
            Hutang Lunas
        </p>

        <h2 class="text-4xl font-semibold text-blue-600 mt-2">
            {{ $hutang->where('status','lunas')->count() }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Telah dilunasi
        </p>

    </div>

</div>
<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

    <!-- TOOLBAR -->
    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Pencairan
        </h2>

       <div class="flex flex-wrap gap-3">

    <!-- Cari -->
    <input
        type="text"
        id="searchInput"
        placeholder="Cari Agen..."
        class="border border-gray-300 rounded-xl px-4 py-2">

    <!-- Status -->
    <select
        id="filterStatus"
        class="border border-gray-300 rounded-xl px-4 py-2">

        <option value="all">Semua Status</option>
        <option value="disetujui">Menunggu</option>
        <option value="berjalan">Dicairkan</option>
        <option value="lunas">Lunas</option>

    </select>

    <!-- Tanggal -->
    <input
        type="date"
        id="filterTanggal"
        class="border border-gray-300 rounded-xl px-4 py-2">

    <!-- Tahun -->
    <select
        id="filterTahun"
        class="border border-gray-300 rounded-xl px-4 py-2">

        <option value="">Semua Tahun</option>

        @for($i = date('Y'); $i >= 2024; $i--)

            <option value="{{ $i }}">{{ $i }}</option>

        @endfor

    </select>

    <!-- Reset -->
    <button
        type="button"
        id="resetFilter"
        class="bg-gray-100 hover:bg-gray-200 px-4 rounded-xl">

        Reset

    </button>

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
    data-agen="{{ strtolower($h->agen->username) }}"
    data-tanggal="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y-m-d') }}"
    data-tahun="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y') }}">

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

const filterTanggal =
document.getElementById('filterTanggal');

const filterTahun =
document.getElementById('filterTahun');

const resetFilter =
document.getElementById('resetFilter');

const searchInput =
document.getElementById('searchInput');

const filterStatus =
document.getElementById('filterStatus');

function filterData(){

    let keyword = searchInput.value.toLowerCase();
    let status = filterStatus.value;
    let tanggal = filterTanggal.value;
    let tahun = filterTahun.value;

    document
    .querySelectorAll('.pencairan-row')
    .forEach(function(row){

        let nama = row.dataset.agen;
        let rowStatus = row.dataset.status;
        let rowTanggal = row.dataset.tanggal;
        let rowTahun = row.dataset.tahun;

        let cocokNama =
            nama.includes(keyword);

        let cocokStatus =
            status === 'all'
            || rowStatus === status;

        let cocokTanggal =
            tanggal === ''
            || rowTanggal === tanggal;

        let cocokTahun =
            tahun === ''
            || rowTahun === tahun;

        row.style.display =
            (cocokNama &&
             cocokStatus &&
             cocokTanggal &&
             cocokTahun)
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
filterTanggal.addEventListener(
    'change',
    filterData
);

filterTahun.addEventListener(
    'change',
    filterData
);

resetFilter.addEventListener('click', function(){

    searchInput.value = '';
    filterStatus.value = 'all';
    filterTanggal.value = '';
    filterTahun.value = '';

    filterData();

});

</script>

@endsection