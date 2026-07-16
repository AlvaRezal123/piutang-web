@extends('layouts.owner')

@section('content')

<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Persetujuan Fasilitas Cicilan
    </h1>

    <p class="text-gray-500 mt-2">
        Kelola permohonan hak akses pembayaran cicilan dari agen.
    </p>

</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Statistik -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- Pending -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Pending</p>
        <h2 class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $agen->where('status_permohonan_cicilan','pending')->count() }}
        </h2>
    </div>

    <!-- Disetujui -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Disetujui</p>
        <h2 class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $agen->where('status_permohonan_cicilan','disetujui')->count() }}
        </h2>
    </div>

    <!-- Ditolak -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Ditolak</p>
        <h2 class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $agen->where('status_permohonan_cicilan','ditolak')->count() }}
        </h2>
    </div>

    <!-- Total -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Total</p>
        <h2 class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $agen->count() }}
        </h2>
    </div>

</div>
<!-- Tabel -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Daftar Permohonan Cicilan
    </h2>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">Agen</th>
                    <th class="text-left py-3">ID Agen</th>
                    <th class="text-left py-3">Tanggal Permohonan</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Detail</th>
                    <th class="text-left py-3">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($agen as $a)

                <tr class="border-b">

                    <td class="py-4">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-[#5628C7] font-semibold">

                                {{ strtoupper(substr($a->username,0,2)) }}

                            </div>

                            {{ $a->username }}

                        </div>

                    </td>

                    <td>

                        {{ $a->id_agen_pp }}

                    </td>

                    <td>

                        {{ $a->updated_at->format('d M Y') }}

                    </td>

                    <td>

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                            Pending

                        </span>

                    </td>

                    <!-- Detail -->
                    <td>
                        <a href="/owner/hutang/detail/{{ $a->id }}?from=cicilan"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                            <i class="ti ti-eye text-sm"></i>
                        </a>
                    </td>

                    <!-- Aksi -->
                    <td>

                        <div class="flex items-center gap-2">

                            <a
                                href="/owner/approval-cicilan/setujui/{{ $a->id }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm">

                                Setujui

                            </a>

                            <button
                                type="button"
                                class="btn-tolak bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg"
                                data-id="{{ $a->id }}">

                                Tolak

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">

                        Belum ada permohonan cicilan.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>
<!-- MODAL TOLAK CICILAN -->

<div
id="modalTolak"
class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

<div class="bg-white rounded-2xl w-full max-w-lg p-8">

<h2 class="text-2xl font-bold">

Tolak Permohonan Cicilan

</h2>

<p class="text-gray-500 mt-2">

Masukkan alasan penolakan.

</p>

<form
id="formTolak"
method="POST">

@csrf

<div class="mt-6">

<label class="font-semibold">

Alasan Penolakan

</label>

<textarea
name="alasan"
rows="5"
class="w-full border rounded-xl p-4 mt-2"
required></textarea>

</div>

<div class="flex justify-end gap-3 mt-6">

<button
type="button"
onclick="tutupModal()"
class="px-5 py-3 rounded-xl bg-gray-100">

Batal

</button>

<button
class="px-5 py-3 rounded-xl bg-red-600 text-white">

Tolak

</button>

</div>

</form>

</div>

</div>
<script>

document.querySelectorAll('.btn-tolak').forEach(function (btn) {
    btn.addEventListener('click', function () {
        bukaModalTolak(btn.dataset.id);
    });
});

function bukaModalTolak(id)
{
    document
    .getElementById('modalTolak')
    .classList.remove('hidden');

    document
    .getElementById('modalTolak')
    .classList.add('flex');

    document
    .getElementById('formTolak')
    .action='/owner/approval-cicilan/tolak/'+id;
}

function tutupModal()
{
    document
    .getElementById('modalTolak')
    .classList.add('hidden');

    document
    .getElementById('modalTolak')
    .classList.remove('flex');
}

</script>
@endsection