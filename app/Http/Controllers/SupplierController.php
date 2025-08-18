<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('kode_supplier', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('status', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('status', false);
            }
        }

        // Get paginated results
        $suppliers = $query->orderBy('nama')->paginate(10);

        // Get statistics
        $totalSupplier = Supplier::count();
        $supplierAktif = Supplier::where('status', true)->count();
        $supplierNonaktif = Supplier::where('status', false)->count();

        return view('supplier.index', compact('suppliers', 'totalSupplier', 'supplierAktif', 'supplierNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|string|max:20|unique:supplier,kode_supplier',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'keterangan' => 'nullable|string',
            'status' => 'boolean',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $supplier = Supplier::with([
                'pembelian.detailPembelian.produk',
                'pembelian.pembayaranPembelian',
                'pembelian.user'
            ])->findOrFail(Crypt::decryptString($id));

            return view('supplier.show', compact('supplier'));
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $supplier = Supplier::findOrFail(Crypt::decryptString($id));
            return view('supplier.edit', compact('supplier'));
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail(Crypt::decryptString($id));

            $validated = $request->validate([
                'kode_supplier' => 'required|string|max:20|unique:supplier,kode_supplier,' . $supplier->id,
                'nama' => 'required|string|max:100',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'keterangan' => 'nullable|string',
                'status' => 'boolean',
            ]);

            $supplier->update($validated);

            return redirect()->route('supplier.index')
                ->with('success', 'Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak ditemukan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail(Crypt::decryptString($id));

            // Check if supplier has any transactions
            if ($supplier->pembelian()->count() > 0) {
                return redirect()->route('supplier.index')
                    ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki transaksi pembelian.');
            }

            $supplier->delete();

            return redirect()->route('supplier.index')
                ->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak ditemukan.');
        }
    }

    /**
     * Search suppliers for AJAX request
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $suppliers = Supplier::where('nama', 'like', "%{$search}%")
            ->orWhere('alamat', 'like', "%{$search}%")
            ->orWhere('telepon', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('kode_supplier', 'like', "%{$search}%")
            ->where('status', true)
            ->orderBy('nama')
            ->limit(10)
            ->get(['id', 'kode_supplier', 'nama', 'alamat', 'telepon', 'email']);

        return response()->json($suppliers);
    }
}
