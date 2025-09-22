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
     * Generate automatic supplier code with format SUP{YYMM}{001}
     * Example: SUP2509001 (September 2025, supplier #001)
     */
    private function generateKodeSupplier()
    {
        // Get current year and month (YYMM format)
        $currentYearMonth = date('ym'); // e.g., 2509 for September 2025

        // Find the last supplier code for current month/year
        $lastSupplier = Supplier::where('kode_supplier', 'LIKE', 'SUP' . $currentYearMonth . '%')
            ->orderBy('kode_supplier', 'desc')
            ->first();

        if ($lastSupplier) {
            // Extract number from last code (e.g., SUP2509001 -> 001)
            $lastCode = $lastSupplier->kode_supplier;
            if (preg_match('/SUP' . $currentYearMonth . '(\d{3})/', $lastCode, $matches)) {
                $lastNumber = (int) $matches[1];
                $newNumber = $lastNumber + 1;

                // If we reach 1000 for current month, throw an exception
                if ($newNumber > 999) {
                    throw new \Exception('Tidak dapat membuat kode supplier baru. Sudah mencapai limit 999 supplier untuk bulan ' . date('F Y') . '.');
                }
            } else {
                $newNumber = 1;
            }
        } else {
            $newNumber = 1;
        }

        // Format: SUP + YYMM + 001
        return 'SUP' . $currentYearMonth . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'keterangan' => 'nullable|string',
                'status' => 'boolean',
            ]);

            // Auto-generate kode supplier
            $validated['kode_supplier'] = $this->generateKodeSupplier();

            Supplier::create($validated);

            return redirect()->route('supplier.index')
                ->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
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
