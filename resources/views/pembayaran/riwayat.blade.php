@extends('layouts.agen')

@section('content')

<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Pembayaran
        </h1>

        <p class="text-gray-500 mt-2">
            Pantau seluruh pembayaran hutang yang pernah Anda lakukan.
        </p>

    </div>

</div>

<!-- CARD RINGKASAN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Total Pembayaran
        </p>

        <h2 class="text-3xl font-bold text-[#5628C7] mt-3">
            {{ $pembayaran->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Menunggu Validasi
        </p>

        <h2 class="text-3xl font-bold text-yellow-500 mt-3">
            {{ $pembayaran->where('status','pending')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Disetujui
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-3">
            {{ $pembayaran->where('status','disetujui')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Ditolak
        </p>

        <h2 class="text-3xl font-bold text-red-600 mt-3">
            {{ $pembayaran->where('status','ditolak')->count() }}
        </h2>

    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Pembayaran
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

                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Bukti</th>

                </tr>

            </thead>

            <tbody>

                @forelse($pembayaran as $p)

                <tr
                    class="border-b status-row"
                    data-status="{{ $p->status }}">

                    <td class="py-4">
                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}
                    </td>

                    <td>
                        {{ $p->bank_pengirim }}
                    </td>

                    <td>

                        @if($p->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>

                        @elseif($p->status == 'disetujui')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Disetujui
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ditolak
                            </span>

                        @endif

                    </td>

                    <td>

                        <a
                            href="{{ asset('uploads/'.$p->bukti_pembayaran) }}"
                            target="_blank"
                            class="text-[#5628C7] font-semibold">

                            Lihat Bukti

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-8 text-gray-500">
                        Belum ada data pembayaran
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

document.getElementById('filterStatus')
.addEventListener('change', function(){

    let status = this.value;

    document.querySelectorAll('.status-row')
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