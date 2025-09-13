<?php

namespace App\Http\Controllers;

use App\Models\PenyesuaianStok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenyesuaianStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenyesuaianStok::with(['user', 'produk.satuan']);

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_penyesuaian', 'like', "%{$searchTerm}%")
                    ->orWhereHas('produk', function ($q) use ($searchTerm) {
                        $q->where('nama_produk', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Handle date filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_penyesuaian', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_penyesuaian', '<=', $request->tanggal_sampai);
        }

        // Handle jenis penyesuaian filter
        if ($request->filled('jenis_penyesuaian')) {
            switch ($request->jenis_penyesuaian) {
                case 'penambahan':
                    $query->where('jumlah_penyesuaian', '>', 0);
                    break;
                case 'pengurangan':
                    $query->where('jumlah_penyesuaian', '<', 0);
                    break;
                case 'netral':
                    $query->where('jumlah_penyesuaian', 0);
                    break;
            }
        }

        $penyesuaianStok = $query->orderBy('tanggal_penyesuaian', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)->appends($request->query());

        return view('penyesuaian-stok.index', compact('penyesuaianStok'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::with('satuan')->get();
        return view('penyesuaian-stok.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'produk_id' => 'required|exists:produk,id',
            'jumlah_penyesuaian' => 'required|numeric',
            'keterangan' => 'required|string|min:10'
        ], [
            'tanggal_penyesuaian.required' => 'Tanggal penyesuaian harus diisi',
            'tanggal_penyesuaian.date' => 'Format tanggal tidak valid',
            'produk_id.required' => 'Produk harus dipilih',
            'produk_id.exists' => 'Produk yang dipilih tidak valid',
            'jumlah_penyesuaian.required' => 'Jumlah penyesuaian harus diisi',
            'jumlah_penyesuaian.numeric' => 'Jumlah penyesuaian harus berupa angka',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.min' => 'Keterangan minimal 10 karakter'
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::find($request->produk_id);
            $stokSebelum = $produk->stok;
            $stokSesudah = $stokSebelum + $request->jumlah_penyesuaian;

            // Validasi stok tidak boleh negatif
            if ($stokSesudah < 0) {
                return back()->with('error', 'Stok tidak boleh negatif. Stok saat ini: ' . $stokSebelum . ', Penyesuaian: ' . $request->jumlah_penyesuaian);
            }

            // Create penyesuaian stok
            $penyesuaianStok = PenyesuaianStok::create([
                'kode_penyesuaian' => PenyesuaianStok::generateKodePenyesuaian(),
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'produk_id' => $request->produk_id,
                'stok_sebelum' => $stokSebelum,
                'jumlah_penyesuaian' => $request->jumlah_penyesuaian,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id()
            ]);

            // Update stok produk langsung
            $produk->update(['stok' => $stokSesudah]);

            DB::commit();
            return redirect()->route('penyesuaian-stok.index')
                ->with('success', 'Penyesuaian stok berhasil dibuat dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PenyesuaianStok $penyesuaianStok)
    {
        $penyesuaianStok->load(['user', 'produk.satuan']);
        return view('penyesuaian-stok.show', compact('penyesuaianStok'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenyesuaianStok $penyesuaianStok)
    {
        $penyesuaianStok->load(['produk.satuan']);
        $produks = Produk::with('satuan')->get();

        return view('penyesuaian-stok.edit', compact('penyesuaianStok', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenyesuaianStok $penyesuaianStok)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'produk_id' => 'required|exists:produk,id',
            'jumlah_penyesuaian' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Kembalikan stok ke kondisi sebelum penyesuaian
            $produkLama = $penyesuaianStok->produk;
            $produkLama->update(['stok' => $penyesuaianStok->stok_sebelum]);

            // Hitung penyesuaian baru
            $produk = Produk::find($request->produk_id);
            $stokSebelum = $produk->stok;
            $stokSesudah = $stokSebelum + $request->jumlah_penyesuaian;

            // Validasi stok tidak boleh negatif
            if ($stokSesudah < 0) {
                return back()->with('error', 'Stok tidak boleh negatif. Stok saat ini: ' . $stokSebelum . ', Penyesuaian: ' . $request->jumlah_penyesuaian);
            }

            // Update penyesuaian stok
            $penyesuaianStok->update([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'produk_id' => $request->produk_id,
                'stok_sebelum' => $stokSebelum,
                'jumlah_penyesuaian' => $request->jumlah_penyesuaian,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $request->keterangan
            ]);

            // Update stok produk
            $produk->update(['stok' => $stokSesudah]);

            DB::commit();
            return redirect()->route('penyesuaian-stok.index')
                ->with('success', 'Penyesuaian stok berhasil diperbarui dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenyesuaianStok $penyesuaianStok)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok ke kondisi sebelum penyesuaian
            $produk = $penyesuaianStok->produk;
            $produk->update(['stok' => $penyesuaianStok->stok_sebelum]);

            // Hapus penyesuaian stok
            $penyesuaianStok->delete();

            DB::commit();
            return redirect()->route('penyesuaian-stok.index')
                ->with('success', 'Penyesuaian stok berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
