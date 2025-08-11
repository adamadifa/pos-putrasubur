<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Satuan::withCount('produk');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }

        $satuans = $query->orderBy('nama')->get();

        // Get totals for stats cards
        $totalSatuan = Satuan::count();
        $totalProduk = \App\Models\Produk::count();

        return view('satuan.index', compact('satuans', 'totalSatuan', 'totalProduk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50|min:2|unique:satuan,nama',
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.string' => 'Nama satuan harus berupa teks.',
            'nama.max' => 'Nama satuan maksimal 50 karakter.',
            'nama.min' => 'Nama satuan minimal 2 karakter.',
            'nama.unique' => 'Nama satuan sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Satuan::create([
                'nama' => $request->nama,
            ]);

            return redirect()->route('satuan.index')
                ->with('success', 'Satuan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan satuan.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);
        return view('satuan.edit', compact('satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $satuan = Satuan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50|min:2|unique:satuan,nama,' . $id,
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.string' => 'Nama satuan harus berupa teks.',
            'nama.max' => 'Nama satuan maksimal 50 karakter.',
            'nama.min' => 'Nama satuan minimal 2 karakter.',
            'nama.unique' => 'Nama satuan sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $satuan->update([
                'nama' => $request->nama,
            ]);

            return redirect()->route('satuan.index')
                ->with('success', 'Satuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui satuan.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);

            // Check if satuan is used in produk
            if ($satuan->produk()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh produk.');
            }

            $satuan->delete();

            return redirect()->route('satuan.index')
                ->with('success', 'Satuan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus satuan.');
        }
    }
}
