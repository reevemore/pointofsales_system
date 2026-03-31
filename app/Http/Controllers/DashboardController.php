<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $today = today();

        $todayTransactions = Transaction::whereDate('created_at', $today)->get();
        $todayRevenue      = $todayTransactions->sum('total');
        $todayCount        = $todayTransactions->count();

        // Statistik bulan ini
        $monthRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Total data
        $totalProducts = Product::where('aktif', true)->count();
        $totalMembers  = Member::count();
        $lowStock      = Product::where('stok', '<=', 5)->where('aktif', true)->count();

        // Transaksi 7 hari terakhir (untuk grafik)
        $weeklyData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produk terlaris
        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.nama', DB::raw('SUM(transaction_items.qty) as total_qty'))
            ->groupBy('products.id', 'products.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with(['user', 'member'])
            ->latest()->limit(5)->get();
            
        // Set page title & subtitle
            session(['pageTitle' => 'Dashboard']);
            session(['pageSubtitle' => 'Selamat datang kembali']);

        return Inertia::render('Dashboard', [
            'stats' => [
                'today_revenue'  => $todayRevenue,
                'today_count'    => $todayCount,
                'month_revenue'  => $monthRevenue,
                'total_products' => $totalProducts,
                'total_members'  => $totalMembers,
                'low_stock'      => $lowStock,
            ],
            'weeklyData'         => $weeklyData,
            'topProducts'        => $topProducts,
            'recentTransactions' => $recentTransactions,
        ]);
    }

}