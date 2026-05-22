<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Exports\LaporanProfitExport;
use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    use HasTokoScope;

    public function index(Request $request)
    {
        session(['pageTitle' => 'Laporan Penjualan', 'pageSubtitle' => '']);

        $query = $this->scopedQuery(Transaction::query())
            ->with(['items.product', 'member', 'user'])
            ->where('payment_status', 'paid');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transactions = $query->latest()->get();

        $summary = [
            'total_transaksi' => $transactions->count(),
            'total_pendapatan' => $transactions->sum('total'),
            'total_diskon'     => $transactions->sum('diskon'),
        ];

        return Inertia::render('Laporan/Index', [
            'transactions' => $transactions,
            'summary'      => $summary,
            'filters'      => $request->only(['dari', 'sampai']),
        ]);
    }

    public function profit(Request $request)
    {
        session(['pageTitle' => 'Laporan Profit', 'pageSubtitle' => '']);

        $query = $this->scopedQuery(Transaction::query())
            ->with(['items.product'])
            ->where('payment_status', 'paid');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transactions = $query->latest()->get();

        // Hitung profit per transaksi
        $data = $transactions->map(function ($trx) {
            $hpp = $trx->items->sum(fn($item) => $item->product->harga_beli * $item->qty);
            return [
                'id'           => $trx->id,
                'no_transaksi' => $trx->no_transaksi,
                'total'        => $trx->total,
                'hpp'          => $hpp,
                'profit'       => $trx->total - $hpp,
                'created_at'   => $trx->created_at,
            ];
        });

        $summary = [
            'total_pendapatan' => $data->sum('total'),
            'total_hpp'        => $data->sum('hpp'),
            'total_profit'     => $data->sum('profit'),
        ];

        return Inertia::render('Laporan/Profit', [
            'data'    => $data,
            'summary' => $summary,
            'filters' => $request->only(['dari', 'sampai']),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = $this->scopedQuery(Transaction::query())
            ->with(['items.product', 'member'])
            ->where('payment_status', 'paid');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transactions = $query->latest()->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('transactions'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['dari', 'sampai']);
        $tokoId  = $this->tokoId();

        return Excel::download(new LaporanExport($filters, $tokoId), 'laporan-penjualan.xlsx');
    }

    public function profitExportExcel(Request $request)
    {
        $filters = $request->only(['dari', 'sampai']);
        $tokoId  = $this->tokoId();

        return Excel::download(new LaporanProfitExport($filters, $tokoId), 'laporan-profit.xlsx');
    }

    public function profitExportPdf(Request $request)
    {
        $query = $this->scopedQuery(Transaction::query())
            ->with(['items.product'])
            ->where('payment_status', 'paid');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transactions = $query->latest()->get();

        $data = $transactions->map(function ($trx) {
            $hpp = $trx->items->sum(fn($item) => $item->product->harga_beli * $item->qty);
            return [
                'no_transaksi' => $trx->no_transaksi,
                'total'        => $trx->total,
                'hpp'          => $hpp,
                'profit'       => $trx->total - $hpp,
                'created_at'   => $trx->created_at,
            ];
        });

        $pdf = Pdf::loadView('laporan.profit-pdf', compact('data'));
        return $pdf->download('laporan-profit.pdf');
    }
}