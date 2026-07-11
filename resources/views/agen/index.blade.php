@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Validasi Agen</h1>
        <p class="text-gray-500 mt-2">Kelola pendaftaran dan status agen Partner Pulsa.</p>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('referensi.index') }}"
            class="inline-flex items-center gap-2 bg-white border border-purple-200 hover:bg-purple-50 text-[#5628C7] px-4 py-2.5 rounded-xl text-sm font-semibold transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Data Referensi
        </a>

        <button type="button" onclick="openImportModal()"
            class="inline-flex items-center gap-2 bg-[#5628C7] hover:bg-[#4b22b0] text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
            </svg>
            Import Excel
        </button>
    </div>
</div>


<!-- STATISTIK -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- Agen Pending -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#EF9F27]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FAEEDA] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#854F0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Agen Pending</p>
        <h2 class="text-3xl font-black text-[#BA7517] mt-1">{{ $agen->where('status','pending')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Menunggu persetujuan</p>
    </div>

    <!-- Agen Aktif -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#639922]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#3B6D11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Agen Aktif</p>
        <h2 class="text-3xl font-black text-[#639922] mt-1">{{ $agen->where('status','aktif')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Siap bertransaksi</p>
    </div>

    <!-- Agen Ditolak -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#E24B4A]"></div>
        <div class="w-9 h-9 rounded-lg bg-[#FCEBEB] flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A32D2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Agen Ditolak</p>
        <h2 class="text-3xl font-black text-[#A32D2D] mt-1">{{ $agen->where('status','ditolak')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Pengajuan ditolak</p>
    </div>

    <!-- Agen Diblokir -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl bg-[#6B7280]"></div>
        <div class="w-9 h-9 rounded-lg bg-gray-200 flex items-center justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-.75 0h10.5a.75.75 0 01.75.75v8.25a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75v-8.25a.75.75 0 01.75-.75z"/>
            </svg>
        </div>
        <p class="text-xs uppercase tracking-wide font-bold text-black">Agen Diblokir</p>
        <h2 class="text-3xl font-black text-gray-700 mt-1">{{ $agen->where('status','diblokir')->count() }}</h2>
        <p class="text-xs text-gray-400 mt-1.5">Akses dinonaktifkan</p>
    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Data Agen</h2>
        <div class="flex gap-3">
            <div class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-2 w-64 bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input type="text" id="searchUsername" placeholder="Cari username..." class="outline-none text-sm w-full">
            </div>
            <select id="filterStatus" class="border border-gray-300 rounded-xl px-4 py-2 text-sm">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="aktif">Aktif</option>
                <option value="ditolak">Ditolak</option>
                <option value="diblokir">Diblokir</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">ID Agen</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Username</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Nama Usaha</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">No HP</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Status</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Dokumen</th>
                    <th class="text-left py-3 text-xs uppercase tracking-wide text-gray-400 font-semibold px-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agen as $a)
                <tr class="border-b agen-row hover:bg-gray-50 transition"
                    data-username="{{ strtolower($a->username) }}"
                    data-status="{{ strtolower($a->status) }}">

                    <td class="py-4 px-2 text-sm text-gray-500">{{ $a->id_agen_pp }}</td>

                    <td class="py-4 px-2">
                        <span class="font-semibold text-gray-800 text-sm">{{ $a->username }}</span>
                    </td>

                    <td class="py-4 px-2 text-sm text-gray-600">{{ $a->nama_usaha ?? '-' }}</td>

                    <td class="py-4 px-2 text-sm text-gray-600">{{ $a->no_hp }}</td>

                    <td class="py-4 px-2">
                        @if($a->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span> Pending
                            </span>
                        @elseif($a->status == 'aktif')
                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Aktif
                            </span>
                        @elseif($a->status == 'ditolak')
                            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 border border-gray-200 px-3 py-1 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500 inline-block"></span> Diblokir
                            </span>
                        @endif
                    </td>

                    <td class="py-4 px-2">
                        <button onclick="openModal('modal{{ $a->id }}')"
                            class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 text-[#5628C7] px-4 py-2 rounded-xl text-xs font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </button>
                    </td>

                    <td class="py-4 px-2">
                        <div class="flex flex-wrap gap-2">
                            @if($a->status == 'pending')
                                <a href="/agen/setujui/{{ $a->id }}"
                                    onclick="return confirm('Yakin ingin menyetujui agen ini?')"
                                    class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Setujui
                                </a>
                                <button type="button" onclick="openTolakModal({{ $a->id }})"
                                    class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak
                                </button>
                            @elseif($a->status == 'aktif')
                                <a href="/agen/blokir/{{ $a->id }}"
                                    onclick="return confirm('Yakin ingin memblokir agen ini?')"
                                    class="inline-flex items-center gap-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Blokir
                                </a>
                            @elseif($a->status == 'diblokir')
                                <a href="/agen/aktifkan/{{ $a->id }}"
                                    onclick="return confirm('Yakin ingin mengaktifkan agen ini kembali?')"
                                    class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Aktifkan
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- MODAL DETAIL -->
                <div id="modal{{ $a->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-6">
                    <div class="bg-white rounded-3xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-purple-100">

                        <form action="/agen/update/{{ $a->id }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Modal Header -->
                            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Detail Agen</h2>
                                    <p class="text-sm text-gray-500 mt-0.5">Informasi dan dokumen agen</p>
                                </div>
                                <button type="button" onclick="closeModal('modal{{ $a->id }}')"
                                    class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Info Banner -->
                            <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4 mb-6 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#5628C7] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($a->username, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-purple-800">{{ $a->username }}</h3>
                                    <p class="text-sm text-gray-500">ID: {{ $a->id_agen_pp }} &nbsp;·&nbsp;
                                        Terdaftar:
                                        @if($a->approved_at)
                                            {{ \Carbon\Carbon::parse($a->approved_at)->format('d M Y') }}
                                        @else
                                            Belum disetujui
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">Username</label>
                                    <input type="text" name="username" value="{{ $a->username }}"
                                        class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">Nama Usaha</label>
                                    <input type="text" name="nama_usaha" value="{{ $a->nama_usaha }}"
                                        class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">NIK</label>
                                    <input type="text" value="{{ $a->nik }}" readonly
                                        class="w-full border border-gray-200 rounded-xl p-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">No Handphone</label>
                                    <input type="text" name="no_hp" value="{{ $a->no_hp }}"
                                        class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition">
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">Alamat</label>
                                <textarea name="alamat" rows="3"
                                    class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition resize-none">{{ $a->alamat }}</textarea>
                            </div>

                            <!-- Dokumen -->
                            <div class="mt-7">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Dokumen & Foto</p>
                                <div class="grid md:grid-cols-3 gap-4">

                                    <!-- Foto KTP -->
                                    <div class="flex flex-col gap-2">
                                        <p class="text-sm font-semibold text-gray-700">Foto KTP</p>
                                        <img src="{{ asset('uploads/'.$a->foto_ktp) }}"
                                            onclick="showImage(this.src)"
                                            class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
                                    </div>

                                    <!-- Selfie KTP -->
                                    <div class="flex flex-col gap-2">
                                        <p class="text-sm font-semibold text-gray-700">Selfie KTP</p>
                                        <img src="{{ asset('uploads/'.$a->foto_selfie_ktp) }}"
                                            onclick="showImage(this.src)"
                                            class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
                                    </div>

                                    <!-- Foto Toko -->
                                    <div class="flex flex-col gap-2">
                                        <p class="text-sm font-semibold text-gray-700">Foto Toko</p>

                                        @if($a->foto_toko_fisik)
                                            <img src="{{ asset('uploads/'.$a->foto_toko_fisik) }}"
                                                onclick="showImage(this.src)"
                                                class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition mb-2">
                                        @endif

                                        <label for="foto_toko_{{ $a->id }}"
                                            class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-purple-200 rounded-2xl p-4 cursor-pointer hover:bg-purple-50 hover:border-purple-400 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                            <span class="text-xs font-semibold text-purple-600">Pilih Foto Baru</span>
                                            <span id="namaFile{{ $a->id }}" class="text-xs text-gray-400 text-center">Belum ada file dipilih</span>
                                        </label>
                                        <input type="file" id="foto_toko_{{ $a->id }}" name="foto_toko_fisik" class="hidden"
                                            onchange="document.getElementById('namaFile{{ $a->id }}').innerHTML='✅ '+this.files[0].name">
                                    </div>

                                </div>
                            </div>

                            <!-- Footer Buttons -->
                            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                                <button type="button" onclick="closeModal('modal{{ $a->id }}')"
                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="bg-[#5628C7] hover:bg-[#4b22b0] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                                    Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-500">Belum ada data agen</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- IMAGE PREVIEW -->
<div id="imagePreview" class="hidden fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-5">
    <button type="button" onclick="closeImage()" class="absolute top-5 right-8 text-white text-5xl">&times;</button>
    <img id="previewImg" src="" class="max-w-[90%] max-h-[90%] rounded-2xl shadow-2xl">
</div>

<!-- MODAL TOLAK AGEN -->
<div id="tolakModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Tolak Pendaftaran Agen</h2>
                <p class="text-sm text-gray-500">Masukkan alasan penolakan agar agen dapat memperbaiki pengajuannya.</p>
            </div>
        </div>
        <form id="formTolak" method="POST">
            @csrf
            <textarea name="alasan_penolakan" rows="4" required
                class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400 transition resize-none"
                placeholder="Contoh: Foto KTP kurang jelas, mohon upload ulang dengan pencahayaan yang baik."></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeTolakModal()"
                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    Tolak Agen
                </button>
            </div>
        </form>
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
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
function openTolakModal(id) {
    document.getElementById('formTolak').action = '/agen/simpan-tolak/' + id;
    document.getElementById('tolakModal').classList.remove('hidden');
}
function closeTolakModal() { document.getElementById('tolakModal').classList.add('hidden'); }
function showImage(src) {
    document.getElementById('previewImg').src = src;
    document.getElementById('imagePreview').classList.remove('hidden');
}
function closeImage() { document.getElementById('imagePreview').classList.add('hidden'); }
function openImportModal() { document.getElementById('importModal').classList.remove('hidden'); }
function closeImportModal() { document.getElementById('importModal').classList.add('hidden'); }

const searchInput = document.getElementById('searchUsername');
const statusFilter = document.getElementById('filterStatus');

function filterAgen() {
    const keyword = searchInput.value.toLowerCase();
    const status = statusFilter.value;
    document.querySelectorAll('.agen-row').forEach(function(row) {
        const cocokNama = row.dataset.username.includes(keyword);
        const cocokStatus = status === 'all' || row.dataset.status === status;
        row.style.display = cocokNama && cocokStatus ? '' : 'none';
    });
}
searchInput.addEventListener('keyup', filterAgen);
statusFilter.addEventListener('change', filterAgen);
</script>

@endsection