<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; color: #555; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #1a1a2e; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .total-row { font-weight: bold; background: #eef2ff; }
        .footer { margin-top: 16px; text-align: right; font-size: 11px; color: #888; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <p class="sub">Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Transaksi</th>
                <th>Kasir</th>
                <th>Member</th>
                <th>Items</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Metode</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $trx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $trx->no_transaksi }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->member->nama ?? '-' }}</td>
                <td>{{ $trx->items->count() }} item</td>
                <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>
                <td>{{ strtoupper($trx->metode_bayar) }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align:right">TOTAL</td>
                <td colspan="4">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>