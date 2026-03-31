<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
}