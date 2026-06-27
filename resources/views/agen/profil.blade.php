@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-gray-500 mt-2">Informasi akun dan data diri kamu.</p>
</div>

<!-- BANNER INFO -->
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4 mb-6">
    <div class="flex-shrink-0 mt-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
        </svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-semibold text-blue-700">Data tidak bisa diubah sendiri</p>
        <p class="text-sm text-blue-600 mt-0.5">Jika ingin mengubah data profil, silakan hubungi admin melalui WhatsApp.</p>
        <a href="https://wa.me/628997911040?text=Halo%20Admin%20SIMPAN,%20saya%20ingin%20mengubah%20data%20profil%20saya%20atas%20nama%20{{ urlencode($agen->username) }}%20(ID:%20{{ $agen->id_agen_pp }})."
            target="_blank"
            class="inline-flex items-center gap-2 mt-3 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.135 1.528 5.887L.057 23.882a.75.75 0 00.920.943l6.184-1.622A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.907 0-3.698-.497-5.25-1.367l-.371-.214-3.924 1.029 1.004-3.805-.234-.381A9.96 9.96 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
            </svg>
            Hubungi Admin via WhatsApp
        </a>
    </div>
</div>

<!-- HERO CARD -->
<div class="relative overflow-hidden rounded-3xl p-8 text-white mb-6" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
    <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-10 bg-white"></div>
    <div class="absolute -right-2 bottom-0 w-20 h-20 rounded-full opacity-10 bg-white"></div>
    <div class="relative z-10 flex items-center gap-6">
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

<script>
function showImage(src) {
    document.getElementById('previewImg').src = src;
    document.getElementById('imagePreview').classList.remove('hidden');
}
function closeImage() {
    document.getElementById('imagePreview').classList.add('hidden');
}
</script>

@endsection