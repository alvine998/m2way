<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions Report - M2Way</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; }
        .subtitle { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1b1b18; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>M2Way - Transactions Report</h1>
    <p class="subtitle">Generated on {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Number</th>
                <th>Customer</th>
                <th>Package</th>
                <th>Type</th>
                <th class="text-right">Total</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Grand Total</th>
                <th class="text-center">Status</th>
                <th class="text-center">Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->transaction_number }}</td>
                    <td>{{ $t->customer->name ?? '-' }}</td>
                    <td>{{ $t->package->name ?? '-' }}</td>
                    <td>{{ ucfirst($t->package->type ?? '-') }}</td>
                    <td class="text-right">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($t->discount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($t->status) }}</td>
                    <td class="text-center">{{ ucfirst($t->payment_status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">M2Way Travel Agent - Hajj & Umroh</p>
</body>
</html>
