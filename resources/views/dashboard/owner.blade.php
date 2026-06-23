@extends('layouts.owner')

@section('content')

<!-- HEADER -->
<div class="bg-white rounded-3xl p-8 shadow-sm border border-purple-100">

    <h1 class="text-3xl font-black text-gray-800">
        Halo Owner 👋
    </h1>

    <p class="text-gray-500 mt-2">
        Kelola persetujuan pengajuan hutang dan monitoring seluruh transaksi SIMPAN.
    </p>

</div>

<!-- CARD STATISTIK -->
<div class="grid md:grid-cols-4 gap-6 mt-8">

    <!-- Total Pengajuan -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="text-4xl mb-4">
            📄
        </div>

        <p class="text-gray-500 text-sm">
            Total Pengajuan
        </p>

        <h2 class="text-3xl font-black text-[#5628C7] mt-2">
          {{ $totalPengajuan }}
        </h2>

    </div>

    <!-- Pending -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="text-4xl mb-4">
            ⏳
        </div>

        <p class="text-gray-500 text-sm">
            Menunggu Persetujuan
        </p>

        <h2 class="text-3xl font-black text-yellow-500 mt-2">
        {{ $pending }}
        </h2>

    </div>

    <!-- Disetujui -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="text-4xl mb-4">
            ✅
        </div>

        <p class="text-gray-500 text-sm">
            Disetujui
        </p>

        <h2 class="text-3xl font-black text-green-500 mt-2">
         {{ $disetujui }}
        </h2>

    </div>

    <!-- Ditolak -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="text-4xl mb-4">
            ❌
        </div>

        <p class="text-gray-500 text-sm">
            Ditolak
        </p>

        <h2 class="text-3xl font-black text-red-500 mt-2">
          {{ $ditolak }}
        </h2>

    </div>

</div>

<!-- QUICK MENU -->
<div class="grid md:grid-cols-2 gap-6 mt-8">

    <!-- Approval -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <h2 class="font-bold text-lg text-gray-800">
            📋 Approval Pengajuan Hutang
        </h2>

        <p class="text-gray-500 mt-2">
            Review dan validasi seluruh pengajuan hutang dari agen.
        </p>

        <a
            href="/owner/hutang"
            class="inline-block mt-5 bg-[#5628C7] text-white px-5 py-3 rounded-xl font-semibold">

            Buka Menu →

        </a>

    </div>

    <!-- Monitoring -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <h2 class="font-bold text-lg text-gray-800">
            📊 Monitoring Hutang
        </h2>

        <p class="text-gray-500 mt-2">
            Pantau seluruh hutang yang sedang berjalan maupun yang telah lunas.
        </p>

        <a
            href="/admin/hutang"
            class="inline-block mt-5 bg-[#5628C7] text-white px-5 py-3 rounded-xl font-semibold">

            Buka Menu →

        </a>

    </div>

</div>

<!-- AKTIVITAS -->
<div class="space-y-4">

    @forelse($aktivitas as $a)

        <div class="border-l-4 border-purple-500 pl-4">

           <p class="font-semibold">
    {{ $a->agen->username }}
    mengajukan pinjaman sebesar
    Rp{{ number_format($a->jumlah_hutang,0,',','.') }}
</p>

            <p class="text-sm text-gray-500">
                {{ $a->created_at->diffForHumans() }}
            </p>

        </div>

    @empty

        <p class="text-gray-500">
            Belum ada aktivitas
        </p>

    @endforelse

</div>

@endsection