<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKasBank;
use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKasBankController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiKasBank::with(['kasBank', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan kas/bank
        if ($request->filled('kas_bank_id')) {
            $query->where('kas_bank_id', $request->kas_bank_id);
        }

        // Filter berdasarkan kategori transaksi
        if ($request->filled('kategori_transaksi')) {
            $query->where('kategori_transaksi', $request->kategori_transaksi);
        }

        // Filter berdasarkan jenis transaksi (D/K)
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        $transaksi = $query->paginate(15);

        // Data untuk filter
        $kasBankList = KasBank::orderBy('nama')->get();

        // Hitung total debet dan kredit untuk hari ini saja
        $today = now()->format('Y-m-d');
        $totalDebet = TransaksiKasBank::where('jenis_transaksi', 'D')
            ->whereDate('tanggal', $today);
        $totalKredit = TransaksiKasBank::where('jenis_transaksi', 'K')
            ->whereDate('tanggal', $today);

        // Filter berdasarkan kas/bank jika ada
        if ($request->filled('kas_bank_id')) {
            $totalDebet->where('kas_bank_id', $request->kas_bank_id);
            $totalKredit->where('kas_bank_id', $request->kas_bank_id);
        }

        $totalDebet = $totalDebet->sum('jumlah');
        $totalKredit = $totalKredit->sum('jumlah');

        return view('transaksi-kas-bank.index', compact(
            'transaksi',
            'kasBankList',
            'totalDebet',
            'totalKredit'
        ));
    }

    public function create()
    {
        $kasBankList = KasBank::orderBy('nama')->get();

        return view('transaksi-kas-bank.create', compact('kasBankList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_hidden' => 'required|date',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'kategori_transaksi' => 'required|in:PJ,PB,MN,TF',
            'jenis_transaksi' => 'required|in:D,K',
            'jumlah_raw' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Use jumlah_raw instead of jumlah
        $jumlah = $request->jumlah_raw;

        DB::beginTransaction();
        try {
            // Generate nomor bukti
            $prefix = 'TRX';
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

            // Ambil kas/bank untuk mendapatkan saldo terkini
            $kasBank = KasBank::find($request->kas_bank_id);

            // Buat transaksi
            $transaksi = TransaksiKasBank::create([
                'no_bukti' => $noBukti,
                'tanggal' => $request->tanggal_hidden,
                'kas_bank_id' => $request->kas_bank_id,
                'jenis_transaksi' => $request->jenis_transaksi,
                'kategori_transaksi' => $request->kategori_transaksi,
                'jumlah' => $jumlah,
                'keterangan' => $request->keterangan,
                'referensi_tipe' => 'MN',
                'referensi_id' => null,
                'user_id' => auth()->id(),
            ]);

            // Update saldo kas/bank
            if ($request->jenis_transaksi == 'D') {
                $kasBank->saldo_terkini += $jumlah;
            } else {
                $kasBank->saldo_terkini -= $jumlah;
            }
            $kasBank->save();

            DB::commit();

            return redirect()->route('transaksi-kas-bank.index')
                ->with('success', 'Transaksi kas/bank berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = TransaksiKasBank::with(['kasBank'])->findOrFail($id);

        return view('transaksi-kas-bank.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = TransaksiKasBank::findOrFail($id);
        $kasBankList = KasBank::orderBy('nama')->get();

        return view('transaksi-kas-bank.edit', compact('transaksi', 'kasBankList'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiKasBank::findOrFail($id);

        $request->validate([
            'tanggal_hidden' => 'required|date',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'kategori_transaksi' => 'required|in:PJ,PB,MN,TF',
            'jenis_transaksi' => 'required|in:D,K',
            'jumlah_raw' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Use jumlah_raw instead of jumlah
        $jumlah = $request->jumlah_raw;

        DB::beginTransaction();
        try {
            // Kembalikan saldo lama
            $oldKasBank = KasBank::find($transaksi->kas_bank_id);
            if ($transaksi->jenis_transaksi == 'D') {
                $oldKasBank->saldo_terkini -= $transaksi->jumlah;
            } else {
                $oldKasBank->saldo_terkini += $transaksi->jumlah;
            }
            $oldKasBank->save();

            // Ambil kas/bank baru untuk mendapatkan saldo terkini
            $newKasBank = KasBank::find($request->kas_bank_id);
            $saldoSebelum = $newKasBank->saldo_terkini;

            // Hitung saldo sesudah
            if ($request->jenis_transaksi == 'D') {
                $saldoSesudah = $saldoSebelum + $jumlah;
            } else {
                $saldoSesudah = $saldoSebelum - $jumlah;
            }

            // Update transaksi
            $transaksi->update([
                'tanggal' => $request->tanggal_hidden,
                'kas_bank_id' => $request->kas_bank_id,
                'kategori_transaksi' => $request->kategori_transaksi,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jumlah' => $jumlah,
                'keterangan' => $request->keterangan,
            ]);

            // Update saldo baru
            $newKasBank->saldo_terkini = $saldoSesudah;
            $newKasBank->save();

            DB::commit();

            return redirect()->route('transaksi-kas-bank.index')
                ->with('success', 'Transaksi kas/bank berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaksi = TransaksiKasBank::findOrFail($id);

        // Hanya transaksi manual yang bisa dihapus
        if ($transaksi->referensi_tipe !== 'MN') {
            return back()->with('error', 'Transaksi otomatis tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan saldo
            $kasBank = KasBank::find($transaksi->kas_bank_id);
            if ($transaksi->jenis_transaksi == 'D') {
                $kasBank->saldo_terkini -= $transaksi->jumlah;
            } else {
                $kasBank->saldo_terkini += $transaksi->jumlah;
            }
            $kasBank->save();

            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            return redirect()->route('transaksi-kas-bank.index')
                ->with('success', 'Transaksi kas/bank berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
        }
    }
}
