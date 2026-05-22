<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected array $filters;
    protected int $tokoId;

    public function __construct(array $filters, int $tokoId)
    {
        $this->filters = $filters;
        $this->tokoId  = $tokoId;
    }

    public function collection()
    {
        $query = Transaction::where('toko_id', $this->tokoId)
            ->with(['member', 'user', 'items.product'])
            ->where('payment_status', 'paid');

        if (!empty($this->filters['dari'])) {
            $query->whereDate('created_at', '>=', $this->filters['dari']);
        }
        if (!empty($this->filters['sampai'])) {
            $query->whereDate('created_at', '<=', $this->filters['sampai']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal',
            'Kasir',
            'Member',
            'Subtotal',
            'Diskon',
            'Total',
            'Bayar',
            'Kembali',
            'Metode Bayar',
            'Status',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->no_transaksi,
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->user?->name ?? '-',
            $transaction->member?->nama ?? '-',
            $transaction->subtotal,
            $transaction->diskon,
            $transaction->total,
            $transaction->bayar,
            $transaction->kembali,
            strtoupper($transaction->metode_bayar),
            $transaction->payment_status,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}