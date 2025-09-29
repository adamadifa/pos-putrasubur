<?php

namespace App\Http\Controllers;

use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class KasBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = KasBank::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_rekening', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $kasBank = $query->paginate(10)->withQueryString();

        return view('kas-bank.index', compact('kasBank'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kas-bank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), KasBank::$rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // Handle status_card_payment checkbox
            $data['status_card_payment'] = $request->has('status_card_payment') ? 1 : 0;

            // If this bank is being set as active for card payment, deactivate others
            if ($data['status_card_payment'] == 1) {
                KasBank::where('status_card_payment', 1)->update(['status_card_payment' => 0]);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('kas-bank', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            KasBank::create($data);
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KasBank $kasBank): View
    {
        return view('kas-bank.show', compact('kasBank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KasBank $kasBank): View
    {
        return view('kas-bank.edit', compact('kasBank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KasBank $kasBank): RedirectResponse
    {
        $rules = KasBank::$updateRules;
        $rules['kode'] = 'required|string|max:20|unique:kas_bank,kode,' . $kasBank->id;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // Handle status_card_payment checkbox
            $data['status_card_payment'] = $request->has('status_card_payment') ? 1 : 0;

            // If this bank is being set as active for card payment, deactivate others
            if ($data['status_card_payment'] == 1) {
                KasBank::where('status_card_payment', 1)
                    ->where('id', '!=', $kasBank->id)
                    ->update(['status_card_payment' => 0]);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($kasBank->image && Storage::disk('public')->exists($kasBank->image)) {
                    Storage::disk('public')->delete($kasBank->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('kas-bank', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $kasBank->update($data);
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KasBank $kasBank): RedirectResponse
    {
        try {
            // Delete image if exists
            if ($kasBank->image && Storage::disk('public')->exists($kasBank->image)) {
                Storage::disk('public')->delete($kasBank->image);
            }

            $kasBank->delete();
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
