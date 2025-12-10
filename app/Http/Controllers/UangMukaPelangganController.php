<?php

namespace App\Http\Controllers;

use App\Models\UangMukaPelanggan;
use App\Models\Pelanggan;
use App\Models\KasBank;
use App\Models\TransaksiKasBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UangMukaPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UangMukaPelanggan::with(['pelanggan', 'user', 'kasBank', 'penggunaanPenjualan'])
            ->withSum('penggunaanPenjualan', 'jumlah_digunakan');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pelanggan
        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_uang_muka', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $uangMuka = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pelanggan = Pelanggan::where('status', true)->orderBy('nama')->get();

        // Statistics
        $totalUangMuka = UangMukaPelanggan::sum('jumlah_uang_muka');
        $totalDigunakan = UangMukaPelanggan::with('penggunaanPenjualan')->get()
            ->sum(function ($um) {
                return $um->penggunaanPenjualan->sum('jumlah_digunakan');
            });
        $totalSisa = $totalUangMuka - $totalDigunakan;
        $totalAktif = UangMukaPelanggan::where('status', 'aktif')->count();

        return view('uang-muka-pelanggan.index', compact(
            'uangMuka',
            'pelanggan',
            'totalUangMuka',
            'totalDigunakan',
            'totalSisa',
            'totalAktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pelanggan = Pelanggan::where('status', true)->orderBy('nama')->get();
        $kasBank = KasBank::orderBy('nama')->get();
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // If AJAX request, return only form HTML
        if ($request->ajax()) {
            return view('uang-muka-pelanggan._form', compact('pelanggan', 'kasBank', 'metodePembayaran'));
        }

        return view('uang-muka-pelanggan.create', compact('pelanggan', 'kasBank', 'metodePembayaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Clean jumlah_uang_muka dari format currency (jika ada)
        $request->merge([
            'jumlah_uang_muka' => preg_replace('/[^\d]/', '', $request->jumlah_uang_muka)
        ]);

        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'tanggal' => 'required|date',
            'jumlah_uang_muka' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'pelanggan_id.required' => 'Pilih pelanggan terlebih dahulu.',
            'pelanggan_id.exists' => 'Pelanggan yang dipilih tidak valid.',
            'tanggal.required' => 'Pilih tanggal terlebih dahulu.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jumlah_uang_muka.required' => 'Masukkan jumlah uang muka.',
            'jumlah_uang_muka.numeric' => 'Jumlah uang muka harus berupa angka.',
            'jumlah_uang_muka.min' => 'Jumlah uang muka minimal 1.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'metode_pembayaran.exists' => 'Metode pembayaran yang dipilih tidak valid.',
            'kas_bank_id.required' => 'Pilih kas/bank terlebih dahulu.',
            'kas_bank_id.exists' => 'Kas/bank yang dipilih tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor uang muka
            $noUangMuka = $this->generateNoUangMuka();

            // Create uang muka
            $uangMuka = UangMukaPelanggan::create([
                'no_uang_muka' => $noUangMuka,
                'pelanggan_id' => $validated['pelanggan_id'],
                'tanggal' => $validated['tanggal'],
                'jumlah_uang_muka' => $validated['jumlah_uang_muka'],
                'sisa_uang_muka' => $validated['jumlah_uang_muka'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'],
                'status' => 'aktif',
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => Auth::id(),
            ]);

            // Update saldo kas/bank (Debet = penerimaan)
            $kasBank = KasBank::find($validated['kas_bank_id']);
            $kasBank->updateSaldo($validated['jumlah_uang_muka'], 'D');

            // Catat transaksi kas/bank
            $this->catatTransaksiKasBank($uangMuka, $kasBank);

            DB::commit();

            return redirect()->route('uang-muka-pelanggan.index')
                ->with('success', 'Uang muka pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            $uangMuka = UangMukaPelanggan::with([
                'pelanggan',
                'user',
                'kasBank',
                'penggunaanPenjualan.penjualan',
                'penggunaanPenjualan.user',
                'pengembalianUang.kasBank',
                'pengembalianUang.user'
            ])->findOrFail($id);

            return view('uang-muka-pelanggan.show', compact('uangMuka'));
        } catch (\Exception $e) {
            return redirect()->route('uang-muka-pelanggan.index')
                ->with('error', 'Uang muka tidak ditemukan.');
        }
    }

    /**
     * Cancel uang muka (batalkan)
     */
    public function cancel($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = decrypt($encryptedId);
            $uangMuka = UangMukaPelanggan::with('penggunaanPenjualan')->findOrFail($id);

            // Cek apakah sudah digunakan
            if ($uangMuka->penggunaanPenjualan->isNotEmpty()) {
                return redirect()->route('uang-muka-pelanggan.index')
                    ->with('error', 'Uang muka tidak dapat dibatalkan karena sudah digunakan pada faktur penjualan.');
            }

            // Cek apakah status aktif
            if ($uangMuka->status !== 'aktif') {
                return redirect()->route('uang-muka-pelanggan.index')
                    ->with('error', 'Uang muka sudah tidak aktif.');
            }

            // Update status
            $uangMuka->update(['status' => 'dibatalkan']);

            // Kurangi saldo kas/bank (Kredit = mengurangi saldo)
            $kasBank = $uangMuka->kasBank;
            if ($kasBank) {
                $kasBank->updateSaldo($uangMuka->jumlah_uang_muka, 'K');
            }

            DB::commit();

            return redirect()->route('uang-muka-pelanggan.index')
                ->with('success', 'Uang muka berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('uang-muka-pelanggan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (delete permanen)
     */
    public function destroy($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = decrypt($encryptedId);
            $uangMuka = UangMukaPelanggan::with('penggunaanPenjualan')->findOrFail($id);

            // Cek apakah sudah digunakan pada faktur penjualan
            if ($uangMuka->penggunaanPenjualan->isNotEmpty()) {
                return redirect()->route('uang-muka-pelanggan.index')
                    ->with('error', 'Uang muka tidak dapat dihapus karena sudah digunakan pada faktur penjualan.');
            }

            // Hapus transaksi kas/bank terkait
            $transaksiKasBank = TransaksiKasBank::where('referensi_tipe', 'UMP')
                ->where('referensi_id', $uangMuka->id)
                ->first();

            // Kurangi saldo kas/bank jika ada transaksi
            if ($transaksiKasBank) {
                $kasBank = $uangMuka->kasBank;
                if ($kasBank) {
                    // Kurangi saldo (karena transaksi asalnya adalah Debet, maka kurangi dengan Kredit)
                    $kasBank->updateSaldo($uangMuka->jumlah_uang_muka, 'K');
                }
                $transaksiKasBank->delete();
            } else {
                // Jika tidak ada transaksi kas/bank, tetap kurangi saldo
                $kasBank = $uangMuka->kasBank;
                if ($kasBank) {
                    $kasBank->updateSaldo($uangMuka->jumlah_uang_muka, 'K');
                }
            }

            // Hapus uang muka
            $noUangMuka = $uangMuka->no_uang_muka;
            $uangMuka->delete();

            DB::commit();

            return redirect()->route('uang-muka-pelanggan.index')
                ->with('success', 'Uang muka ' . $noUangMuka . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('uang-muka-pelanggan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate nomor uang muka
     */
    private function generateNoUangMuka()
    {
        $prefix = 'UM-PEL-';
        $date = date('Ymd');
        
        $lastUangMuka = UangMukaPelanggan::where('no_uang_muka', 'like', "{$prefix}{$date}%")
            ->orderBy('no_uang_muka', 'desc')
            ->first();

        if ($lastUangMuka) {
            $lastNumber = intval(substr($lastUangMuka->no_uang_muka, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Catat transaksi kas/bank untuk uang muka
     */
    private function catatTransaksiKasBank(UangMukaPelanggan $uangMuka, KasBank $kasBank)
    {
        // Generate nomor bukti
        $prefix = 'UM-PEL';
        $date = date('Ymd');
        $lastTransaksi = TransaksiKasBank::where('no_bukti', 'like', "{$prefix}{$date}%")
            ->orderBy('no_bukti', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->no_bukti, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $noBukti = $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Buat transaksi kas/bank
        TransaksiKasBank::create([
            'kas_bank_id' => $kasBank->id,
            'tanggal' => $uangMuka->tanggal->toDateString(),
            'no_bukti' => $noBukti,
            'jenis_transaksi' => 'D', // Debet = penerimaan
            'kategori_transaksi' => 'PJ', // Penjualan (uang muka)
            'referensi_id' => $uangMuka->id,
            'referensi_tipe' => 'UMP', // Uang Muka Pelanggan
            'jumlah' => $uangMuka->jumlah_uang_muka,
            'keterangan' => "Uang muka pelanggan {$uangMuka->no_uang_muka} - {$uangMuka->pelanggan->nama}",
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Return uang muka (pengembalian sisa uang muka)
     */
    public function return($encryptedId, Request $request)
    {
        // Clean jumlah_kembali dari format currency (jika ada)
        $request->merge([
            'jumlah_kembali' => preg_replace('/[^\d]/', '', $request->jumlah_kembali ?? '0')
        ]);

        $validated = $request->validate([
            'jumlah_kembali' => 'required|numeric|min:1',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah_kembali.required' => 'Masukkan jumlah yang akan dikembalikan.',
            'jumlah_kembali.numeric' => 'Jumlah harus berupa angka.',
            'jumlah_kembali.min' => 'Jumlah minimal 1.',
            'kas_bank_id.required' => 'Pilih kas/bank terlebih dahulu.',
            'kas_bank_id.exists' => 'Kas/bank yang dipilih tidak valid.',
            'tanggal.required' => 'Pilih tanggal terlebih dahulu.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $id = decrypt($encryptedId);
            $uangMuka = UangMukaPelanggan::findOrFail($id);

            // Cek apakah status aktif
            if ($uangMuka->status !== 'aktif') {
                return back()->with('error', 'Uang muka tidak aktif atau sudah habis.');
            }

            // Cek apakah ada sisa uang muka
            if ($uangMuka->sisa_uang_muka <= 0) {
                return back()->with('error', 'Tidak ada sisa uang muka yang dapat dikembalikan.');
            }

            // Cek apakah jumlah yang dikembalikan tidak melebihi sisa
            if ($validated['jumlah_kembali'] > $uangMuka->sisa_uang_muka) {
                return back()->with('error', 'Jumlah yang dikembalikan tidak boleh melebihi sisa uang muka.');
            }

            // Update sisa uang muka
            $sisaBaru = $uangMuka->sisa_uang_muka - $validated['jumlah_kembali'];
            $uangMuka->update([
                'sisa_uang_muka' => $sisaBaru,
                'status' => $sisaBaru <= 0 ? 'habis' : 'aktif'
            ]);

            // Update saldo kas/bank (Kredit = mengurangi saldo kas perusahaan? pengembalian ke pelanggan berarti keluar uang -> Kredit)
            $kasBank = KasBank::find($validated['kas_bank_id']);
            $kasBank->updateSaldo($validated['jumlah_kembali'], 'K');

            // Catat transaksi kas/bank untuk pengembalian
            $this->catatTransaksiKasBankReturn($uangMuka, $kasBank, $validated);

            DB::commit();

            return redirect()->route('uang-muka-pelanggan.show', $encryptedId)
                ->with('success', 'Pengembalian uang muka berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Catat transaksi kas/bank untuk pengembalian uang muka
     */
    private function catatTransaksiKasBankReturn(UangMukaPelanggan $uangMuka, KasBank $kasBank, array $data)
    {
        // Generate nomor bukti
        $prefix = 'RT-UM-PEL';
        $date = date('Ymd', strtotime($data['tanggal']));
        $lastTransaksi = TransaksiKasBank::where('no_bukti', 'like', "{$prefix}{$date}%")
            ->orderBy('no_bukti', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->no_bukti, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $noBukti = $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Buat transaksi kas/bank
        TransaksiKasBank::create([
            'kas_bank_id' => $kasBank->id,
            'tanggal' => $data['tanggal'],
            'no_bukti' => $noBukti,
            'jenis_transaksi' => 'K', // Kredit = pengeluaran (kembalikan ke pelanggan)
            'kategori_transaksi' => 'PJ', // Penjualan
            'referensi_id' => $uangMuka->id,
            'referensi_tipe' => 'UMP', // Uang Muka Pelanggan
            'jumlah' => $data['jumlah_kembali'],
            'keterangan' => "Pengembalian uang muka pelanggan {$uangMuka->no_uang_muka} - {$uangMuka->pelanggan->nama}" .
                ($data['keterangan'] ? " - {$data['keterangan']}" : ''),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Delete pengembalian uang muka
     */
    public function deleteReturn($encryptedId, $transaksiId)
    {
        DB::beginTransaction();
        try {
            $id = decrypt($encryptedId);
            $uangMuka = UangMukaPelanggan::findOrFail($id);
            
            // Cari transaksi pengembalian
            $transaksi = TransaksiKasBank::where('id', $transaksiId)
                ->where('referensi_id', $uangMuka->id)
                ->where('referensi_tipe', 'UMP')
                ->where('jenis_transaksi', 'K')
                ->where('no_bukti', 'like', 'RT-UM-PEL%')
                ->firstOrFail();

            // Kembalikan saldo kas/bank (Debet = menambah saldo karena menghapus transaksi Kredit)
            $kasBank = $transaksi->kasBank;
            if ($kasBank) {
                $kasBank->updateSaldo($transaksi->jumlah, 'D');
            }

            // Update sisa uang muka (tambah kembali jumlah yang dikembalikan)
            $sisaBaru = $uangMuka->sisa_uang_muka + $transaksi->jumlah;
            $uangMuka->update([
                'sisa_uang_muka' => $sisaBaru,
                'status' => 'aktif' // Kembalikan status ke aktif jika sebelumnya habis
            ]);

            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            return redirect()->route('uang-muka-pelanggan.show', $encryptedId)
                ->with('success', 'Pengembalian uang muka berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('uang-muka-pelanggan.show', $encryptedId)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get available uang muka for pelanggan (for AJAX)
     */
    public function getAvailableUangMuka(Request $request)
    {
        $pelangganId = $request->pelanggan_id;
        
        $uangMuka = UangMukaPelanggan::where('pelanggan_id', $pelangganId)
            ->where('status', 'aktif')
            ->where('sisa_uang_muka', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($um) {
                return [
                    'id' => $um->id,
                    'no_uang_muka' => $um->no_uang_muka,
                    'sisa_uang_muka' => $um->sisa_uang_muka,
                    'tanggal' => $um->tanggal->format('d/m/Y'),
                    'jumlah_uang_muka' => $um->jumlah_uang_muka,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $uangMuka
        ]);
    }
}
