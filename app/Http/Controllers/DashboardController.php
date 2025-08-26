<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PembayaranPenjualan;
use App\Models\PembayaranPembelian;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Get chart period from request, default to monthly
        $chartPeriod = $request->get('chart_period', 'monthly');

        // Today's Sales
        $todaySales = Penjualan::whereDate('tanggal', $today)->sum('total');
        $yesterdaySales = Penjualan::whereDate('tanggal', $yesterday)->sum('total');
        $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;

        // Today's Transactions
        $todayTransactions = Penjualan::whereDate('tanggal', $today)->count();
        $yesterdayTransactions = Penjualan::whereDate('tanggal', $yesterday)->count();
        $transactionGrowth = $yesterdayTransactions > 0 ? (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100 : 0;

        // New Customers (this month)
        $newCustomers = Pelanggan::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();
        $lastMonthCustomers = Pelanggan::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $customerGrowth = $lastMonthCustomers > 0 ? (($newCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : 0;

        // Low Stock Products
        $lowStockProducts = Produk::where('stok', '<=', 10)->count();

        // Recent Transactions
        $recentTransactions = Penjualan::with(['pelanggan', 'kasir'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Recent Payments
        $recentPayments = PembayaranPenjualan::with(['penjualan.pelanggan', 'user'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Sales Chart Data based on period
        $monthlySales = $this->getSalesChartData($chartPeriod);



        // Payment Methods Distribution
        $paymentMethods = PembayaranPenjualan::selectRaw('metode_pembayaran, COUNT(*) as count, SUM(jumlah_bayar) as total')
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->groupBy('metode_pembayaran')
            ->get();

        // Purchase vs Sales (based on chart period)
        $chartStartDate = $this->getChartStartDate($chartPeriod);
        $monthlyPurchases = Pembelian::whereBetween('tanggal', [$chartStartDate, $today])->sum('total');
        $monthlySalesTotal = Penjualan::whereBetween('tanggal', [$chartStartDate, $today])->sum('total');

        // Outstanding Payments
        $outstandingPayments = Penjualan::where('status_pembayaran', '!=', 'Lunas')->count();
        $outstandingPurchases = Pembelian::where('status_pembayaran', '!=', 'Lunas')->count();

        return view('dashboard', compact(
            'todaySales',
            'salesGrowth',
            'todayTransactions',
            'transactionGrowth',
            'newCustomers',
            'customerGrowth',
            'lowStockProducts',
            'recentTransactions',
            'recentPayments',
            'monthlySales',
            'paymentMethods',
            'monthlyPurchases',
            'monthlySalesTotal',
            'outstandingPayments',
            'outstandingPurchases',
            'chartPeriod'
        ));
    }

    private function getSalesChartData($period)
    {
        $today = Carbon::today();
        $startDate = $this->getChartStartDate($period);

        switch ($period) {
            case 'weekly':
                $groupBy = 'DATE(tanggal)';
                break;

            case 'monthly':
                $groupBy = 'DATE(tanggal)';
                break;

            case 'yearly':
                $groupBy = 'DATE_FORMAT(tanggal, "%Y-%m")';
                break;

            default:
                $groupBy = 'DATE(tanggal)';
        }

        // Get sales data
        $salesData = Penjualan::selectRaw("$groupBy as date, SUM(total) as total_sales")
            ->whereBetween('tanggal', [$startDate, $today])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get purchase data
        $purchaseData = Pembelian::selectRaw("$groupBy as date, SUM(total) as total_purchases")
            ->whereBetween('tanggal', [$startDate, $today])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Combine the data
        $combinedData = [];

        // Get all unique dates
        $allDates = collect();
        $salesData->each(function ($item) use ($allDates) {
            $allDates->push($item->date);
        });
        $purchaseData->each(function ($item) use ($allDates) {
            $allDates->push($item->date);
        });
        $allDates = $allDates->unique()->sort();

        // Create combined data structure
        foreach ($allDates as $date) {
            $salesItem = $salesData->where('date', $date)->first();
            $purchaseItem = $purchaseData->where('date', $date)->first();

            $combinedData[] = [
                'date' => $date,
                'total_sales' => $salesItem ? $salesItem->total_sales : 0,
                'total_purchases' => $purchaseItem ? $purchaseItem->total_purchases : 0
            ];
        }

        return $combinedData;
    }

    private function getChartStartDate($period)
    {
        $today = Carbon::today();

        switch ($period) {
            case 'weekly':
                return $today->copy()->subDays(6);
            case 'monthly':
                return $today->copy()->startOfMonth();
            case 'yearly':
                return $today->copy()->subMonths(11)->startOfMonth();
            default:
                return $today->copy()->startOfMonth();
        }
    }
}
