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
        return view('pelanggan.create');
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

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pelanggan', 'public');
        }

        // Auto-generate kode_pelanggan if not provided
        if (empty($validated['kode_pelanggan'])) {
            $validated['kode_pelanggan'] = 'P-' . str_pad(Pelanggan::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        Pelanggan::create($validated);

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
            'kode_pelanggan' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('pelanggan', 'kode_pelanggan')->ignore($pelangganId),
            ],
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
            'kode_pelanggan.string' => 'Kode pelanggan harus berupa teks.',
            'kode_pelanggan.max' => 'Kode pelanggan maksimal 50 karakter.',
            'kode_pelanggan.unique' => 'Kode pelanggan sudah digunakan.',

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
