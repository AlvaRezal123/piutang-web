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
<div class="grid md:grid-cols-[1.3fr_1fr_1fr_1fr] gap-6 mb-8">

    <!-- Total Pembayaran -->
    <div class="bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-6 shadow-sm text-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z" />
                </svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-white/80">Total Pembayaran</p>
        </div>
        <h2 class="text-4xl font-bold">{{ $pembayaran->total() }}</h2>
        <div class="border-t border-white/20 mt-4 pt-4">
            <p class="text-sm text-white/80">Semua status</p>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Pending</p>
        </div>
        <h2 class="text-4xl font-bold text-yellow-600">{{ $pembayaran->where('status','pending')->count() }}</h2>
        <p class="text-sm text-gray-500 mt-4">Menunggu validasi</p>
    </div>

    <!-- Disetujui -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Disetujui</p>
        </div>
        <h2 class="text-4xl font-bold text-yellow-600">{{ $pembayaran->where('status','disetujui')->count() }}</h2>
        <p class="text-sm text-gray-500 mt-4">Pembayaran valid</p>
    </div>

    <!-- Ditolak -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Ditolak</p>
        </div>
        <h2 class="text-4xl font-bold text-yellow-600">{{ $pembayaran->where('status','ditolak')->count() }}</h2>
        <p class="text-sm text-gray-500 mt-4">Perlu tindak lanjut</p>
    </div>

</div>
<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Pembayaran
        </h2>

        <form method="GET">

            <div class="flex items-center gap-2">

                <!-- Cari Agen -->
                <input
                    type="text"
                    name="cari_agen"
                    value="{{ request('cari_agen') }}"
                    placeholder="Cari Agen..."
                    class="border border-gray-300 rounded-xl px-4 py-2">

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

    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        ID Hutang
                    </th>

                    <th class="text-left py-3">
                        Nama Agen
                    </th>

                    <th class="text-left py-3">
                        Tanggal Pembayaran
                    </th>

                    <th class="text-left py-3">
                        Metode Pembayaran
                    </th>

                    <th class="text-left py-3">
                        Jumlah Bayar
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                    <th class="text-left py-3">
                        Bukti
                    </th>

                    <th class="text-left py-3">
                        Detail
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

                    <td class="py-4 text-sm text-gray-500">

                        #{{ $p->hutang->id }}

                    </td>

                    <td class="py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($p->hutang->agen->username, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $p->hutang->agen->username }}</p>
                                <p class="text-sm text-gray-500">{{ $p->hutang->agen->id_agen_pp }}</p>
                            </div>
                        </div>
                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}

                    </td>

                    <td>
                        @if($p->hutang->metode == 'cash')

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

                                Cicilan {{ $p->cicilan->cicilan_ke }}
                                dari
                                {{ $p->hutang->lama_tempo }}

                            </span>

                        </div>

                        @endif
                    </td>

                    <td>

                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}

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
                            title="Lihat Bukti Pembayaran"
                            class="bg-purple-100 text-[#5628C7] w-10 h-10 rounded-xl inline-flex items-center justify-center">

                            <i class="ti ti-eye text-lg"></i>

                        </a>

                    </td>

                    <td>

              <button
                type="button"
                class="btn-detail bg-purple-100 text-[#5628C7] px-4 py-2 rounded-xl font-semibold text-sm"
                data-id="{{ $p->id }}">

                Detail

            </button>
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
                            class="btn-tolak bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm"
                            data-id="{{ $p->id }}">

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

                    <td colspan="9" class="text-center py-8 text-gray-500">

                        Belum ada pembayaran

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <!-- PAGINATION -->
        @if($pembayaran->hasPages())

        <div class="flex justify-between items-center mt-6">

            <div class="text-sm text-gray-500">
                Menampilkan
                {{ $pembayaran->firstItem() }}
                -
                {{ $pembayaran->lastItem() }}
                dari
                {{ $pembayaran->total() }}
                data
            </div>

            {{ $pembayaran->links() }}

        </div>

        @endif

    </div>

</div>

<!-- MODAL DETAIL CICILAN -->
<div
    id="detailModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-6 w-full max-w-2xl max-h-[85vh] overflow-y-auto">

        <div class="flex items-start justify-between mb-5">

            <div>

                <h2 class="text-xl font-bold text-gray-800" id="detailAgenNama">
                    -
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    ID Agen: <span id="detailAgenId">-</span>
                </p>

            </div>

            <button
                type="button"
                onclick="closeDetailModal()"
                class="text-gray-400 hover:text-gray-600">

                <i class="ti ti-x text-xl"></i>

            </button>

        </div>

        <div class="grid grid-cols-2 gap-3 mb-6">

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Tanggal Pengajuan</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailTanggalPengajuan">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Metode</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailMetode">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Total Hutang</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailTotalHutang">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Sisa Hutang</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailSisaHutang">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Metode Pembayaran</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailBankPengirim">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Rekening Tujuan</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailBankTujuan">-</p>
            </div>

        </div>

        <h3
            id="detailJudulCicilan"
            class="font-bold text-gray-800 mb-3">

            Rincian Cicilan

        </h3>

        <div id="detailCicilanList" class="flex flex-col gap-3">
        </div>

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

function closeTolakModal()
{
    document
        .getElementById('tolakModal')
        .classList.add('hidden');
}
</script>

<script type="application/json" id="pembayaranDataJson">
    @json($pembayaranData)
</script>

<script>

// Data seluruh pembayaran (halaman berjalan) + cicilan dari hutang terkait,
// sudah disiapkan di PembayaranController@index lalu di-dump satu kali
// di sini supaya modal detail bisa langsung dipopulate tanpa AJAX.
const pembayaranData = JSON.parse(document.getElementById('pembayaranDataJson').textContent);

function openDetailModal(id)
{
    const data = pembayaranData[id];

    if (!data) {
        return;
    }

    document.getElementById('detailAgenNama').textContent = data.agen;
    document.getElementById('detailAgenId').textContent = data.idAgen;
    document.getElementById('detailTanggalPengajuan').textContent = data.tanggalPengajuan;

    document.getElementById('detailMetode').textContent =
        data.metode === 'cash'
            ? 'Pembayaran Penuh'
            : 'Cicilan ' + (data.lamaTempo ?? '-') + ' bulan';

    const judul = document.getElementById('detailJudulCicilan');

    judul.textContent =
        data.metode === 'cash'
            ? 'Rincian Pembayaran'
            : 'Rincian Cicilan';

    document.getElementById('detailTotalHutang').textContent = 'Rp' + data.totalHutang;
    document.getElementById('detailSisaHutang').textContent = 'Rp' + data.sisaHutang;
    document.getElementById('detailBankPengirim').textContent = data.bankPengirim ?? '-';
    document.getElementById('detailBankTujuan').textContent = data.bankTujuan ?? '-';

    const listEl = document.getElementById('detailCicilanList');
    listEl.innerHTML = '';

    if (!data.cicilan.length) {

        listEl.innerHTML =
            '<p class="text-center text-gray-400 py-6">Tidak ada data cicilan</p>';

    } else {

        data.cicilan.forEach(function (c) {

            const isTerpilih = c.id === data.idCicilanTerpilih;

         let statusBadge = '';

if (c.status === 'lunas') {

    statusBadge =
    '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Lunas</span>';

}
else if (c.status === 'terlambat') {

    statusBadge =
    '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Terlambat</span>';

}
else {

    statusBadge =
    '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Belum Lunas</span>';

}

            const row = document.createElement('div');

            row.className =
                'flex items-center justify-between gap-4 p-4 rounded-2xl border ' +
                (isTerpilih
                    ? 'border-[#5628C7] bg-[#F5F3FE]'
                    : 'border-gray-100 bg-gray-50');

            row.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center font-bold text-[#5628C7]">
                        ${c.cicilanKe}
                    </div>
                    <div>
                       <p class="font-semibold text-gray-800">
    ${
        data.metode === 'cash'
            ? 'Pembayaran Penuh'
            : 'Cicilan ke-' + c.cicilanKe
    }
</p>

<p class="text-xs text-gray-500 mt-0.5">
    Jatuh Tempo: ${c.jatuhTempo}
</p>

${
    c.hariKeterlambatan > 0
    ? `<p class="text-xs text-red-600 font-semibold mt-1">
        ⚠ Terlambat ${c.hariKeterlambatan} hari
    </p>`
    : ''
}

${c.tanggalLunas
    ? `<p class="text-xs text-gray-500 mt-1">
        Tanggal Lunas: ${c.tanggalLunas}
    </p>`
    : ''
}
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-800 mb-1">${c.jumlah}</p>
                    ${statusBadge}
                    ${isTerpilih ? '<p class="text-xs font-semibold text-[#5628C7] mt-1">Sedang Divalidasi</p>' : ''}
                </div>
            `;

            listEl.appendChild(row);

        });

    }

    document.getElementById('detailModal').classList.remove('hidden');
}

document.addEventListener('click', function(e) {
    const btnDetail = e.target.closest('.btn-detail');
    if (btnDetail) {
        openDetailModal(btnDetail.dataset.id);
    }

    const btnTolak = e.target.closest('.btn-tolak');
    if (btnTolak) {
        openTolakModal(btnTolak.dataset.id);
    }
});

function closeDetailModal()
{
    document
        .getElementById('detailModal')
        .classList.add('hidden');
}

</script>

@endsection