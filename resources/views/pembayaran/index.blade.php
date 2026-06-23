@extends('layouts.admin')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- HEADER -->
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Validasi Pembayaran
    </h1>

    <p class="text-gray-500 mt-2">
        Kelola dan validasi pembayaran yang dikirim oleh agen.
    </p>

</div>

@if(session('success'))

<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">

    {{ session('success') }}

</div>

@endif

<!-- STATISTIK -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Total Pembayaran
        </p>

        <h2 class="text-3xl font-bold text-[#5628C7] mt-2">
            {{ $pembayaran->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Pending
        </p>

        <h2 class="text-3xl font-bold text-yellow-500 mt-2">
            {{ $pembayaran->where('status','pending')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Disetujui
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-2">
            {{ $pembayaran->where('status','disetujui')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Ditolak
        </p>

        <h2 class="text-3xl font-bold text-red-500 mt-2">
            {{ $pembayaran->where('status','ditolak')->count() }}
        </h2>

    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex items-center justify-between mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        Data Pembayaran
    </h2>

    <div class="flex items-center gap-3">

        <form method="GET">

            <div class="flex items-center gap-2">

                <input
                    type="text"
                    id="rangeTanggal"
                    name="range_tanggal"
                    value="{{ request('range_tanggal') }}"
                    placeholder="Range Tanggal"
                    class="border border-gray-300 rounded-xl px-4 py-2 w-56">

                <button
                    type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl">

                    Cari

                </button>

            </div>

        </form>

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

            <option value="ditolak">
                Ditolak
            </option>

        </select>

    </div>

</div>


    

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Nama Agen
                    </th>

                    <th class="text-left py-3">
                       Cicilan
                    </th>

                    <th class="text-left py-3">
                        Jumlah Bayar
                    </th>

                    <th class="text-left py-3">
                         Metode
                    </th>

                    <th class="text-left py-3">
                        Tanggal
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                    <th class="text-left py-3">
                        Bukti
                    </th>

                    <th class="text-left py-3">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($pembayaran as $p)

                <tr
                    class="border-b status-row"
                    data-status="{{ $p->status }}">

                    <td class="py-4">

                        {{ $p->hutang->agen->username }}

                    </td>
                <td>
                @if($p->cicilan)
                    Cicilan {{ $p->cicilan->cicilan_ke }}
                @else
                    Cash
                @endif
                </td>
                    <td>

                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}

                    </td>
                    <td>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                            {{ $p->bank_pengirim }}
                        </span>
                    </td>
                    <td>

                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}

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
                            class="bg-purple-100 text-[#5628C7] px-4 py-2 rounded-xl font-semibold">

                            Lihat

                        </a>

                    </td>

                    <td>

                        @if($p->status == 'pending')

                        <div class="flex gap-2">

                            <a
                                href="/admin/pembayaran/setujui/{{ $p->id }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm">

                                Setujui

                            </a>

                         <button
                                type="button"
                                onclick="openTolakModal({{$p->id}})"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm">

                                Tolak

                            </button>
                        </div>

                        @else

                            <span class="text-gray-400">
                                Selesai
                            </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">

                        Belum ada pembayaran

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<div
    id="tolakModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">

        <h2 class="text-xl font-bold mb-2">
            Alasan Penolakan
        </h2>

        <p class="text-gray-500 mb-4">
            Masukkan alasan penolakan pembayaran.
        </p>

        <form
            id="formTolak"
            method="POST">

            @csrf

            <textarea
                name="alasan_penolakan"
                rows="4"
                required
                class="w-full border border-gray-300 rounded-xl p-3"></textarea>

            <div class="flex justify-end gap-3 mt-4">

                <button
                    type="button"
                    onclick="closeTolakModal()"
                    class="border px-4 py-2 rounded-xl">

                    Batal

                </button>

                <button
                    type="submit"
                    class="bg-red-500 text-white px-4 py-2 rounded-xl">

                    Kirim

                </button>

            </div>

        </form>

    </div>

</div>
<script>
function openTolakModal(id)
{
    document
        .getElementById('formTolak')
        .action =
        '/admin/pembayaran/simpan-tolak/' + id;

    document
        .getElementById('tolakModal')
        .classList.remove('hidden');
}
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
<script>

flatpickr("#rangeTanggal", {

    mode: "range",

    dateFormat: "Y-m-d"

});

</script>
@endsection