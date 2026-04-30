<?php

namespace App\Http\Controllers;

use App\Models\UangMukaPelanggan;
use App\Models\UangMukaSupplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanUangMukaController extends Controller
{
    public function pelanggan(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', date('d/m/Y'));
        $tanggalSampai = $request->get('tanggal_sampai', date('d/m/Y'));

        try {
            $start = Carbon::createFromFormat('d/m/Y', $tanggalDari)->startOfDay();
            $end = Carbon::createFromFormat('d/m/Y', $tanggalSampai)->endOfDay();
        } catch (\Exception $e) {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        $data = UangMukaPelanggan::with(['pelanggan', 'penggunaanPenjualan'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporan.uang-muka.pelanggan', compact('data', 'tanggalDari', 'tanggalSampai'));
    }

    public function supplier(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', date('d/m/Y'));
        $tanggalSampai = $request->get('tanggal_sampai', date('d/m/Y'));

        try {
            $start = Carbon::createFromFormat('d/m/Y', $tanggalDari)->startOfDay();
            $end = Carbon::createFromFormat('d/m/Y', $tanggalSampai)->endOfDay();
        } catch (\Exception $e) {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        $data = UangMukaSupplier::with(['supplier', 'penggunaanPembelian'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporan.uang-muka.supplier', compact('data', 'tanggalDari', 'tanggalSampai'));
    }
}
