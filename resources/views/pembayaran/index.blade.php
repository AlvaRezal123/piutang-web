@extends('layouts.admin')

@section('content')

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
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- Total Pembayaran -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#534AB7]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EEEDFE] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#534AB7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z" />
            </svg>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Total Pembayaran</p>
        <h2 class="text-3xl font-medium text-[#534AB7] mt-1">{{ $pembayaran->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Semua status</p>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#EF9F27]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FAEEDA] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#854F0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Pending</p>
        <h2 class="text-3xl font-medium text-[#BA7517] mt-1">{{ $pembayaran->where('status','pending')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Menunggu validasi</p>
    </div>

    <!-- Disetujui -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#639922]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#3B6D11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Disetujui</p>
        <h2 class="text-3xl font-medium text-[#639922] mt-1">{{ $pembayaran->where('status','disetujui')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Pembayaran valid</p>
    </div>

    <!-- Ditolak -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#E24B4A]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FCEBEB] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A32D2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Ditolak</p>
        <h2 class="text-3xl font-medium text-[#A32D2D] mt-1">{{ $pembayaran->where('status','ditolak')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Perlu tindak lanjut</p>
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

        <!-- Tanggal -->
        <input
            type="date"
            name="tanggal"
            value="{{ request('tanggal') }}"
            class="border border-gray-300 rounded-xl px-4 py-2">

<!-- Tahun -->
<select
    name="tahun"
    class="border border-gray-300 rounded-xl px-4 py-2">

    <option value="">Semua Tahun</option>

    @for($i = date('Y'); $i >= 2024; $i--)

        <option
            value="{{ $i }}"
            {{ request('tahun') == $i ? 'selected' : '' }}>

            {{ $i }}

        </option>

    @endfor

</select>

        <!-- Status -->
        <select
            name="status"
            class="border border-gray-300 rounded-xl px-4 py-2">

            <option value="">Semua Status</option>

            <option value="pending"
                {{ request('status')=='pending' ? 'selected' : '' }}>
                Pending
            </option>

            <option value="disetujui"
                {{ request('status')=='disetujui' ? 'selected' : '' }}>
                Disetujui
            </option>

            <option value="ditolak"
                {{ request('status')=='ditolak' ? 'selected' : '' }}>
                Ditolak
            </option>

        </select>

        <button
            type="submit"
            class="bg-purple-600 text-white px-4 py-2 rounded-xl">

            Cari

        </button>

        <a
            href="/admin/pembayaran"
            class="border border-gray-300 bg-red-600 text-white px-4 py-2 rounded-xl">

            Reset

        </a>

    </div>

</form>
        </form>


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
    onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')"
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

</script>

@endsection