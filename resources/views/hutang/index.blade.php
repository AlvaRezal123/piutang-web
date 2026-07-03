@extends('layouts.owner')

@section('content')

<!-- HEADER -->
<div class="mb-8 flex items-center justify-between">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">Approval Pengajuan Hutang</h1>
        <p class="text-gray-400 mt-1 text-sm">Kelola dan validasi pengajuan hutang dari agen Partner Pulsa.</p>
    </div>

    <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-2xl px-4 py-3">
        <i class="ti ti-clock text-yellow-500 text-lg"></i>
        <div>
            <p class="text-xs text-yellow-600">Menunggu review</p>
            <p class="text-xl font-bold text-yellow-600">{{ $hutang->where('status','pending')->count() }}</p>
        </div>
    </div>

</div>

<!-- STATISTIK -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center mb-3">
            <i class="ti ti-clock text-yellow-600 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Pending</p>
        <p class="text-3xl font-bold text-yellow-500">{{ $hutang->where('status','pending')->count() }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mb-3">
            <i class="ti ti-circle-check text-[#5628C7] text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Disetujui</p>
        <p class="text-3xl font-bold text-[#5628C7]">{{ $hutang->where('status','disetujui')->count() }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center mb-3">
            <i class="ti ti-circle-x text-red-500 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Ditolak</p>
        <p class="text-3xl font-bold text-red-500">{{ $hutang->where('status','ditolak')->count() }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mb-3">
            <i class="ti ti-file-text text-green-600 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Total Pengajuan</p>
        <p class="text-3xl font-bold text-green-600">{{ $hutang->count() }}</p>
    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">

    <!-- Toolbar -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">

        <h2 class="text-base font-semibold text-gray-800">Data Pengajuan Hutang</h2>

        <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
            <option value="berjalan">Berjalan</option>
            <option value="lunas">Lunas</option>
        </select>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Agen</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jumlah</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($hutang as $h)

                <tr class="status-row border-b border-gray-50 hover:bg-gray-50 transition-colors" data-status="{{ $h->status }}">

                    <!-- Agen -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $h->agen->username }}</span>
                        </div>
                    </td>

                    <!-- Jumlah -->
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}
                    </td>

                    <!-- Metode -->
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1 text-gray-500">

                        @if($h->metode == 'cash')

                            <i class="ti ti-cash text-green-500 text-sm"></i>
                            Pembayaran Penuh

                        @else

                            <i class="ti ti-calendar-repeat text-blue-500 text-sm"></i>
                            Cicilan

                        @endif

                    </span>
                </td>

                    <!-- Tanggal -->
                    <td class="px-6 py-4 text-gray-500">
                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        @if($h->status == 'pending')
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-clock text-xs"></i> Pending
                            </span>
                        @elseif($h->status == 'disetujui')
                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-check text-xs"></i> Disetujui
                            </span>
                        @elseif($h->status == 'berjalan')
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-loader text-xs"></i> Berjalan
                            </span>
                        @elseif($h->status == 'lunas')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-circle-check text-xs"></i> Lunas
                            </span>
                        @elseif($h->status == 'ditolak')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-x text-xs"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ ucfirst($h->status) }}
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">

                            <a href="/owner/hutang/detail/{{ $h->id }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition-colors">
                                <i class="ti ti-eye text-xs"></i> Detail
                            </a>

                            @if($h->status == 'pending')

                          <a
                                href="/owner/hutang/setujui/{{ $h->id }}"
                                onclick="return confirmSetujui(event)"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 text-xs font-semibold transition-colors">

                                <i class="ti ti-check text-xs"></i>
                                Setujui

                            </a>

                                <button
                                    data-id="{{ $h->id }}"
                                    onclick="openModal(this.dataset.id)"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 text-xs font-semibold transition-colors">
                                    <i class="ti ti-x text-xs"></i> Tolak
                                </button>

                            @endif

                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-16 text-gray-400">
                        <i class="ti ti-inbox text-4xl block mb-3 text-gray-200"></i>
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
document.getElementById('filterStatus').addEventListener('change', function () {
    const status = this.value;
    document.querySelectorAll('.status-row').forEach(function (row) {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
});

function openModal(id) {
    document.getElementById('modal' + id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById('modal' + id).classList.add('hidden');
}
function confirmSetujui(event){

    if(!confirm('Apakah Anda yakin ingin menyetujui pengajuan hutang ini?')){
        event.preventDefault();
        return false;
    }

    return true;

}
</script>

@endsection