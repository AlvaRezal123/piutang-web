@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Validasi Agen
        </h1>

        <p class="text-gray-500 mt-2">
            Kelola pendaftaran dan status agen Partner Pulsa.
        </p>

    </div>

</div>

<!-- CARD RINGKASAN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Agen Pending
        </p>

        <h2 class="text-3xl font-bold text-yellow-500 mt-3">
            {{ $agen->where('status','pending')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Agen Aktif
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-3">
            {{ $agen->where('status','aktif')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Agen Ditolak
        </p>

        <h2 class="text-3xl font-bold text-red-500 mt-3">
            {{ $agen->where('status','ditolak')->count() }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Agen Diblokir
        </p>

        <h2 class="text-3xl font-bold text-gray-600 mt-3">
            {{ $agen->where('status','diblokir')->count() }}
        </h2>

    </div>

</div>


<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

<div class="flex justify-between items-center mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        Data Agen
    </h2>

    <div class="flex gap-3">

        <input
            type="text"
            id="searchUsername"
            placeholder="🔍 Cari username..."
            class="border border-gray-300 rounded-xl px-4 py-2 w-64">

        <select
            id="filterStatus"
            class="border border-gray-300 rounded-xl px-4 py-2">

            <option value="all">
                Semua Status
            </option>

            <option value="pending">
                Pending
            </option>

            <option value="aktif">
                Aktif
            </option>

            <option value="ditolak">
                Ditolak
            </option>

            <option value="diblokir">
                Diblokir
            </option>

        </select>

    </div>

</div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-4">
                        ID Agen
                    </th>

                    <th class="text-left py-4">
                        Username
                    </th>

                    <th class="text-left py-4">
                        Nama Usaha
                    </th>

                    <th class="text-left py-4">
                        No HP
                    </th>

                    <th class="text-left py-4">
                        Status
                    </th>

                    <th class="text-left py-4">
                        Dokumen
                    </th>

                    <th class="text-left py-4">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($agen as $a)

                <tr
                class="border-b agen-row"
                data-username="{{ strtolower($a->username) }}"
                data-status="{{ strtolower($a->status) }}">

                
                    <td class="py-4">
                        {{ $a->id_agen_pp }}
                    </td>

                    <td>
                        {{ $a->username }}
                    </td>

                    <td>
                        {{ $a->nama_usaha ?? '-' }}
                    </td>

                    <td>
                        {{ $a->no_hp }}
                    </td>

                    <td>

                        @if($a->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Pending
                            </span>

                        @elseif($a->status == 'aktif')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Aktif
                            </span>

                        @elseif($a->status == 'ditolak')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Ditolak
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Diblokir
                            </span>

                        @endif

                    </td>

                    <td>

                       <button
    onclick="openModal('modal{{ $a->id }}')"
    class="bg-purple-100 text-[#5628C7] px-4 py-2 rounded-xl text-sm font-semibold">

    Detail

</button>

                    </td>

<td>

    <div class="flex flex-wrap gap-2">

        @if($a->status == 'pending')

            <a
                href="/agen/setujui/{{ $a->id }}"
                class="bg-green-500 text-white px-4 py-2 rounded-xl text-sm">

                Setujui

            </a>

            <button
                type="button"
                onclick="openTolakModal({{ $a->id }})"
                class="bg-red-500 text-white px-4 py-2 rounded-xl text-sm">

                Tolak

            </button>

        @elseif($a->status == 'aktif')

            <a
                href="/agen/blokir/{{ $a->id }}"
                onclick="return confirm('Yakin ingin memblokir agen ini?')"
                class="bg-gray-700 text-white px-4 py-2 rounded-xl">

                Blokir

            </a>

        @elseif($a->status == 'diblokir')

            <a
                href="/agen/aktifkan/{{ $a->id }}"
                onclick="return confirm('Yakin ingin mengaktifkan agen ini kembali?')"
                class="bg-green-600 text-white px-4 py-2 rounded-xl">

                Aktifkan

            </a>

        @else

            -

        @endif

    </div>

</td>

                </tr>

                <!-- MODAL -->
                <div
                    id="modal{{ $a->id }}"
                    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-6">

                    <div class="bg-white rounded-[32px] p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-purple-100">
            <form
    action="/agen/update/{{ $a->id }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
                  <div class="flex justify-between items-center mb-6 pb-4 border-b">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Detail Agen
        </h2>

        <p class="text-sm text-gray-500">
            Informasi dan dokumen agen
        </p>
    </div>

    <button
        onclick="closeModal('modal{{ $a->id }}')"
        class="w-10 h-10 rounded-full hover:bg-gray-100 text-xl">
        ✕
    </button>

</div>
<div class="bg-purple-50 border border-purple-100 rounded-2xl p-4 mb-6">

    <h3 class="font-bold text-lg text-purple-700">
        {{ $a->username }}
    </h3>

    <p class="text-gray-600">
        ID Agen : {{ $a->id_agen_pp }}
    </p>
     <p>
    Terdaftar Sejak :

    @if($a->approved_at)

        {{ \Carbon\Carbon::parse($a->approved_at)->format('d M Y') }}

    @else

        Belum Disetujui

    @endif

</p>

</div>

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-sm text-gray-500">
                                    Username
                                </p>

                             <input
    type="text"
    name="username"
    value="{{ $a->username }}"
    class="w-full border border-gray-300 rounded-xl p-3">
                            </div>

                            <div>

                                <p class="text-sm text-gray-500">
                                    Nama Usaha
                                </p>

                               <input
    type="text"
    name="nama_usaha"
    value="{{ $a->nama_usaha }}"
    class="w-full border border-gray-300 rounded-xl p-3">

                            </div>

                            <div>

                                <p class="text-sm text-gray-500">
                                    NIK
                                </p>

                          <input
    type="text"
    value="{{ $a->nik }}"
    readonly
    class="w-full border border-gray-300 rounded-xl p-3 bg-gray-100">

                            </div>

                            <div>
   <p class="text-sm text-gray-500">
                                    No Handphone    
                                </p>
                          <input
    type="text"
    name="no_hp"
    value="{{ $a->no_hp }}"
    class="w-full border border-gray-300 rounded-xl p-3">
                            

                            </div>

                        </div>

                        <div class="mt-6">

                            <p class="text-sm text-gray-500 mb-2">
                                Alamat
                            </p>

                        <textarea
    name="alamat"
    rows="4"
    class="w-full border border-gray-300 rounded-xl p-3">{{ $a->alamat }}</textarea>

                        </div>

                        <div class="grid md:grid-cols-3 gap-6 mt-8">

                            <div>

                                <p class="font-semibold mb-2">
                                    Foto KTP
                                </p>

                           <img
    src="{{ asset('uploads/'.$a->foto_ktp) }}"
    onclick="showImage(this.src)"
    class="w-full h-64 object-cover rounded-2xl border shadow-sm cursor-pointer">

                            </div>

                            <div>

                                <p class="font-semibold mb-2">
                                    Selfie KTP
                                </p>

                    <img
    src="{{ asset('uploads/'.$a->foto_selfie_ktp) }}"
    onclick="showImage(this.src)"
    class="w-full h-64 object-cover rounded-2xl border shadow-sm cursor-pointer">

                            </div>

                            <div>

                                <p class="font-semibold mb-2">
                                    Foto Toko
                                </p>

                                @if($a->foto_toko_fisik)

                           <img
    src="{{ asset('uploads/'.$a->foto_toko_fisik) }}"
    onclick="showImage(this.src)"
    class="w-full h-64 object-cover rounded-2xl border shadow-sm cursor-pointer">
                                @else

                                    <div class="text-gray-400">
                                        Tidak ada foto
                                    </div>
                                @endif
                                <div class="mt-3">

    <input
        type="file"
        name="foto_toko_fisik"
        class="w-full border border-gray-300 rounded-xl p-2">

</div>
</div>
                            </div>
<div class="flex justify-end gap-3 mt-8">
    <button
        type="button"
        onclick="closeModal('modal{{ $a->id }}')"
        class="px-5 py-3 border rounded-xl">
        Batal
    </button>
    <button
        type="submit"
        class="bg-[#5628C7] text-white px-6 py-3 rounded-xl">
        Simpan Perubahan
    </button>
    
</div>
</form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-500">
                        Belum ada data agen
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

function openModal(id)
{
    document.getElementById(id)
    .classList.remove('hidden');
}

function closeModal(id)
{
    document.getElementById(id)
    .classList.add('hidden');
}
const searchInput =
    document.getElementById(
        'searchUsername'
    );

const statusFilter =
    document.getElementById(
        'filterStatus'
    );

function openTolakModal(id)
{
    document
        .getElementById('formTolak')
        .action =
        '/agen/simpan-tolak/' + id;

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

function filterAgen()
{
    const keyword =
        searchInput.value.toLowerCase();

    const status =
        statusFilter.value;

    document
    .querySelectorAll('.agen-row')
    .forEach(function(row){

        const username =
            row.dataset.username;

        const rowStatus =
            row.dataset.status;

        const cocokNama =
            username.includes(keyword);

        const cocokStatus =
            status === 'all'
            || rowStatus === status;

        row.style.display =
            cocokNama && cocokStatus
            ? ''
            : 'none';

    });
}
function showImage(src)
{
    document.getElementById('previewImg').src = src;
    document.getElementById('imagePreview').classList.remove('hidden');
}

function closeImage()
{
    document.getElementById('imagePreview').classList.add('hidden');
}
searchInput.addEventListener(
    'keyup',
    filterAgen
);

statusFilter.addEventListener(
    'change',
    filterAgen
);
</script>
<div
    id="imagePreview"
    class="hidden fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-5">

    <button
        type="button"
        onclick="closeImage()"
        class="absolute top-5 right-8 text-white text-5xl">
        &times;
    </button>

    <img
        id="previewImg"
        src=""
        class="max-w-[90%] max-h-[90%] rounded-2xl shadow-2xl">
</div>

<!-- MODAL TOLAK AGEN -->
<div
    id="tolakModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">

        <h2 class="text-xl font-bold mb-2">
            Alasan Penolakan Agen
        </h2>

        <p class="text-gray-500 mb-4">
            Masukkan alasan penolakan pendaftaran agen.
        </p>

        <form
            id="formTolak"
            method="POST">

            @csrf

            <textarea
                name="alasan_penolakan"
                rows="4"
                required
                class="w-full border border-gray-300 rounded-xl p-3"
                placeholder="Contoh: Foto KTP kurang jelas"></textarea>

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

                    Tolak Agen

                </button>

            </div>

        </form>

    </div>

</div>
@endsection