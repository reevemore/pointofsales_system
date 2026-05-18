<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\LaporanExport;
use App\Exports\LaporanProfitExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $transactions = Transaction::with(['user', 'member', 'items.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        $total = $transactions->sum('total');

        return Inertia::render('Laporan/Index', [
            'transactions' => $transactions,
            'total'        => $total,
            'from'         => $from,
            'to'           => $to,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $transactions = Transaction::with(['user', 'member', 'items.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        $total = $transactions->sum('total');

        $pdf = Pdf::loadView('laporan.pdf', compact('transactions', 'total', 'from', 'to'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-{$from}-sd-{$to}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();
        return Excel::download(new LaporanExport($from, $to), "laporan-{$from}-sd-{$to}.xlsx");
    }

    public function profit(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $transactions = Transaction::with(['items.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $profitData = $transactions->map(function ($trx) {
            $modalTotal = $trx->items->sum(function ($item) {
                return ($item->product->harga_beli ?? 0) * $item->qty;
            });
            $profit = $trx->total - $modalTotal;

            return [
                'id'           => $trx->id,
                'no_transaksi' => $trx->no_transaksi,
                'created_at'   => $trx->created_at,
                'total'        => $trx->total,
                'modal'        => $modalTotal,
                'profit'       => $profit,
                'margin'       => $trx->total > 0
                                    ? round(($profit / $trx->total) * 100, 1)
                                    : 0,
            ];
        });

        return Inertia::render('Laporan/Profit', [
            'profitData'  => $profitData,
            'totalOmzet'  => $profitData->sum('total'),
            'totalModal'  => $profitData->sum('modal'),
            'totalProfit' => $profitData->sum('profit'),
            'from'        => $from,
            'to'          => $to,
        ]);
    }

    public function profitExportExcel(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();
        return Excel::download(new LaporanProfitExport($from, $to), "profit-{$from}-sd-{$to}.xlsx");
    }

    public function profitExportPdf(Request $request)
    {
        $from = $request->from ?? today()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $transactions = Transaction::with(['items.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $profitData = $transactions->map(function ($trx) {
            $modal  = $trx->items->sum(fn($i) => ($i->product->harga_beli ?? 0) * $i->qty);
            $profit = $trx->total - $modal;
            return [
                'no_transaksi' => $trx->no_transaksi,
                'created_at'   => $trx->created_at,
                'total'        => $trx->total,
                'modal'        => $modal,
                'profit'       => $profit,
                'margin'       => $trx->total > 0 ? round(($profit / $trx->total) * 100, 1) : 0,
            ];
        });

        $pdf = Pdf::loadView('laporan.profit-pdf', [
            'profitData'  => $profitData,
            'totalOmzet'  => $profitData->sum('total'),
            'totalModal'  => $profitData->sum('modal'),
            'totalProfit' => $profitData->sum('profit'),
            'from'        => $from,
            'to'          => $to,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("profit-{$from}-sd-{$to}.pdf");
    }
}