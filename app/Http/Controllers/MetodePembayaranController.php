<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MetodePembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MetodePembayaran::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->aktif();
            } elseif ($request->status === 'nonaktif') {
                $query->nonaktif();
            }
        }

        // Get paginated results
        $metodePembayaran = $query->ordered()->paginate(10);

        // Calculate statistics
        $totalMetode = MetodePembayaran::count();
        $aktifCount = MetodePembayaran::aktif()->count();
        $nonaktifCount = MetodePembayaran::nonaktif()->count();

        return view('metode-pembayaran.index', compact(
            'metodePembayaran',
            'totalMetode',
            'aktifCount',
            'nonaktifCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('metode-pembayaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:metode_pembayaran',
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'status' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ], [
            'kode.required' => 'Kode metode pembayaran wajib diisi.',
            'kode.unique' => 'Kode metode pembayaran sudah digunakan.',
            'nama.required' => 'Nama metode pembayaran wajib diisi.',
            'nama.max' => 'Nama metode pembayaran maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'icon.max' => 'Icon maksimal 50 karakter.',
            'urutan.min' => 'Urutan tidak boleh negatif.',
        ]);

        try {
            MetodePembayaran::create($validated);

            return redirect()->route('metode-pembayaran.index')
                ->with('success', 'Metode pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan metode pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $metodePembayaran = MetodePembayaran::findOrFail(Crypt::decryptString($id));

            return view('metode-pembayaran.show', compact('metodePembayaran'));
        } catch (\Exception $e) {
            return redirect()->route('metode-pembayaran.index')
                ->with('error', 'Metode pembayaran tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $metodePembayaran = MetodePembayaran::findOrFail(Crypt::decryptString($id));

            return view('metode-pembayaran.edit', compact('metodePembayaran'));
        } catch (\Exception $e) {
            return redirect()->route('metode-pembayaran.index')
                ->with('error', 'Metode pembayaran tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $metodePembayaran = MetodePembayaran::findOrFail(Crypt::decryptString($id));

            $validated = $request->validate([
                'kode' => 'required|string|max:20|unique:metode_pembayaran,kode,' . $metodePembayaran->id,
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:500',
                'icon' => 'nullable|string|max:50',
                'status' => 'boolean',
                'urutan' => 'nullable|integer|min:0',
            ], [
                'kode.required' => 'Kode metode pembayaran wajib diisi.',
                'kode.unique' => 'Kode metode pembayaran sudah digunakan.',
                'nama.required' => 'Nama metode pembayaran wajib diisi.',
                'nama.max' => 'Nama metode pembayaran maksimal 100 karakter.',
                'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
                'icon.max' => 'Icon maksimal 50 karakter.',
                'urutan.min' => 'Urutan tidak boleh negatif.',
            ]);

            $metodePembayaran->update($validated);

            return redirect()->route('metode-pembayaran.index')
                ->with('success', 'Metode pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui metode pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $metodePembayaran = MetodePembayaran::findOrFail(Crypt::decryptString($id));

            // Check if method is being used in payments
            $usedInPayments = DB::table('pembayaran_penjualan')
                ->where('metode_pembayaran', $metodePembayaran->kode)
                ->exists();

            if ($usedInPayments) {
                return back()->with('error', 'Metode pembayaran tidak dapat dihapus karena masih digunakan dalam transaksi.');
            }

            $metodePembayaran->delete();

            return redirect()->route('metode-pembayaran.index')
                ->with('success', 'Metode pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus metode pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Search metode pembayaran for AJAX requests
     */
    public function search(Request $request)
    {
        $search = $request->get('q');

        $metodePembayaran = MetodePembayaran::aktif()
            ->where(function ($query) use ($search) {
                $query->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->ordered()
            ->limit(10)
            ->get(['id', 'kode', 'nama', 'icon']);

        return response()->json($metodePembayaran);
    }
}
