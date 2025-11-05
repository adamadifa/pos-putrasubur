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

        //dd($request->all());
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

            // Check if using uang muka first (before parsing jumlah) - hanya untuk proses uang muka, bukan validasi
            $uangMukaFromRequest = $request->input('uang_muka');
            $hasUangMuka = false;
            $totalUangMukaInput = 0;
            if ($uangMukaFromRequest && is_array($uangMukaFromRequest)) {
                $validUangMuka = array_filter($uangMukaFromRequest, function ($um) {
                    return isset($um['id']) && !empty($um['id']) && isset($um['jumlah']) && floatval($um['jumlah']) > 0;
                });
                $hasUangMuka = count($validUangMuka) > 0;

                foreach ($uangMukaFromRequest as $um) {
                    if (isset($um['jumlah']) && is_numeric($um['jumlah'])) {
                        $totalUangMukaInput += floatval($um['jumlah']);
                    }
                }
            }

            // Validate request - sama seperti tanpa checklist
            $validationRules = [
                'penjualan_id' => 'required|exists:penjualan,id',
                'jumlah' => 'required|string',
                'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
                'kas_bank_id' => 'required|exists:kas_bank,id',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string|max:255',
            ];

            // Hitung jumlah setelah dikurangi uang muka untuk proses
            $jumlahSetelahUangMuka = max(0, $jumlahNumeric - $totalUangMukaInput);

            $customMessages = [
                'penjualan_id.required' => 'ID penjualan wajib diisi.',
                'penjualan_id.exists' => 'Penjualan tidak ditemukan.',
                'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
                'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
                'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
                'kas_bank_id.required' => 'Kas/Bank wajib dipilih.',
                'kas_bank_id.exists' => 'Kas/Bank tidak valid.',
                'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            ];

            $validated = $request->validate($validationRules, $customMessages);

            // Custom validation for jumlah - sama seperti tanpa checklist
            if ($jumlahNumeric < 1000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran minimal Rp 1.000.'
                ], 422);
            }

            // Update validated data with clean numeric value
            $validated['jumlah'] = $jumlahSetelahUangMuka; // Jumlah setelah dikurangi uang muka

            // Log validated data for debugging
            Log::info('Payment validation passed', [
                'validated_data' => $validated,
                'kas_bank_id' => $validated['kas_bank_id'] ?? 'NOT_SET'
            ]);

            DB::beginTransaction();

            // Get penjualan data
            $penjualan = Penjualan::findOrFail($validated['penjualan_id']);

            // Handle uang muka jika ada
            $totalUangMukaDigunakan = 0;
            $uangMukaData = [];

            if ($request->filled('uang_muka') && is_array($request->uang_muka)) {
                foreach ($request->uang_muka as $umItem) {
                    $uangMukaIdRaw = $umItem['id'] ?? '';
                    try {
                        $uangMukaId = decrypt($uangMukaIdRaw);
                    } catch (\Exception $e) {
                        // If decrypt fails, assume it's already a plain ID
                        $uangMukaId = $uangMukaIdRaw;
                    }
                    $jumlahDigunakan = floatval($umItem['jumlah'] ?? 0);

                    if ($uangMukaId && $jumlahDigunakan > 0) {
                        $uangMuka = \App\Models\UangMukaPelanggan::find($uangMukaId);
                        if ($uangMuka && $uangMuka->pelanggan_id == $penjualan->pelanggan_id) {
                            // Validasi: jumlah tidak boleh lebih dari sisa
                            if ($jumlahDigunakan > $uangMuka->sisa_uang_muka) {
                                DB::rollback();
                                return response()->json([
                                    'success' => false,
                                    'message' => "Jumlah uang muka yang digunakan melebihi sisa uang muka untuk {$uangMuka->no_uang_muka}"
                                ], 422);
                            }

                            $uangMukaData[] = [
                                'uang_muka' => $uangMuka,
                                'uang_muka_id' => $uangMukaId,
                                'jumlah_digunakan' => $jumlahDigunakan,
                            ];

                            $totalUangMukaDigunakan += $jumlahDigunakan;
                        }
                    }
                }
            }

            // Check if payment amount (including uang muka) exceeds remaining amount
            $totalSudahBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            $sisaPembayaran = $penjualan->total - $totalSudahBayar;
            $jumlahPembayaran = isset($validated['jumlah']) ? (float)$validated['jumlah'] : 0;
            $totalPayment = $jumlahPembayaran + $totalUangMukaDigunakan;

            if ($totalPayment > $sisaPembayaran) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran (termasuk uang muka) melebihi sisa pembayaran yang harus dibayar.'
                ], 400);
            }

            if ($totalUangMukaDigunakan > $sisaPembayaran) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Total uang muka yang digunakan melebihi sisa pembayaran yang harus dibayar.'
                ], 400);
            }

            // Generate payment reference number
            $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($penjualan->pembayaranPenjualan->count() + 1, 2, '0', STR_PAD_LEFT);

            /**
             * LOGIKA STATUS PEMBAYARAN:
             * 
             * 1. PEMBAYARAN PERTAMA (totalSudahBayar == 0):
             *    - Jika (bayar + uang muka) >= total transaksi → P (Pelunasan)
             *    - Jika (bayar + uang muka) < total transaksi → D (DP)
             * 
             * 2. PEMBAYARAN SELANJUTNYA (totalSudahBayar > 0):
             *    - Jika (totalSudahBayar + bayar + uang muka) >= total transaksi → P (Pelunasan)
             *    - Jika (totalSudahBayar + bayar + uang muka) < total transaksi → A (Angsuran)
             * 
             * KODE STATUS:
             * P = Pelunasan (Lunas)
             * D = DP (Down Payment)
             * A = Angsuran
             * U = Uang Muka (hanya untuk record PembayaranPenjualan dengan status_bayar = 'U')
             */
            $totalBayarBaru = $totalSudahBayar + $validated['jumlah'] + $totalUangMukaDigunakan;
            $statusBayar = 'D'; // Default: DP (first payment)
            $statusPembayaran = 'dp'; // Default: DP status for penjualan

            if ($totalSudahBayar == 0) {
                // First payment
                if ($totalBayarBaru >= $penjualan->total) {
                    $statusBayar = 'P'; // Pelunasan (full payment)
                    $statusPembayaran = 'lunas';
                } else {
                    $statusBayar = 'D'; // DP (partial payment)
                    $statusPembayaran = 'dp';
                }
            } else {
                // Subsequent payments
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
                'uang_muka_digunakan' => $totalUangMukaDigunakan,
                'total_bayar_baru' => $totalBayarBaru,
                'status_bayar' => $statusBayar,
                'status_pembayaran' => $statusPembayaran,
                'is_completing' => $totalBayarBaru >= $penjualan->total,
                'is_first_payment' => $totalSudahBayar == 0
            ]);

            // Create payment record (hanya jika ada jumlah pembayaran > 0)
            $pembayaran = null;
            if ($validated['jumlah'] > 0) {
                $pembayaran = PembayaranPenjualan::create([
                    'penjualan_id' => $validated['penjualan_id'],
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $validated['jumlah'],
                    'metode_pembayaran' => $validated['metode_pembayaran'] ?? null,
                    'status_bayar' => $statusBayar,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'user_id' => Auth::id(),
                    'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                ]);
            }

            // Process uang muka jika ada
            if ($totalUangMukaDigunakan > 0 && !empty($uangMukaData)) {
                foreach ($uangMukaData as $umData) {
                    // Update sisa uang muka
                    $uangMuka = $umData['uang_muka'];
                    $uangMuka->sisa_uang_muka -= $umData['jumlah_digunakan'];
                    if ($uangMuka->sisa_uang_muka <= 0) {
                        $uangMuka->status = 'habis';
                    }
                    $uangMuka->save();

                    // Buat record penggunaan uang muka
                    $keteranganPenggunaan = $pembayaran && $noBukti ? "Penggunaan uang muka untuk pembayaran " . $noBukti : "Penggunaan uang muka untuk penjualan " . $penjualan->no_faktur;
                    \App\Models\PenggunaanUangMukaPenjualan::create([
                        'uang_muka_pelanggan_id' => $umData['uang_muka_id'],
                        'penjualan_id' => $penjualan->id,
                        'jumlah_digunakan' => $umData['jumlah_digunakan'],
                        'tanggal_penggunaan' => $validated['tanggal'],
                        'keterangan' => $keteranganPenggunaan,
                        'user_id' => Auth::id(),
                    ]);

                    // Buat record PembayaranPenjualan untuk uang muka
                    // dengan kas_bank_id = null agar tidak update saldo kas bank
                    $noBuktiUm = 'PAY-UM-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($umData['uang_muka_id'], 3, '0', STR_PAD_LEFT);

                    PembayaranPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'no_bukti' => $noBuktiUm,
                        'tanggal' => $validated['tanggal'],
                        'jumlah_bayar' => $umData['jumlah_digunakan'],
                        'metode_pembayaran' => $uangMuka->metode_pembayaran,
                        'status_bayar' => 'U', // Uang Muka
                        'keterangan' => "Pembayaran dari uang muka " . $uangMuka->no_uang_muka,
                        'user_id' => Auth::id(),
                        'kas_bank_id' => null, // NULL agar tidak update saldo kas bank
                    ]);
                }
            }

            // Update status pembayaran dengan mempertimbangkan uang muka (sudah dihitung di $totalBayarBaru)
            if ($totalBayarBaru >= $penjualan->total) {
                $statusPembayaran = 'lunas';
            } elseif ($totalBayarBaru > 0) {
                $statusPembayaran = 'dp';
            } else {
                $statusPembayaran = 'belum_bayar';
            }

            $penjualan->update([
                'status_pembayaran' => $statusPembayaran
            ]);

            // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger (hanya untuk pembayaran biasa, bukan uang muka)

            DB::commit();

            // Log successful payment
            Log::info('Payment created successfully', [
                'payment_id' => $pembayaran ? $pembayaran->id : null,
                'penjualan_id' => $penjualan->id,
                'amount' => $validated['jumlah'],
                'uang_muka_digunakan' => $totalUangMukaDigunakan,
                'method' => $validated['metode_pembayaran'] ?? null,
                'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                'user_id' => Auth::id()
            ]);

            $responseMessage = 'Pembayaran berhasil disimpan!';
            if ($totalUangMukaDigunakan > 0 && $validated['jumlah'] == 0) {
                $responseMessage = 'Pembayaran menggunakan uang muka berhasil disimpan!';
            } elseif ($totalUangMukaDigunakan > 0) {
                $responseMessage = "Pembayaran berhasil disimpan! (Rp " . number_format($validated['jumlah'], 0, ',', '.') . " + Uang Muka Rp " . number_format($totalUangMukaDigunakan, 0, ',', '.') . ")";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'data' => [
                    'payment_id' => $pembayaran ? $pembayaran->id : null,
                    'no_bukti' => $pembayaran ? $noBukti : null,
                    'jumlah_bayar' => $validated['jumlah'],
                    'uang_muka_digunakan' => $totalUangMukaDigunakan,
                    'status_pembayaran' => $statusPembayaran,
                    'sisa_pembayaran' => max(0, $penjualan->total - $totalBayarBaru)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = implode(', ', $messages);
            }
            $errorMessage = !empty($errorMessages) ? implode(' | ', $errorMessages) : 'Validasi gagal';

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
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

            // Handle uang muka jika pembayaran menggunakan uang muka (status_bayar = 'U')
            if ($pembayaran->status_bayar === 'U') {
                // Cari record penggunaan uang muka yang terkait dengan pembayaran ini
                $penggunaanUangMuka = \App\Models\PenggunaanUangMukaPenjualan::where('penjualan_id', $penjualan->id)
                    ->where('jumlah_digunakan', $pembayaran->jumlah_bayar)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($penggunaanUangMuka) {
                    $uangMuka = $penggunaanUangMuka->uangMukaPelanggan;
                    $jumlahDigunakan = $penggunaanUangMuka->jumlah_digunakan;

                    // Kembalikan sisa uang muka (tambah kembali jumlah yang digunakan)
                    $uangMuka->sisa_uang_muka += $jumlahDigunakan;

                    // Update status uang muka jika perlu
                    // Jika sisa kembali > 0 dan status 'habis', ubah ke 'aktif'
                    if ($uangMuka->sisa_uang_muka > 0 && $uangMuka->status === 'habis') {
                        $uangMuka->status = 'aktif';
                    }

                    $uangMuka->save();

                    // Hapus record penggunaan uang muka
                    $penggunaanUangMuka->delete();

                    Log::info('Uang muka dikembalikan setelah pembayaran dihapus', [
                        'pembayaran_id' => $pembayaran->id,
                        'uang_muka_id' => $uangMuka->id,
                        'jumlah_dikembalikan' => $jumlahDigunakan,
                        'sisa_uang_muka_baru' => $uangMuka->sisa_uang_muka,
                        'status_uang_muka' => $uangMuka->status
                    ]);
                } else {
                    Log::warning('Penggunaan uang muka tidak ditemukan untuk pembayaran yang dihapus', [
                        'pembayaran_id' => $pembayaran->id,
                        'penjualan_id' => $penjualan->id,
                        'jumlah_bayar' => $pembayaran->jumlah_bayar
                    ]);
                }
            }

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

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus!'
            ]);
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
