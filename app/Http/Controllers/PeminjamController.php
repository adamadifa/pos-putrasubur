<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PeminjamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Peminjam::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_peminjam', 'like', "%{$searchTerm}%")
                    ->orWhere('nomor_telepon', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->aktif();
            } elseif ($request->status == 'nonaktif') {
                $query->nonaktif();
            }
        }

        $query->orderBy('nama', 'asc');
        $peminjam = $query->paginate(15)->appends($request->query());

        $totalPeminjam = Peminjam::count();
        $peminjamAktif = Peminjam::aktif()->count();
        $peminjamNonaktif = Peminjam::nonaktif()->count();

        return view('peminjam.index', compact('peminjam', 'totalPeminjam', 'peminjamAktif', 'peminjamNonaktif'));
    }

    public function create(): View
    {
        return view('peminjam.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_peminjam' => 'nullable|string|max:50|unique:peminjam',
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'boolean',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'kode_peminjam.unique' => 'Kode peminjam sudah digunakan.',
        ]);

        $validated['status'] = $request->has('status') && $request->status == '1' ? true : false;

        Peminjam::create($validated);

        return redirect()->route('peminjam.index')
            ->with('success', 'Peminjam berhasil ditambahkan.');
    }

    public function show($encryptedId): View
    {
        try {
            $id = decrypt($encryptedId);
            $peminjam = Peminjam::with('pinjaman')->findOrFail($id);
            return view('peminjam.show', compact('peminjam'));
        } catch (\Exception $e) {
            return redirect()->route('peminjam.index')
                ->with('error', 'Peminjam tidak ditemukan.');
        }
    }

    public function edit($encryptedId): View
    {
        try {
            $id = decrypt($encryptedId);
            $peminjam = Peminjam::findOrFail($id);
            return view('peminjam.edit', compact('peminjam'));
        } catch (\Exception $e) {
            return redirect()->route('peminjam.index')
                ->with('error', 'Peminjam tidak ditemukan.');
        }
    }

    public function update(Request $request, $encryptedId): RedirectResponse
    {
        try {
            $id = decrypt($encryptedId);
            $peminjam = Peminjam::findOrFail($id);

        $validated = $request->validate([
            'kode_peminjam' => 'nullable|string|max:50|unique:peminjam,kode_peminjam,' . $peminjam->id,
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'boolean',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'kode_peminjam.unique' => 'Kode peminjam sudah digunakan.',
        ]);

        $validated['status'] = $request->has('status') && $request->status == '1' ? true : false;

        $peminjam->update($validated);

        return redirect()->route('peminjam.show', $peminjam->encrypted_id)
            ->with('success', 'Data peminjam berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('peminjam.index')
                ->with('error', 'Peminjam tidak ditemukan.');
        }
    }

    public function destroy($encryptedId): RedirectResponse
    {
        try {
            $id = decrypt($encryptedId);
            $peminjam = Peminjam::with('pinjaman')->findOrFail($id);

            // Check if peminjam has pinjaman
            if ($peminjam->pinjaman->count() > 0) {
                return back()->withErrors(['error' => 'Peminjam tidak dapat dihapus karena sudah memiliki pinjaman.']);
            }

            $peminjam->delete();

            return redirect()->route('peminjam.index')
                ->with('success', 'Peminjam berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('peminjam.index')
                ->with('error', 'Peminjam tidak ditemukan.');
        }
    }
}
