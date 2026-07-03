@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-gray-500 mt-2">Informasi akun dan data diri kamu.</p>
</div>

<!-- HERO CARD -->
<div class="relative overflow-hidden rounded-3xl p-8 text-white mb-6" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
    <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-10 bg-white"></div>
    <div class="absolute -right-2 bottom-0 w-20 h-20 rounded-full opacity-10 bg-white"></div>
    <div class="relative z-10">

        <div class="flex items-center gap-6">

            <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center text-3xl font-black flex-shrink-0">
                {{ strtoupper(substr($agen->username, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-2xl font-black">{{ $agen->username }}</h2>
                <p class="text-white/70 text-sm mt-1">ID Agen: {{ $agen->id_agen_pp }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @if($agen->status == 'aktif')
                        <span class="inline-flex items-center gap-1.5 bg-green-400/20 text-green-200 border border-green-300/30 px-3 py-1 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-300 inline-block"></span> Aktif
                        </span>
                    @elseif($agen->status == 'pending')
                        <span class="inline-flex items-center gap-1.5 bg-yellow-400/20 text-yellow-200 border border-yellow-300/30 px-3 py-1 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-300 inline-block"></span> Pending
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-gray-400/20 text-gray-200 border border-gray-300/30 px-3 py-1 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 inline-block"></span> {{ ucfirst($agen->status) }}
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-1.5 bg-white/10 text-white/80 border border-white/20 px-3 py-1 rounded-full text-xs font-semibold">
                        Kredit: {{ ucfirst($agen->status_kredit ?? '-') }}
                    </span>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- PENGATURAN AKUN: EDIT PROFIL & GANTI PASSWORD -->
<div class="grid md:grid-cols-2 gap-6 mb-6">

    <!-- CARD: EDIT PROFIL -->
    <button type="button" onclick="openModal()"
        class="group bg-white rounded-2xl p-6 border border-purple-100 shadow-sm flex items-center justify-between gap-4 text-left hover:border-[#5628C7]/40 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#5628C7] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#5628C7] group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Edit Profil</h3>
                <p class="text-xs text-gray-400 mt-0.5">Perbarui data diri &amp; kontak kamu</p>
            </div>
        </div>
        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </div>
    </button>

    <!-- CARD: GANTI PASSWORD -->
    <button type="button" onclick="openPasswordModal()"
        class="group bg-white rounded-2xl p-6 border border-purple-100 shadow-sm flex items-center justify-between gap-4 text-left hover:border-blue-400/40 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Ganti Password</h3>
                <p class="text-xs text-gray-400 mt-0.5">Update kata sandi akun kamu</p>
            </div>
        </div>
        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </div>
    </button>

</div>

<!-- INFO GRID -->
<div class="grid md:grid-cols-2 gap-6 mb-6">

    <!-- DATA DIRI -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">
        <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
            <span class="w-2 h-2 rounded-full bg-[#5628C7]"></span>
            Data Diri
        </h2>
        <div class="space-y-4">

            <div class="flex items-center gap-3 py-3 border-b border-gray-50">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-400">Username</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $agen->username }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 py-3 border-b border-gray-50">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-400">No. Handphone</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $agen->no_hp }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 py-3 border-b border-gray-50">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-400">NIK</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $agen->nik }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3 py-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-400">Alamat</p>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $agen->alamat }}</p>
                </div>
            </div>

        </div>
    </div>

    <!-- DATA USAHA & KREDIT -->
    <div class="flex flex-col gap-6">

        <!-- Nama Usaha -->
        <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">
            <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Data Usaha
            </h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3 py-3 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Nama Usaha</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $agen->nama_usaha ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Terdaftar Sejak</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $agen->approved_at ? \Carbon\Carbon::parse($agen->approved_at)->format('d M Y') : 'Belum disetujui' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Kredit -->
        <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">
            <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                Info Kredit
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500">Limit Pinjaman</p>
                    </div>
                    <p class="text-sm font-bold text-[#5628C7]">Rp{{ number_format($agen->limit_pinjaman, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500">Status Kredit</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        @if($agen->status_kredit == 'terpercaya') bg-green-100 text-green-700
                        @elseif($agen->status_kredit == 'bermasalah') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700
                        @endif">
                        {{ ucfirst($agen->status_kredit ?? 'Baru') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- FOTO DOKUMEN -->
<div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">
    <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
        <span class="w-2 h-2 rounded-full bg-orange-400"></span>
        Dokumen
    </h2>
    <div class="grid md:grid-cols-3 gap-4">

        <div>
            <p class="text-xs text-gray-400 mb-2 font-medium">Foto KTP</p>
            <img src="{{ asset('uploads/'.$agen->foto_ktp) }}"
                onclick="showImage(this.src)"
                class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
        </div>

        <div>
            <p class="text-xs text-gray-400 mb-2 font-medium">Selfie KTP</p>
            <img src="{{ asset('uploads/'.$agen->foto_selfie_ktp) }}"
                onclick="showImage(this.src)"
                class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
        </div>

        <div>
            <p class="text-xs text-gray-400 mb-2 font-medium">Foto Toko</p>
            @if($agen->foto_toko_fisik)
                <img src="{{ asset('uploads/'.$agen->foto_toko_fisik) }}"
                    onclick="showImage(this.src)"
                    class="w-full h-44 object-cover rounded-2xl border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
            @else
                <div class="w-full h-44 rounded-2xl border border-dashed border-gray-200 flex items-center justify-center">
                    <p class="text-xs text-gray-400">Belum ada foto toko</p>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- IMAGE PREVIEW -->
<div id="imagePreview" class="hidden fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-5">
    <button type="button" onclick="closeImage()" class="absolute top-5 right-8 text-white text-5xl">&times;</button>
    <img id="previewImg" src="" class="max-w-[90%] max-h-[90%] rounded-2xl shadow-2xl">
</div>

<!-- MODAL EDIT PROFIL (logic & form tidak diubah) -->
<div id="modalEdit" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- HEADER GRADIENT -->
        <div class="relative px-8 py-6 text-white flex-shrink-0" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -right-8 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-tight">Edit Profil</h2>
                        <p class="text-white/70 text-xs mt-0.5">Perbarui data usaha &amp; kontak kamu</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal()" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center transition flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- BODY (scrollable) -->
        <div class="overflow-y-auto px-8 py-7">
            <form id="formEditProfil" action="/profil-agen/update" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">

                    <!-- Email -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <input
                                type="email"
                                name="email"
                                value="{{ $agen->user->email }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            No Handphone
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="no_hp"
                                value="{{ $agen->no_hp }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Alamat
                        </label>
                        <div class="relative">
                            <div class="absolute top-3.5 left-0 pl-4 flex items-start pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <textarea
                                name="alamat"
                                rows="3"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 leading-relaxed resize-none transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">{{ $agen->alamat }}</textarea>
                        </div>
                    </div>

                    <!-- Nama Usaha -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Nama Usaha
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#5628C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="nama_usaha"
                                value="{{ $agen->nama_usaha }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                    </div>

                    <!-- Foto Toko (custom dropzone) -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Foto Toko Fisik
                        </label>
                        <label for="fotoTokoInput"
                            class="group flex items-center gap-4 w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl px-4 py-4 cursor-pointer transition hover:border-[#5628C7]/40 hover:bg-purple-50/30">
                            <div class="w-11 h-11 rounded-xl bg-purple-100 text-[#5628C7] flex items-center justify-center flex-shrink-0 group-hover:bg-[#5628C7] group-hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 7.5L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate" id="fotoTokoLabel">Klik untuk unggah foto baru</p>
                                <p class="text-xs text-gray-400 mt-0.5">PNG atau JPG, maks 2MB</p>
                            </div>
                            <input id="fotoTokoInput" type="file" name="foto_toko_fisik" accept="image/*" class="hidden" onchange="document.getElementById('fotoTokoLabel').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah foto baru'">
                        </label>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100">
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="px-5 py-3 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-lg shadow-purple-200 transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<!-- MODAL GANTI PASSWORD (UI only, action masih placeholder, belum disambungkan ke controller) -->
<div id="modalPassword" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col">

        <!-- HEADER GRADIENT -->
        <div class="relative px-8 py-6 text-white flex-shrink-0" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -right-8 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-tight">Ganti Password</h2>
                        <p class="text-white/70 text-xs mt-0.5">Pastikan password baru kamu aman</p>
                    </div>
                </div>
                <button type="button" onclick="closePasswordModal()" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center transition flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

     <!-- BODY -->
        <div class="px-8 py-7">

            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-600 font-medium">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form id="formGantiPassword" action="/profil-agen/update-password" method="POST">   
                @csrf

                <div class="space-y-4">

                    <!-- Password Lama -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Password Lama
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password_lama"
                                placeholder="Masukkan password lama"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white">
                        </div>
                          @error('password_lama')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>  

                    <!-- Password Baru -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password_baru"
                                placeholder="Minimal 8 karakter"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white">
                        </div>
                             @error('password_baru')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                placeholder="Ulangi password baru"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white">
                        </div>
                            @error('password_baru_confirmation')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100">
                    <button
                        type="button"
                        onclick="closePasswordModal()"
                        class="px-5 py-3 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-lg shadow-blue-200 transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Simpan Password
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<script>
function showImage(src) {
    document.getElementById('previewImg').src = src;
    document.getElementById('imagePreview').classList.remove('hidden');
}
function closeImage() {
    document.getElementById('imagePreview').classList.add('hidden');
}
function openModal() {
    document.getElementById('modalEdit').classList.remove('hidden');
    document.getElementById('modalEdit').classList.add('flex');
}
function closeModal() {
    document.getElementById('modalEdit').classList.remove('flex');
    document.getElementById('modalEdit').classList.add('hidden');
}
function openPasswordModal() {
    document.getElementById('modalPassword').classList.remove('hidden');
    document.getElementById('modalPassword').classList.add('flex');
}
function closePasswordModal() {
    document.getElementById('modalPassword').classList.remove('flex');
    document.getElementById('modalPassword').classList.add('hidden');
}
</script>

@if($errors->any() || session('error') || session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        openPasswordModal();
    });
</script>
@endif

@endsection