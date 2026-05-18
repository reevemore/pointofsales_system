<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Profit</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; }
        h2 { text-align: center; font-size: 16px; margin-bottom: 4px; }
        .sub { text-align: center; color: #64748b; font-size: 11px; margin-bottom: 16px; }
        .summary { display: flex; gap: 12px; margin-bottom: 16px; }
        .card { flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; }
        .card-label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .card-value { font-size: 14px; font-weight: bold; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #10b981; color: white; padding: 7px 10px; text-align: left; font-size: 10px; }
        td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .profit { color: #065f46; font-weight: bold; }
        .loss { color: #dc2626; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <h2>💰 Laporan Profit</h2>
    <div class="sub">Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</div>

    <table style="margin-bottom:16px; width:auto;">
        <tr>
            <td style="padding:6px 16px 6px 0;"><strong>Total Omzet</strong></td>
            <td>: Rp {{ number_format($totalOmzet,0,',','.') }}</td>
            <td style="padding-left:24px;"><strong>Total Modal</strong></td>
            <td>: Rp {{ number_format($totalModal,0,',','.') }}</td>
            <td style="padding-left:24px;"><strong>Total Profit</strong></td>
            <td>: <strong class="{{ $totalProfit >= 0 ? 'profit' : 'loss' }}">Rp {{ number_format($totalProfit,0,',','.') }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th class="text-right">Omzet</th>
                <th class="text-right">Modal (HPP)</th>
                <th class="text-right">Profit</th>
                <th class="text-center">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($profitData as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row['no_transaksi'] }}</td>
                <td>{{ \Carbon\Carbon::parse($row['created_at'])->format('d/m/Y H:i') }}</td>
                <td class="text-right">{{ number_format($row['total'],0,',','.') }}</td>
                <td class="text-right">{{ number_format($row['modal'],0,',','.') }}</td>
                <td class="text-right {{ $row['profit'] >= 0 ? 'profit' : 'loss' }}">
                    {{ number_format($row['profit'],0,',','.') }}
                </td>
                <td class="text-center">{{ $row['margin'] }}%</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak pada {{ now()->format('d M Y H:i') }} — POSOWII</div>
</body>
</html>