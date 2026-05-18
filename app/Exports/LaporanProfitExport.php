<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanProfitExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(private string $from, private string $to) {}

    public function collection()
    {
        return Transaction::with(['items.product'])
            ->whereDate('created_at', '>=', $this->from)
            ->whereDate('created_at', '<=', $this->to)
            ->where('payment_status', 'paid')
            ->latest()
            ->get()
            ->map(function ($trx) {
                $modal  = $trx->items->sum(fn($i) => ($i->product->harga_beli ?? 0) * $i->qty);
                $profit = $trx->total - $modal;
                $margin = $trx->total > 0 ? round(($profit / $trx->total) * 100, 1) : 0;

                return [
                    $trx->no_transaksi,
                    $trx->created_at->format('d/m/Y H:i'),
                    $trx->total,
                    $modal,
                    $profit,
                    $margin . '%',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal',
            'Omzet (Rp)',
            'Modal/HPP (Rp)',
            'Profit (Rp)',
            'Margin',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '10B981']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 18,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 12,
        ];
    }
}