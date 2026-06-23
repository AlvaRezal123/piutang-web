@extends('layouts.owner')

@section('content')

<!-- HEADER -->

<div class="mb-8">
<h1 class="text-3xl font-bold text-gray-800">
    Approval Pengajuan Hutang
</h1>

<p class="text-gray-500 mt-2">
    Kelola dan validasi pengajuan hutang dari agen Partner Pulsa.
</p>


</div>

<!-- STATISTIK -->

<div class="grid md:grid-cols-4 gap-6 mb-8">


<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <p class="text-sm text-gray-500">
        Pending
    </p>

    <h2 class="text-3xl font-bold text-yellow-500 mt-2">
        {{ $hutang->where('status','pending')->count() }}
    </h2>

</div>

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <p class="text-sm text-gray-500">
        Disetujui
    </p>

    <h2 class="text-3xl font-bold text-purple-600 mt-2">
        {{ $hutang->where('status','disetujui')->count() }}
    </h2>

</div>

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <p class="text-sm text-gray-500">
        Ditolak
    </p>

    <h2 class="text-3xl font-bold text-red-500 mt-2">
        {{ $hutang->where('status','ditolak')->count() }}
    </h2>

</div>

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <p class="text-sm text-gray-500">
        Total Pengajuan
    </p>

    <h2 class="text-3xl font-bold text-green-500 mt-2">
        {{ $hutang->count() }}
    </h2>

</div>


</div>

<!-- TABEL -->

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

<div class="flex justify-between items-center mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        Data Pengajuan Hutang
    </h2>

    <select
        id="filterStatus"
        class="border border-gray-300 rounded-xl px-4 py-2">

        <option value="all">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="disetujui">Disetujui</option>
        <option value="ditolak">Ditolak</option>

    </select>

</div>

<div class="overflow-x-auto">

    <table class="w-full">

        <thead>

            <tr class="border-b">

                <th class="text-left py-3">Agen</th>
                <th class="text-left py-3">Cicilan</th>
                <th class="text-left py-3">Jumlah</th>
                <th class="text-left py-3">Metode</th>
                <th class="text-left py-3">Tanggal</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Aksi</th>

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

                    @if($h->status == 'pending')

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                            Pending
                        </span>

                    @elseif($h->status == 'disetujui')

                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                            Disetujui
                        </span>

                    @elseif($h->status == 'ditolak')

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Ditolak
                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            {{ ucfirst($h->status) }}
                        </span>

                    @endif

                </td>

                <td>

                    <div class="flex gap-2">

                        <a
                            href="/owner/hutang/detail/{{ $h->id }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm">

                            Detail

                        </a>

                        @if($h->status == 'pending')

                            <a
                                href="/owner/hutang/setujui/{{ $h->id }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm">

                                Setujui

                            </a>

                            <a
                                href="/owner/hutang/form-tolak/{{ $h->id }}"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm">

                                Tolak

                            </a>

                        @endif

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="6" class="text-center py-8 text-gray-500">

                    Belum ada data pengajuan hutang

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
