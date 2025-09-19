<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Pelanggan::query();

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$searchTerm}%")
                    ->orWhere('nomor_telepon', 'like', "%{$searchTerm}%")
                    ->orWhere('alamat', 'like', "%{$searchTerm}%");
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'aktif':
                    $query->aktif();
                    break;
                case 'nonaktif':
                    $query->nonaktif();
                    break;
            }
        }

        // Default sorting by name
        $query->orderBy('nama', 'asc');

        // Get customers with pagination
        $pelanggan = $query->paginate(15)->appends($request->query());

        // Get statistics
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::aktif()->count();
        $pelangganNonaktif = Pelanggan::nonaktif()->count();

        return view('pelanggan.index', compact(
            'pelanggan',
            'totalPelanggan',
            'pelangganAktif',
            'pelangganNonaktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kodePelanggan = $this->generateKodePelanggan();
        return view('pelanggan.create', compact('kodePelanggan'));
    }

    /**
     * Generate automatic customer code with format PEL{YYMM}{001}
     * Example: PEL2509001 (September 2025, customer #001)
     */
    private function generateKodePelanggan()
    {
        // Get current year and month (YYMM format)
        $currentYearMonth = date('ym'); // e.g., 2509 for September 2025

        // Find the last customer code for current month/year
        $lastPelanggan = Pelanggan::where('kode_pelanggan', 'LIKE', 'PEL' . $currentYearMonth . '%')
            ->orderBy('kode_pelanggan', 'desc')
            ->first();

        if ($lastPelanggan) {
            // Extract number from last code (e.g., PEL2509001 -> 001)
            $lastCode = $lastPelanggan->kode_pelanggan;
            if (preg_match('/PEL' . $currentYearMonth . '(\d{3})/', $lastCode, $matches)) {
                $lastNumber = (int) $matches[1];
                $newNumber = $lastNumber + 1;

                // If we reach 1000 for current month, throw an exception
                if ($newNumber > 999) {
                    throw new \Exception('Tidak dapat membuat kode pelanggan baru. Sudah mencapai limit 999 pelanggan untuk bulan ' . date('F Y') . '.');
                }
            } else {
                $newNumber = 1;
            }
        } else {
            $newNumber = 1;
        }

        // Format: PEL + YYMM + 001
        return 'PEL' . $currentYearMonth . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->getValidationRules(),
            $this->getValidationMessages()
        );

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pelanggan', 'public');
        }

        // Auto-generate kode_pelanggan
        $validated['kode_pelanggan'] = $this->generateKodePelanggan();

        $pelanggan = Pelanggan::create($validated);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan.',
                'pelanggan' => $pelanggan
            ]);
        }

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId): View
    {
        $pelanggan = Pelanggan::findByEncryptedId($encryptedId);
        return view('pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId): View
    {
        $pelanggan = Pelanggan::findByEncryptedId($encryptedId);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId): RedirectResponse
    {
        $pelanggan = Pelanggan::findByEncryptedId($encryptedId);

        $validated = $request->validate(
            $this->getValidationRules($pelanggan->id),
            $this->getValidationMessages()
        );

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pelanggan->foto) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pelanggan', 'public');
        }

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.show', $pelanggan->encrypted_id)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId): RedirectResponse
    {
        $pelanggan = Pelanggan::findByEncryptedId($encryptedId);

        // Delete photo if exists
        if ($pelanggan->foto) {
            Storage::disk('public')->delete($pelanggan->foto);
        }

        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Get validation rules
     */
    private function getValidationRules($pelangganId = null): array
    {
        return [
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get validation messages in Indonesian
     */
    private function getValidationMessages(): array
    {
        return [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'nama.string' => 'Nama pelanggan harus berupa teks.',
            'nama.max' => 'Nama pelanggan maksimal 100 karakter.',

            'nomor_telepon.string' => 'Nomor telepon harus berupa teks.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 20 karakter.',

            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',

            'status.required' => 'Status wajib dipilih.',
            'status.boolean' => 'Status harus berupa boolean.',

            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Get customer statistics for dashboard
     */
    public function getStats(): array
    {
        return [
            'total' => Pelanggan::count(),
            'aktif' => Pelanggan::aktif()->count(),
            'nonaktif' => Pelanggan::nonaktif()->count(),
        ];
    }

    /**
     * Search customers for autocomplete
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $pelanggan = Pelanggan::where('nama', 'like', "%{$query}%")
            ->orWhere('kode_pelanggan', 'like', "%{$query}%")
            ->orWhere('nomor_telepon', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nama', 'kode_pelanggan', 'nomor_telepon']);

        return response()->json($pelanggan);
    }
}
