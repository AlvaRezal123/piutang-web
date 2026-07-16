@extends('layouts.admin')

@section('content')

<div class="flex items-start justify-between mb-8">
    <div>
        <h1 class="text-4xl font-bold text-gray-800">Periode Cicilan</h1>
        <p class="text-gray-500 mt-2">Kelola daftar periode cicilan yang dapat dipilih oleh agen.</p>
    </div>

    <button
        onclick="document.getElementById('modalTambah').classList.remove('hidden'); document.getElementById('modalTambah').classList.add('flex');"
        class="bg-[#5628C7] hover:bg-[#4722a8] text-white px-6 py-3 rounded-2xl shadow-sm flex items-center gap-2">
        <i class="ti ti-plus text-xl"></i>
        Tambah Periode
    </button>
</div>

<!-- CARD -->
<div class="grid md:grid-cols-2 gap-6 mb-8">

    <!-- TOTAL -->
    <div class="bg-white rounded-3xl border border-purple-100 shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center">
                <i class="ti ti-calendar-time text-3xl text-[#5628C7]"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase">Total Periode</p>
                <h2 class="text-4xl font-bold text-[#5628C7]">{{ $periode->count() }}</h2>
                <p class="text-sm text-gray-400 mt-1">Periode terdaftar</p>
            </div>
        </div>
    </div>

    <!-- AKTIF -->
    <div class="bg-white rounded-3xl border border-green-100 shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                <i class="ti ti-circle-check text-3xl text-green-600"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase">Periode Aktif</p>
                <h2 class="text-4xl font-bold text-green-600">{{ $periode->where('status','aktif')->count() }}</h2>
                <p class="text-sm text-gray-400 mt-1">Dapat dipilih agen</p>
            </div>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="bg-white rounded-3xl border border-purple-100 shadow-sm">

    <div class="flex items-center justify-between p-7 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Periode Cicilan</h2>
        <input
            type="text"
            placeholder="Cari periode..."
            class="border rounded-2xl px-5 py-3 w-72 focus:outline-none focus:ring-2 focus:ring-purple-500">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-gray-400 text-sm uppercase">
                    <th class="text-left px-8 py-5">Nama Periode</th>
                    <th class="text-left">Jumlah Bulan</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periode as $p)
                    <tr class="border-t">
                        <td class="px-8 py-5 font-semibold">{{ $p->nama_periode }}</td>
                        <td>{{ $p->jumlah_bulan }}</td>
                        <td>
                            @if($p->status == 'aktif')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $p->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    onclick="bukaModalEdit('{{ $p->id }}', '{{ $p->jumlah_bulan }}', '{{ $p->status }}')"
                                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded-xl">
                                    <i class="ti ti-edit"></i>
                                </button>

                                <a
                                    href="/admin/periode-cicilan/delete/{{ $p->id }}"
                                    onclick="return confirm('Hapus periode ini?')"
                                    class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-2 rounded-xl">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">Belum ada periode cicilan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modalTambah" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">

        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Periode Cicilan</h2>
            <button onclick="tutupModalTambah()" class="text-gray-400 hover:text-gray-600">
                <i class="ti ti-x text-3xl"></i>
            </button>
        </div>

        <form action="/admin/periode-cicilan/store" method="POST">
            @csrf

            <div class="p-6 space-y-5">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Jumlah Bulan</label>
                    <input
                        type="number"
                        name="jumlah_bulan"
                        min="1"
                        required
                        placeholder="Contoh : 6"
                        class="w-full rounded-2xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-[#5628C7] outline-none">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-[#5628C7]">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-6 border-t">
                <button type="button" onclick="tutupModalTambah()" class="px-5 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#5628C7] hover:bg-[#4722a8] text-white">
                    <i class="ti ti-device-floppy mr-1"></i>
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL EDIT (dipindah keluar dari modalTambah, sekarang sibling, bukan nested) -->
<div id="modalEdit" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">

        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Edit Periode Cicilan</h2>
            <button type="button" onclick="tutupModalEdit()" class="text-gray-400 hover:text-gray-600">
                <i class="ti ti-x text-3xl"></i>
            </button>
        </div>

        <form id="formEdit" method="POST">
            @csrf

            <div class="p-6 space-y-5">
                <div>
                    <label class="block mb-2 font-semibold text-gray-500">Jumlah Bulan</label>
                    <div id="editJumlahText" class="w-full rounded-2xl border bg-gray-50 px-4 py-3 text-gray-700 font-semibold">
                        -
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Jumlah bulan tidak dapat diubah. Buat periode baru jika perlu nilai lain.</p>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Status</label>
                    <select id="editStatus" name="status" class="w-full rounded-2xl border px-4 py-3">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-6 border-t">
                <button type="button" onclick="tutupModalEdit()" class="px-5 py-3 rounded-2xl bg-gray-100">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#5628C7] text-white">
                    <i class="ti ti-device-floppy mr-1"></i>
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function tutupModalTambah() {
    const modal = document.getElementById('modalTambah');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function bukaModalEdit(id, bulan, status) {
    document.getElementById('formEdit').action = '/admin/periode-cicilan/update/' + id;
    document.getElementById('editJumlahText').textContent = bulan + ' Bulan';
    document.getElementById('editStatus').value = status;

    const modal = document.getElementById('modalEdit');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function tutupModalEdit() {
    const modal = document.getElementById('modalEdit');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>

@endsection