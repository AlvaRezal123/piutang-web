@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-6" id="notifHeader" data-notif-baru="{{ $notifikasi->where('status_baca','belum')->count() }}">
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

@php

$icon = '<i class="ti ti-bell text-xl text-purple-600"></i>';

if($n->judul == 'Registrasi Agen Baru'){
    $icon = '<i class="ti ti-user-plus text-xl text-blue-600"></i>';
}
elseif($n->judul == 'Pengajuan Hutang Baru'){
    $icon = '<i class="ti ti-file-invoice text-xl text-yellow-600"></i>';
}
elseif($n->judul == 'Hutang Disetujui'){
    $icon = '<i class="ti ti-cash text-xl text-green-600"></i>';
}
elseif($n->judul == 'Pembayaran Baru'){
    $icon = '<i class="ti ti-credit-card text-xl text-indigo-600"></i>';
}
elseif($n->judul == 'Jatuh Tempo'){
    $icon = '<i class="ti ti-clock-exclamation text-xl text-orange-600"></i>';
}
elseif($n->judul == 'Keterlambatan'){
    $icon = '<i class="ti ti-alert-triangle text-xl text-red-600"></i>';
}

@endphp

<div class="flex items-start gap-3">

    <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">

        {!! $icon !!}

    </div>

    <div class="flex-1">

        <p class="font-semibold text-gray-800">

            {{ $n->judul }}

            @if($n->status_baca == 'belum')

                <span class="ml-2 text-xs bg-purple-600 text-white px-2 py-1 rounded-full badgeBaru">
                    Baru
                </span>

            @endif

        </p>

        <p class="text-gray-600 mt-1">
            {{ $n->pesan }}
        </p>

        <p class="text-sm text-gray-400 mt-2">
            {{ \Carbon\Carbon::parse($n->tanggal)->diffForHumans() }}
        </p>

    </div>

</div>
</div>


</div>

@empty

<div class="bg-white rounded-2xl p-5 shadow-sm border text-gray-500">

Belum ada notifikasi

</div>

@endforelse
<script>

window.onload = function(){

    const adaNotifBaru = parseInt(document.getElementById('notifHeader').dataset.notifBaru, 10);

    if(adaNotifBaru > 0){

  fetch('/admin/notifikasi/baca', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
})
.then(res => {
    console.log(res.status);
    return res.json();
})
.then(data => {
    console.log(data);

    if(data.success){

    document
        .querySelectorAll('.badgeBaru')
        .forEach(el => {

            el.remove();

        });

}
})
.catch(err => {
    console.log(err);
});

    }

};

</script>
@endsection
