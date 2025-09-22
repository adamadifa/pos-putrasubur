<?php

namespace App\Http\Controllers;

use App\Models\PengaturanUmum;
use App\Helpers\PengaturanUmumHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaturan = PengaturanUmum::getActive();

        return view('pengaturan-umum.index', compact('pengaturan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengaturan-umum.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_rekening_koperasi' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'nama_toko.required' => 'Nama toko harus diisi',
            'nama_toko.max' => 'Nama toko maksimal 255 karakter',
            'alamat.string' => 'Alamat harus berupa teks',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter',
            'no_rekening_koperasi.max' => 'Nomor rekening koperasi maksimal 50 karakter',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'deskripsi.string' => 'Deskripsi harus berupa teks',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
            'foto_toko.image' => 'File foto toko harus berupa gambar',
            'foto_toko.mimes' => 'Format foto toko harus jpeg, png, jpg, atau gif',
            'foto_toko.max' => 'Ukuran foto toko maksimal 5MB'
        ]);

        $data = $request->except(['logo', 'foto_toko']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('pengaturan', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle foto toko upload
        if ($request->hasFile('foto_toko')) {
            $fotoTokoPath = $request->file('foto_toko')->store('pengaturan', 'public');
            $data['foto_toko'] = $fotoTokoPath;
        }

        $pengaturan = PengaturanUmum::create($data);
        $pengaturan->setAsActive();

        // Clear cache after creating
        PengaturanUmumHelper::clearCache();

        return redirect()->route('pengaturan-umum.index')
            ->with('success', 'Pengaturan umum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengaturanUmum $pengaturanUmum)
    {
        return view('pengaturan-umum.show', ['pengaturanUmumModel' => $pengaturanUmum]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengaturanUmum $pengaturanUmum)
    {
        return view('pengaturan-umum.edit', ['pengaturanUmumModel' => $pengaturanUmum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengaturanUmum $pengaturanUmum)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_rekening_koperasi' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'nama_toko.required' => 'Nama toko harus diisi',
            'nama_toko.max' => 'Nama toko maksimal 255 karakter',
            'alamat.string' => 'Alamat harus berupa teks',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter',
            'no_rekening_koperasi.max' => 'Nomor rekening koperasi maksimal 50 karakter',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'deskripsi.string' => 'Deskripsi harus berupa teks',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
            'foto_toko.image' => 'File foto toko harus berupa gambar',
            'foto_toko.mimes' => 'Format foto toko harus jpeg, png, jpg, atau gif',
            'foto_toko.max' => 'Ukuran foto toko maksimal 5MB'
        ]);

        $data = $request->except(['logo', 'foto_toko']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($pengaturanUmum->logo && Storage::disk('public')->exists($pengaturanUmum->logo)) {
                Storage::disk('public')->delete($pengaturanUmum->logo);
            }

            $logoPath = $request->file('logo')->store('pengaturan', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle foto toko upload
        if ($request->hasFile('foto_toko')) {
            // Delete old foto toko if exists
            if ($pengaturanUmum->foto_toko && Storage::disk('public')->exists($pengaturanUmum->foto_toko)) {
                Storage::disk('public')->delete($pengaturanUmum->foto_toko);
            }

            $fotoTokoPath = $request->file('foto_toko')->store('pengaturan', 'public');
            $data['foto_toko'] = $fotoTokoPath;
        }

        $pengaturanUmum->update($data);

        // Clear cache after updating
        PengaturanUmumHelper::clearCache();

        return redirect()->route('pengaturan-umum.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengaturanUmum $pengaturanUmum)
    {
        // Delete logo file if exists
        if ($pengaturanUmum->logo && Storage::disk('public')->exists($pengaturanUmum->logo)) {
            Storage::disk('public')->delete($pengaturanUmum->logo);
        }

        $pengaturanUmum->delete();

        // Clear cache after deleting
        PengaturanUmumHelper::clearCache();

        return redirect()->route('pengaturan-umum.index')
            ->with('success', 'Pengaturan umum berhasil dihapus.');
    }

    /**
     * Set pengaturan as active
     */
    public function setActive(PengaturanUmum $pengaturanUmum)
    {
        $pengaturanUmum->setAsActive();

        // Clear cache after setting active
        PengaturanUmumHelper::clearCache();

        return redirect()->route('pengaturan-umum.index')
            ->with('success', 'Pengaturan telah diaktifkan.');
    }
}
