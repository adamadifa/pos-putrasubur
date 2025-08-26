<?php

namespace App\Http\Controllers;

use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KasBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    public function create()
    {
        return view('kas-bank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), KasBank::$rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            KasBank::create($request->all());
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KasBank $kasBank)
    {
        return view('kas-bank.show', compact('kasBank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KasBank $kasBank)
    {
        return view('kas-bank.edit', compact('kasBank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KasBank $kasBank)
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
            $kasBank->update($request->all());
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KasBank $kasBank)
    {
        try {
            $kasBank->delete();
            return redirect()->route('kas-bank.index')
                ->with('success', 'Data Kas & Bank berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
