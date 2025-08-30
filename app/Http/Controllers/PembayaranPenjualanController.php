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
            // Log incoming request data for debugging
            Log::info('Payment request received', [
                'all_data' => $request->all(),
                'kas_bank_id' => $request->input('kas_bank_id'),
                'metode_pembayaran' => $request->input('metode_pembayaran'),
                'jumlah' => $request->input('jumlah'),
                'user_id' => Auth::id()
            ]);

            // Clean and parse jumlah input
            $jumlahInput = $request->input('jumlah');
            $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
            $jumlahNumeric = (int) $jumlahClean;

            // Validate request
            $validated = $request->validate([
                'penjualan_id' => 'required|exists:penjualan,id',
                'jumlah' => 'required|string',
                'tanggal' => 'required|date',
                'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
                'kas_bank_id' => 'required|exists:kas_bank,id',
                'keterangan' => 'nullable|string|max:255',
            ], [
                'penjualan_id.required' => 'ID penjualan wajib diisi.',
                'penjualan_id.exists' => 'Penjualan tidak ditemukan.',
                'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
                'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
                'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
                'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            ]);

            // Custom validation for jumlah
            if ($jumlahNumeric < 1000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran minimal Rp 1.000.'
                ], 422);
            }

            // Update validated data with clean numeric value
            $validated['jumlah'] = $jumlahNumeric;

            // Log validated data for debugging
            Log::info('Payment validation passed', [
                'validated_data' => $validated,
                'kas_bank_id' => $validated['kas_bank_id'] ?? 'NOT_SET'
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

            /**
             * LOGIKA STATUS PEMBAYARAN:
             * 
             * 1. PEMBAYARAN PERTAMA (totalSudahBayar == 0):
             *    - Jika bayar >= total transaksi → P (Pelunasan)
             *    - Jika bayar < total transaksi → D (DP)
             * 
             * 2. PEMBAYARAN SELANJUTNYA (totalSudahBayar > 0):
             *    - Jika (totalSudahBayar + bayar) >= total transaksi → P (Pelunasan)
             *    - Jika (totalSudahBayar + bayar) < total transaksi → A (Angsuran)
             * 
             * KODE STATUS:
             * P = Pelunasan (Lunas)
             * D = DP (Down Payment)
             * A = Angsuran
             */
            $statusBayar = 'D'; // Default: DP (first payment)
            $statusPembayaran = 'dp'; // Default: DP status for penjualan

            if ($totalSudahBayar == 0) {
                // First payment
                if ($validated['jumlah'] >= $penjualan->total) {
                    $statusBayar = 'P'; // Pelunasan (full payment)
                    $statusPembayaran = 'lunas';
                } else {
                    $statusBayar = 'D'; // DP (partial payment)
                    $statusPembayaran = 'dp';
                }
            } else {
                // Subsequent payments
                $totalBayarBaru = $totalSudahBayar + $validated['jumlah'];
                if ($totalBayarBaru >= $penjualan->total) {
                    $statusBayar = 'P'; // Pelunasan (final payment)
                    $statusPembayaran = 'lunas';
                } else {
                    $statusBayar = 'A'; // Angsuran (partial payment)
                    $statusPembayaran = 'dp';
                }
            }

            // Log payment status logic for debugging
            Log::info('Payment status calculation', [
                'penjualan_id' => $penjualan->id,
                'total_penjualan' => $penjualan->total,
                'sudah_dibayar' => $totalSudahBayar,
                'jumlah_pembayaran_baru' => $validated['jumlah'],
                'total_bayar_baru' => $totalSudahBayar + $validated['jumlah'],
                'status_bayar' => $statusBayar,
                'status_pembayaran' => $statusPembayaran,
                'is_completing' => ($totalSudahBayar + $validated['jumlah']) >= $penjualan->total,
                'is_first_payment' => $totalSudahBayar == 0
            ]);

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
                'kas_bank_id' => $validated['kas_bank_id'], // Gunakan data yang sudah divalidasi
            ]);

            // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger

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
                'kas_bank_id' => $validated['kas_bank_id'],
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

    /**
     * Delete a payment
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Find payment
            $pembayaran = PembayaranPenjualan::findOrFail($id);
            $penjualan = $pembayaran->penjualan;

            // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger saat pembayaran dihapus

            // Delete payment
            $pembayaran->delete();

            // Update penjualan status
            $totalSudahBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');

            if ($totalSudahBayar == 0) {
                $statusPembayaran = 'belum_bayar';
            } elseif ($totalSudahBayar >= $penjualan->total) {
                $statusPembayaran = 'lunas';
            } else {
                $statusPembayaran = 'dp';
            }

            $penjualan->update([
                'status_pembayaran' => $statusPembayaran
            ]);

            DB::commit();

            Log::info('Payment deleted successfully', [
                'payment_id' => $id,
                'penjualan_id' => $penjualan->id,
                'user_id' => Auth::id()
            ]);

            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dihapus!',
                    'data' => [
                        'status_pembayaran' => $statusPembayaran,
                        'sisa_pembayaran' => max(0, $penjualan->total - $totalSudahBayar)
                    ]
                ]);
            }

            return redirect()->route('penjualan.show', $penjualan->encrypted_id)
                ->with('success', 'Pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment deletion failed: ' . $e->getMessage(), [
                'payment_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
