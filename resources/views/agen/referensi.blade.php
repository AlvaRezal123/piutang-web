@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">
    <div>
        <div class="flex items-center gap-3">
            <a href="/agen"
                class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Data Referensi Agen PP</h1>
        </div>
        <p class="text-gray-500 mt-2 ml-12">Daftar ID Agen PP yang sudah diimport dan siap dipakai untuk registrasi.</p>
    </div>

    <button type="button" onclick="openImportModal()"
        class="inline-flex items-center gap-2 bg-[#5628C7] hover:bg-[#4b22b0] text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
        </svg>
        Import Excel
    </button>
</div>

<!-- STATISTIK -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

    <!-- Total Referensi -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#5628C7]"></div>
        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Total Referensi</p>
        <h2 class="text-3xl font-black text-[#5628C7] mt-1">{{ $referensi->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">ID Agen PP terdaftar</p>
    </div>

    <!-- Ditambahkan Hari Ini -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#639922]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#3B6D11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Ditambahkan Hari Ini</p>
        <h2 class="text-3xl font-black text-[#639922] mt-1">{{ $referensi->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Dari import terbaru</p>
    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Referensi</h2>
        <div class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-2 w-64 bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" id="searchReferensi" placeholder="Cari ID Agen PP / username..." class="outline-none text-sm w-full">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">ID Agen PP</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Username</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">No HP</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Alamat</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Ditambahkan</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referensi as $r)
                <tr class="border-b referensi-row hover:bg-gray-50 transition"
                    data-idagen="{{ strtolower($r->id_agen_pp) }}"
                    data-username="{{ strtolower($r->username) }}">

                    <td class="py-4 px-2">
                        <span class="font-semibold text-gray-800 text-sm">{{ $r->id_agen_pp }}</span>
                    </td>

                    <td class="py-4 px-2 text-sm text-gray-600">{{ $r->username }}</td>

                    <td class="py-4 px-2 text-sm text-gray-600">{{ $r->no_hp }}</td>

                    <td class="py-4 px-2 text-sm text-gray-600 max-w-xs truncate" title="{{ $r->alamat }}">
                        {{ $r->alamat }}
                    </td>

                    <td class="py-4 px-2 text-sm text-gray-500">
                        {{ $r->created_at ? $r->created_at->format('d M Y') : '-' }}
                    </td>

                    <td class="py-4 px-2">
                        <form action="{{ route('referensi.destroy', $r->id_agen_pp) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data referensi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-xl text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">
                        Belum ada data referensi. Silakan import file Excel terlebih dahulu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL IMPORT EXCEL REFERENSI AGEN PP -->
<div id="importModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Import Data Referensi Agen PP</h2>
                <p class="text-sm text-gray-500">Upload file Excel berisi daftar ID Agen PP yang valid.</p>
            </div>
        </div>

        @if(session('errorImport'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl p-3 mb-4">
                {{ session('errorImport') }}
            </div>
        @endif

        <form action="{{ route('import.referensi.agen.pp') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="fileReferensi"
                class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-purple-200 rounded-2xl p-6 cursor-pointer hover:bg-purple-50 hover:border-purple-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                <span class="text-sm font-semibold text-purple-600">Pilih File Excel</span>
                <span id="namaFileImport" class="text-xs text-gray-400 text-center">Format .xlsx atau .csv</span>
            </label>

            <input type="file" id="fileReferensi" name="file" accept=".xlsx,.xls,.csv" class="hidden" required
                onchange="document.getElementById('namaFileImport').innerHTML='✅ '+this.files[0].name">
                <!-- METODE IMPORT -->
<div class="mt-6">

    <label class="block text-sm font-semibold text-gray-700 mb-3">
        Metode Import
    </label>

    <!-- Opsi 1 -->
    <label
        class="flex items-start gap-3 p-4 rounded-2xl border border-gray-200 hover:border-[#5628C7] hover:bg-purple-50 cursor-pointer transition">

        <input
            type="radio"
            name="metode_import"
            value="replace"
            checked
            class="mt-1 text-[#5628C7]">

        <div>

            <p class="font-semibold text-gray-800">
                Ganti Seluruh Data Referensi
            </p>

            <p class="text-sm text-gray-500 mt-1">

                Seluruh data referensi lama akan dihapus,
                kemudian diganti sesuai file Excel terbaru.

            </p>

        </div>

    </label>

    <!-- Opsi 2 -->
    <label
        class="flex items-start gap-3 p-4 rounded-2xl border border-gray-200 hover:border-[#5628C7] hover:bg-purple-50 cursor-pointer transition mt-3">

        <input
            type="radio"
            name="metode_import"
            value="sync"
            class="mt-1 text-[#5628C7]">

        <div>

            <p class="font-semibold text-gray-800">
                Sinkronisasi Data Referensi
            </p>

            <p class="text-sm text-gray-500 mt-1">

                Memperbarui data lama,
                menambahkan data baru,
                serta menghapus data yang sudah tidak ada
                pada file Excel terbaru.

            </p>

        </div>

    </label>

</div>

            <div class="flex justify-end gap-3 mt-5">
                <button type="button" onclick="closeImportModal()"
                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-[#5628C7] hover:bg-[#4b22b0] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    Import Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() { document.getElementById('importModal').classList.remove('hidden'); }
function closeImportModal() { document.getElementById('importModal').classList.add('hidden'); }

const searchReferensi = document.getElementById('searchReferensi');

searchReferensi.addEventListener('keyup', function() {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.referensi-row').forEach(function(row) {
        const cocok = row.dataset.idagen.includes(keyword) || row.dataset.username.includes(keyword);
        row.style.display = cocok ? '' : 'none';
    });
});
</script>

@endsection