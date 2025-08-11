<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Produk::with(['kategori', 'satuan']);

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_produk', 'like', "%{$searchTerm}%")
                    ->orWhereHas('kategori', function ($kategoriQuery) use ($searchTerm) {
                        $kategoriQuery->where('nama', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Handle status filter (from quick filters)
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'tersedia':
                    $query->tersedia();
                    break;
                case 'menipis':
                    $query->menipis();
                    break;
                case 'habis':
                    $query->habis();
                    break;
            }
        }

        // Default sorting by name
        $query->orderBy('nama_produk', 'asc');

        // Get products with pagination
        $produk = $query->paginate(15)->appends($request->query());

        // Get statistics
        $totalProduk = Produk::count();
        $produkTersedia = Produk::tersedia()->count();
        $produkMenipis = Produk::menipis()->count();
        $produkHabis = Produk::habis()->count();

        return view('produk.index', compact(
            'produk',
            'totalProduk',
            'produkTersedia',
            'produkMenipis',
            'produkHabis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        return view('produk.create', compact('kategoris', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            $this->getValidationRules(),
            $this->getValidationMessages()
        );

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk): View
    {
        $produk->load(['kategori', 'satuan']);

        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk): View
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        return view('produk.edit', compact('produk', 'kategoris', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate(
            $this->getValidationRules($produk->id),
            $this->getValidationMessages()
        );

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('produk.edit', $produk->id)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        // Delete photo if exists
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }



    /**
     * Get validation rules
     */
    private function getValidationRules($produkId = null): array
    {
        return [
            'kode_produk' => $produkId ?
                'required|string|max:50|unique:produk,kode_produk,' . $produkId :
                'required|string|max:50|unique:produk',
            'nama_produk' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get validation messages in Indonesian
     */
    private function getValidationMessages(): array
    {
        return [
            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.string' => 'Kode produk harus berupa teks.',
            'kode_produk.max' => 'Kode produk maksimal 50 karakter.',
            'kode_produk.unique' => 'Kode produk sudah digunakan.',

            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.string' => 'Nama produk harus berupa teks.',
            'nama_produk.max' => 'Nama produk maksimal 100 karakter.',

            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',

            'satuan_id.required' => 'Satuan wajib dipilih.',
            'satuan_id.exists' => 'Satuan yang dipilih tidak valid.',

            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',

            'stok.required' => 'Stok awal wajib diisi.',
            'stok.integer' => 'Stok awal harus berupa angka bulat.',
            'stok.min' => 'Stok awal tidak boleh kurang dari 0.',

            'stok_minimal.required' => 'Stok minimal wajib diisi.',
            'stok_minimal.integer' => 'Stok minimal harus berupa angka bulat.',
            'stok_minimal.min' => 'Stok minimal tidak boleh kurang dari 0.',

            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
