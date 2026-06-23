@extends('layouts.owner')

@section('content')

<div class="mb-8">


<h1 class="text-3xl font-bold text-gray-800">
    Detail Pengajuan Hutang
</h1>

<p class="text-gray-500 mt-2">
    Informasi lengkap pengajuan dan riwayat hutang agen.
</p>


</div>

<div class="grid md:grid-cols-2 gap-6 mb-8">


<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <h2 class="font-bold text-lg mb-4">
        Data Agen
    </h2>

    <div class="space-y-3">

        <p>
            <strong>Nama Agen :</strong>
            {{ $hutang->agen->username }}
        </p>

        <p>
            <strong>ID Agen :</strong>
            {{ $hutang->agen->id_agen_pp }}
        </p>

        <p>
            <strong>No HP :</strong>
            {{ $hutang->agen->no_hp }}
        </p>

        <p>
            <strong>Status Agen :</strong>
            {{ ucfirst($hutang->agen->status) }}
        </p>

    </div>

</div>

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <h2 class="font-bold text-lg mb-4">
        Pengajuan Saat Ini
    </h2>

    <div class="space-y-3">

        <p>
            <strong>Jumlah :</strong>
            Rp{{ number_format($hutang->jumlah_hutang,0,',','.') }}
        </p>

        <p>
            <strong>Metode :</strong>
            {{ ucfirst($hutang->metode) }}
        </p>

        <p>
            <strong>Tanggal Pengajuan :</strong>
            {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
        </p>

        <p>
            <strong>Jatuh Tempo :</strong>
            {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
        </p>

        <p>
            <strong>Status :</strong>
            {{ ucfirst($hutang->status) }}
        </p>

        <p>
            <strong>Catatan :</strong>
            {{ $hutang->catatan_pengajuan ?? '-' }}
        </p>

    </div>

</div>


</div>

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">


<h2 class="text-xl font-bold mb-6">
    Riwayat Hutang Agen
</h2>

<div class="overflow-x-auto">

    <table class="w-full">

        <thead>

            <tr class="border-b">

                <th class="text-left py-3">
                    Tanggal
                </th>

                <th class="text-left py-3">
                    Jumlah
                </th>

                <th class="text-left py-3">
                    Metode
                </th>

                <th class="text-left py-3">
                    Status
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($riwayat as $r)

            <tr class="border-b">

                <td class="py-4">

                    {{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d M Y') }}

                </td>

                <td>

                    Rp{{ number_format($r->jumlah_hutang,0,',','.') }}

                </td>

                <td>

                    {{ ucfirst($r->metode) }}

                </td>

                <td>

                    {{ ucfirst($r->status) }}

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>


</div>

<div class="flex justify-between mt-8">


<a
    href="/owner/hutang"
    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl">

    Kembali

</a>

@if($hutang->status == 'pending')

<div class="flex gap-3">

    <a
        href="/owner/hutang/setujui/{{ $hutang->id }}"
        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl">

        Setujui

    </a>

    <a
        href="/owner/hutang/form-tolak/{{ $hutang->id }}"
        class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl">

        Tolak

    </a>

</div>

@endif


</div>

@endsection
