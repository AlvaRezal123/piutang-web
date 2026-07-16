@extends('layouts.owner')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Tolak Pengajuan Hutangs
        </h1>

        <p class="text-gray-500 mt-2">
            Berikan alasan penolakan agar agen mengetahui penyebab pengajuannya ditolak.
        </p>

    </div>

    <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-8">

        <div class="mb-6">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-2xl">
                    ❌
                </div>

                <div>

                    <h2 class="text-xl font-bold text-gray-800">
                        Form Penolakan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Pengajuan dari {{ $hutang->agen->username }}
                    </p>

                </div>

            </div>

        </div>

        <form action="/owner/hutang/tolak/{{ $hutang->id }}" method="POST">

            @csrf

            <div class="mb-6">

                <label class="block font-semibold text-gray-700 mb-2">
                    Alasan Penolakan
                </label>

                <textarea
                    name="alasan_penolakan"
                    rows="5"
                    class="w-full border border-gray-300 rounded-2xl p-4 focus:ring-2 focus:ring-red-400 focus:outline-none"
                    placeholder="Contoh: Riwayat pembayaran sebelumnya kurang baik..."
                >{{ old('alasan_penolakan') }}</textarea>

                @error('alasan_penolakan')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <div class="flex justify-end gap-3">

                <a href="/owner/hutang"
                   class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">

                    Batal

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold">

                    Simpan Penolakan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection