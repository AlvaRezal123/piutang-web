@extends('layouts.owner')

@section('content')

<!-- HEADER -->
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Monitoring Hutang
    </h1>

    <p class="text-gray-500 mt-2">
        Pantau seluruh pengajuan dan status hutang agen Partner Pulsa.
    </p>

</div>

<!-- STATISTIK -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Total Hutang
        </p>

        <h2 class="text-3xl font-bold text-[#5628C7] mt-2">
            {{ $hutang->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Berjalan
        </p>

        <h2 class="text-3xl font-bold text-blue-500 mt-2">
            {{ $hutang->where('status','berjalan')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Lunas
        </p>

        <h2 class="text-3xl font-bold text-green-500 mt-2">
            {{ $hutang->where('status','lunas')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Total Nominal
        </p>

        <h2 class="text-2xl font-bold text-orange-500 mt-2">
            Rp{{ number_format($hutang->sum('jumlah_hutang'),0,',','.') }}
        </h2>

    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Monitoring Hutang
        </h2>

        <select
            id="filterStatus"
            class="border border-gray-300 rounded-xl px-4 py-2">

            <option value="all">
                Semua Status
            </option>

            <option value="pending">
                Pending
            </option>

            <option value="disetujui">
                Disetujui
            </option>

            <option value="berjalan">
                Berjalan
            </option>

            <option value="lunas">
                Lunas
            </option>

            <option value="ditolak">
                Ditolak
            </option>

        </select>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Agen
                    </th>

                    <th class="text-left py-3">
                        Jumlah
                    </th>

                    <th class="text-left py-3">
                        Metode
                    </th>

                    <th class="text-left py-3">
                        Pengajuan
                    </th>

                    <th class="text-left py-3">
                        Jatuh Tempo
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($hutang as $h)

                <tr
                    class="border-b status-row"
                    data-status="{{ $h->status }}">

                    <td class="py-4">

                        {{ $h->agen->username }}

                    </td>

                    <td>

                        Rp{{ number_format($h->jumlah_hutang,0,',','.') }}

                    </td>

                    <td>

                        {{ ucfirst($h->metode) }}

                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}

                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)->format('d M Y') }}

                    </td>

                    <td>

                        @if($h->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>

                        @elseif($h->status == 'disetujui')

                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                Disetujui
                            </span>

                        @elseif($h->status == 'berjalan')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                Berjalan
                            </span>

                        @elseif($h->status == 'lunas')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Lunas
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ditolak
                            </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">

                        Belum ada data hutang

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

document
.getElementById('filterStatus')
.addEventListener('change', function(){

    let status = this.value;

    document
    .querySelectorAll('.status-row')
    .forEach(function(row){

        if(status === 'all'){

            row.style.display = '';

        }else{

            row.style.display =
                row.dataset.status === status
                ? ''
                : 'none';

        }

    });

});

</script>

@endsection