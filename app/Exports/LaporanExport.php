<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(private string $from, private string $to) {}

    public function collection()
    {
        return Transaction::with(['user', 'member', 'items'])
            ->whereDate('created_at', '>=', $this->from)
            ->whereDate('created_at', '<=', $this->to)
            ->latest()
            ->get()
            ->map(fn($t) => [
                $t->no_transaksi,
                $t->user?->name ?? '-',
                $t->member?->nama ?? '-',
                $t->items->count(),
                $t->subtotal,
                $t->diskon,
                $t->total,
                $t->bayar,
                $t->kembali,
                strtoupper($t->metode_bayar),
                $t->created_at->format('d/m/Y H:i'),
            ]);
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Kasir',
            'Member',
            'Items',
            'Subtotal',
            'Diskon',
            'Total',
            'Bayar',
            'Kembali',
            'Metode',
            'Waktu',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3B82F6']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 16,
            'C' => 16,
            'D' => 8,
            'E' => 16,
            'F' => 12,
            'G' => 16,
            'H' => 16,
            'I' => 14,
            'J' => 10,
            'K' => 18,
        ];
    }
}