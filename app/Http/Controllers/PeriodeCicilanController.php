<?php

namespace App\Http\Controllers;

use App\Models\PeriodeCicilan;
use Illuminate\Http\Request;

class PeriodeCicilanController extends Controller
{
    public function index()
    {
        $periode = PeriodeCicilan::orderBy('jumlah_bulan')->get();

        return view(
            'periode-cicilan.index',
            compact('periode')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_bulan' => 'required|integer|min:1|unique:periode_cicilan,jumlah_bulan',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'jumlah_bulan.unique' => 'Periode tersebut sudah ada.'
        ]);

        PeriodeCicilan::create([
            'nama_periode' => $request->jumlah_bulan.' Bulan',
            'jumlah_bulan' => $request->jumlah_bulan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Periode berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Hanya status yang boleh diubah.
        // jumlah_bulan & nama_periode tidak diedit di sini agar data historis
        // (cicilan yang sudah memakai periode ini) tidak berubah makna.
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $periode = PeriodeCicilan::findOrFail($id);

        $periode->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status periode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PeriodeCicilan::findOrFail($id)->delete();

        return back()->with('success', 'Periode berhasil dihapus.');
    }
}