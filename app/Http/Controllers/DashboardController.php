<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Dashboard', 'pageSubtitle' => 'Ringkasan toko hari ini']);

        $tokoId = $this->tokoId();

        // Statistik hari ini
        $today = now()->toDateString();

        $transaksiHariIni = Transaction::where('toko_id', $tokoId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();

        $pendapatanHariIni = Transaction::where('toko_id', $tokoId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total');

        $totalProduk = Product::where('toko_id', $tokoId)
            ->where('aktif', true)
            ->count();

        $totalMember = Member::where('toko_id', $tokoId)
            ->where('status', true)
            ->count();

        $stokMenipis = Product::where('toko_id', $tokoId)
            ->where('aktif', true)
            ->where('stok', '<=', 10)
            ->orderBy('stok')
            ->limit(5)
            ->get(['id', 'nama', 'stok', 'satuan']);

        // Grafik 7 hari terakhir
        $grafikPenjualan = Transaction::where('toko_id', $tokoId)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', '>=', now()->subDays(6)->toDateString())
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Transaction::where('toko_id', $tokoId)
            ->with(['member', 'user'])
            ->where('payment_status', 'paid')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($t) => [
                'id'           => $t->id,
                'no_transaksi' => $t->no_transaksi,
                'total'        => $t->total,
                'metode_bayar' => $t->metode_bayar,
                'member'       => $t->member?->nama,
                'kasir'        => $t->user?->name,
                'created_at'   => $t->created_at->format('d/m/Y H:i'),
            ]);

        // Produk terlaris bulan ini
        $produkTerlaris = DB::table('transaction_items')
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.toko_id', $tokoId)
            ->where('transactions.payment_status', 'paid')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->select(
                'products.nama',
                DB::raw('SUM(transaction_items.qty) as total_qty'),
                DB::raw('SUM(transaction_items.subtotal) as total_omzet')
            )
            ->groupBy('products.id', 'products.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'transaksi_hari_ini'  => $transaksiHariIni,
                'pendapatan_hari_ini' => $pendapatanHariIni,
                'total_produk'        => $totalProduk,
                'total_member'        => $totalMember,
            ],
            'stok_menipis'     => $stokMenipis,
            'grafik_penjualan' => $grafikPenjualan,
            'transaksi_terbaru'=> $transaksiTerbaru,
            'produk_terlaris'  => $produkTerlaris,
        ]);
    }
}