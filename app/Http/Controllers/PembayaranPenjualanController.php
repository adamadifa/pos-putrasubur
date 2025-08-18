<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PembayaranPenjualanController extends Controller
{
    /**
     * Store a newly created payment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'penjualan_id' => 'required|exists:penjualan,id',
                'jumlah' => 'required|numeric|min:1000',
                'tanggal' => 'required|date',
                'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
                'keterangan' => 'nullable|string|max:255',
            ], [
                'penjualan_id.required' => 'ID penjualan wajib diisi.',
                'penjualan_id.exists' => 'Penjualan tidak ditemukan.',
                'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
                'jumlah.numeric' => 'Jumlah pembayaran harus berupa angka.',
                'jumlah.min' => 'Jumlah pembayaran minimal Rp 1.000.',
                'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
                'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
                'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            ]);

            DB::beginTransaction();

            // Get penjualan data
            $penjualan = Penjualan::findOrFail($validated['penjualan_id']);

            // Check if payment amount exceeds remaining amount
            $totalSudahBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            $sisaPembayaran = $penjualan->total - $totalSudahBayar;

            if ($validated['jumlah'] > $sisaPembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran melebihi sisa pembayaran yang harus dibayar.'
                ], 400);
            }

            // Generate payment reference number
            $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($penjualan->pembayaranPenjualan->count() + 1, 2, '0', STR_PAD_LEFT);

            // Calculate payment status logic
            $totalBayarBaru = $totalSudahBayar + $validated['jumlah'];
            $jumlahPembayaranSebelumnya = $penjualan->pembayaranPenjualan->count();

            // Determine status_bayar based on payment sequence and completion
            $statusBayar = 'D'; // Default: DP (first payment)
            $statusPembayaran = 'dp'; // Default: DP status for penjualan

            if ($totalBayarBaru >= $penjualan->total) {
                // This payment completes the transaction
                $statusBayar = 'P'; // Pelunasan
                $statusPembayaran = 'lunas';
            } elseif ($jumlahPembayaranSebelumnya > 0) {
                // This is not the first payment and not completing the transaction
                $statusBayar = 'A'; // Angsuran
                $statusPembayaran = 'angsuran';
            }
            // If $jumlahPembayaranSebelumnya == 0, it remains 'D' (first DP payment)

            // Create payment record
            $pembayaran = PembayaranPenjualan::create([
                'penjualan_id' => $validated['penjualan_id'],
                'no_bukti' => $noBukti,
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $validated['jumlah'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_bayar' => $statusBayar,
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
            ]);

            $penjualan->update([
                'status_pembayaran' => $statusPembayaran
            ]);

            DB::commit();

            // Log successful payment
            Log::info('Payment created successfully', [
                'payment_id' => $pembayaran->id,
                'penjualan_id' => $penjualan->id,
                'amount' => $validated['jumlah'],
                'method' => $validated['metode_pembayaran'],
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimpan!',
                'data' => [
                    'payment_id' => $pembayaran->id,
                    'no_bukti' => $noBukti,
                    'jumlah_bayar' => $validated['jumlah'],
                    'status_pembayaran' => $statusPembayaran,
                    'sisa_pembayaran' => max(0, $penjualan->total - $totalBayarBaru)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
