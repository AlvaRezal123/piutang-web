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

@if(session('success'))

<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">

    {{ session('success') }}

</div>

@endif

<!-- STATISTIK -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- Pending -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#EF9F27]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FAEEDA] flex items-center justify-center mb-3">
            <i class="ti ti-clock text-[#854F0B] text-base"></i>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Pending</p>
        <h2 class="text-3xl font-medium text-[#BA7517] mt-1">{{ $hutang->where('status','pending')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Menunggu review</p>
    </div>

    <!-- Disetujui -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#5628C7]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EEEDFE] flex items-center justify-center mb-3">
            <i class="ti ti-circle-check text-[#5628C7] text-base"></i>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Disetujui</p>
        <h2 class="text-3xl font-medium text-[#5628C7] mt-1">{{ $hutang->where('status','disetujui')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Pengajuan disetujui</p>
    </div>

    <!-- Ditolak -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#E24B4A]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FCEBEB] flex items-center justify-center mb-3">
            <i class="ti ti-circle-x text-[#A32D2D] text-base"></i>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Ditolak</p>
        <h2 class="text-3xl font-medium text-[#A32D2D] mt-1">{{ $hutang->where('status','ditolak')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Perlu ditinjau ulang</p>
    </div>

    <!-- Total Pengajuan -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#639922]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] flex items-center justify-center mb-3">
            <i class="ti ti-file-text text-[#3B6D11] text-base"></i>
        </div>
        <p class="text-xs text-black-600 uppercase tracking-wide font-bold">Total Pengajuan</p>
        <h2 class="text-3xl font-medium text-[#639922] mt-1">{{ $hutang->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Semua status</p>
    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Pengajuan Hutang
        </h2>

        <div class="flex flex-wrap gap-3">

            <!-- Cari Agen -->
            <input
                type="text"
                id="searchInput"
                placeholder="Cari Agen..."
                class="border border-gray-300 rounded-xl px-4 py-2">

            <!-- Status -->
            <select id="filterStatus" class="border border-gray-300 rounded-xl px-4 py-2">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="berjalan">Berjalan</option>
                <option value="lunas">Lunas</option>
            </select>

            <!-- Cari -->
            <button
                type="button"
                id="btnCariFilter"
                class="bg-purple-600 text-white px-4 py-2 rounded-xl">

                Cari

            </button>

            <!-- Reset -->
            <button
                type="button"
                id="resetFilter"
                class="border border-gray-300 bg-red-600 text-white px-4 py-2 rounded-xl">

                Reset

            </button>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full table-auto">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">Agen</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Detail</th>
                    <th class="text-left py-3">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($hutang as $h)

                <tr
                    class="border-b status-row"
                    data-status="{{ $h->status }}"
                    data-agen="{{ strtolower($h->agen->username) }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y-m-d') }}"
                    data-tahun="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y') }}">

                    <!-- Agen -->
                    <td class="py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $h->agen->username }}</span>
                        </div>
                    </td>

                    <!-- Jumlah -->
                    <td class="font-semibold text-gray-800">
                        Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}
                    </td>

                    <!-- Metode -->
                    <td>

                        @if($h->metode == 'cash')

                            <div class="flex items-center gap-2">

                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="ti ti-credit-card text-green-600 text-sm"></i>
                                </div>

                                <span class="font-medium">
                                    Pembayaran Penuh
                                </span>

                            </div>

                        @else

                            <div class="flex items-center gap-2">

                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="ti ti-calendar-time text-blue-600 text-sm"></i>
                                </div>

                                <span class="font-medium">
                                    Cicilan {{ $h->lama_tempo }}
                                </span>

                            </div>

                        @endif

                    </td>

                    <!-- Tanggal -->
                    <td class="text-gray-500">
                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <!-- Status -->
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

                    <!-- Detail -->
                    <td>

                        <a
                            href="/owner/hutang/detail/{{ $h->id }}"
                            title="Lihat Detail"
                            class="bg-purple-100 text-[#5628C7] w-10 h-10 rounded-xl inline-flex items-center justify-center">

                            <i class="ti ti-eye text-lg"></i>

                        </a>

                    </td>

                    <!-- Aksi -->
                    <td>

                        @if($h->status == 'pending')

                        <div class="flex gap-2">

                            <a
                                href="/owner/hutang/setujui/{{ $h->id }}"
                                onclick="return confirmSetujui(event)"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm">

                                Setujui

                            </a>

                            <button
                                type="button"
                                data-id="{{ $h->id }}"
                                onclick="openModal(this.dataset.id)"
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

                    <td colspan="7" class="text-center py-8 text-gray-500">
                        Belum ada data pengajuan hutang
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- ===================== -->
<!-- MODAL TOLAK (per hutang pending) -->
<!-- ===================== -->

@foreach($hutang as $h)
@if($h->status == 'pending')

<div
    id="modal{{ $h->id }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background:rgba(0,0,0,0.45)">

    <div class="bg-white rounded-3xl w-full max-w-lg  shadow-xl p-8">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i class="ti ti-alert-triangle text-red-500 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Tolak Pengajuan</h2>
                <p class="text-sm text-gray-400">{{ $h->agen->username }} · Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}</p>
            </div>
        </div>

        <form action="/owner/hutang/tolak/{{ $h->id }}" method="POST">
            @csrf

            <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan penolakan</label>

            <textarea
                name="alasan_penolakan"
                rows="4"
                required
                placeholder="Masukkan alasan penolakan yang jelas untuk agen..."
                class="w-full border border-gray-200 rounded-2xl p-4 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>

            <div class="flex justify-end gap-3 mt-5">

                <button
                    type="button"
                    data-id="{{ $h->id }}"
                    onclick="closeModal(this.dataset.id)"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-colors">
                    Tolak Pengajuan
                </button>

            </div>

        </form>

    </div>

</div>

@endif
@endforeach

<script>

const searchInput = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');
const btnCariFilter = document.getElementById('btnCariFilter');
const resetFilter = document.getElementById('resetFilter');

function filterData() {

    let keyword = searchInput.value.toLowerCase();
    let status = filterStatus.value;

    document.querySelectorAll('.status-row').forEach(function (row) {

        let nama = row.dataset.agen;
        let rowStatus = row.dataset.status;

        let cocokNama = nama.includes(keyword);
        let cocokStatus = status === 'all' || rowStatus === status;

        row.style.display =
            (cocokNama && cocokStatus)
                ? ''
                : 'none';

    });

}

searchInput.addEventListener('keyup', filterData);
filterStatus.addEventListener('change', filterData);
btnCariFilter.addEventListener('click', filterData);

resetFilter.addEventListener('click', function () {

    searchInput.value = '';
    filterStatus.value = 'all';

    filterData();

});

function openModal(id) {
    document.getElementById('modal' + id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById('modal' + id).classList.add('hidden');
}

function confirmSetujui(event) {

    if (!confirm('Apakah Anda yakin ingin menyetujui pengajuan hutang ini?')) {
        event.preventDefault();
        return false;
    }

    return true;

}

</script>

@endsection