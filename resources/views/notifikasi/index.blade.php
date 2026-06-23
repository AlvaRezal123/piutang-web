@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-6">
    🔔 Notifikasi
</h1>

@forelse($notifikasi as $n)

<div
    class="
        rounded-2xl
        p-5
        mb-4
        shadow-sm
        border-l-4

        {{ $n->status_baca == 'belum'
            ? 'bg-indigo-100 border-l-indigo-600 border-indigo-300'
            : 'bg-white border-gray-200'
        }}
    ">
<div class="font-semibold text-lg">

    @if($n->judul == 'Registrasi Agen Baru')
        👤

    @elseif($n->judul == 'Pengajuan Hutang Baru')
        📄

    @elseif($n->judul == 'Hutang Disetujui')
        💰

    @elseif($n->judul == 'Pembayaran Baru')
        💳

    @elseif($n->judul == 'Jatuh Tempo')
        ⚠️

    @elseif($n->judul == 'Keterlambatan')
        🚨

    @else
        🔔
    @endif

    {{ $n->pesan }}
    @if($n->status_baca == 'belum')

<span class="ml-2 text-xs bg-purple-600 text-white px-2 py-1 rounded-full">

    Baru

</span>

@endif

</div>

<div class="text-sm text-gray-500 mt-2">

    {{ \Carbon\Carbon::parse($n->tanggal)->diffForHumans() }}

</div>

</div>

@empty

<div class="bg-white rounded-2xl p-5 shadow-sm border text-gray-500">

Belum ada notifikasi

</div>

@endforelse
<script>

window.onload = function(){

    setTimeout(() => {

        fetch(
            '/admin/notifikasi/baca',
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}'
                }
            }
        );

    }, 2000);

};

</script>
@endsection
