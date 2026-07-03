@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-6">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('agen.index') }}" class="text-sm text-purple-600 hover:underline flex items-center gap-1">
            <i class="ti ti-arrow-left"></i> Kembali ke Data Agen
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Tambah Agen Baru</h1>
        <p class="text-sm text-gray-500">Form ini digunakan admin untuk mendaftarkan agen secara manual.</p>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('agen.store_admin') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf

        {{-- Data Akun --}}
        <div>
            <h2 class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-3">Data Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Agen PP</label>
                    <input type="text" name="id_agen_pp" value="{{ old('id_agen_pp') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>

        {{-- Data Diri --}}
        <div>
            <h2 class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-3">Data Diri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">{{ old('alamat') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha (opsional)</label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>

        {{-- Upload Dokumen --}}
        <div>
            <h2 class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-3">Dokumen</h2>
            <p class="text-xs text-gray-500 mb-3">
                Foto dikirim oleh agen melalui WA/Email, lalu diupload oleh admin di sini.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto KTP</label>
                    <input type="file" name="foto_ktp" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg cursor-pointer focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Selfie KTP</label>
                    <input type="file" name="foto_selfie_ktp" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg cursor-pointer focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Toko Fisik (opsional)</label>
                    <input type="file" name="foto_toko_fisik" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg cursor-pointer focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('agen.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 flex items-center gap-2">
                <i class="ti ti-user-plus"></i> Simpan Agen
            </button>
        </div>
    </form>
</div>
@endsection