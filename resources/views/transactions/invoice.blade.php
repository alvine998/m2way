<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Faktur {{ $transaction->transaction_number }} - M2Way</title>
    <style>
        @page { margin: 0; size: A4; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; color: #333; line-height: 1.5; background: #fff; }

        .invoice-container { padding: 135px 48px 78px; }

        /* Letterhead */
        .letterhead { position: fixed; top: 30px; left: 0; right: 0; text-align: center; }
        .letterhead img { width: 100%; height: auto; display: block; }

        /* Header */
        .header { margin-bottom: 22px; padding-bottom: 12px; border-bottom: 1px solid #d1d5db; }
        .header-table { width: 100%; border-collapse: collapse; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; font-weight: 700; color: #2563eb; letter-spacing: 2px; }
        .invoice-title .inv-number { font-size: 13px; color: #1b1b18; font-weight: 600; margin-top: 5px; }
        .invoice-meta { font-size: 11px; color: #374151; }
        .invoice-meta strong { color: #1b1b18; }

        /* Info Grid */
        .info-grid { width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 10px; }
        .info-box { width: 50%; vertical-align: top; background: #f9fafb; border-radius: 8px; padding: 13px 15px; border: 1px solid #e5e7eb; }
        .info-box-spacer { width: 16px; }
        .info-box h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 8px; font-weight: 600; }
        .info-box p { font-size: 11px; color: #374151; margin-bottom: 3px; }
        .info-box .label { color: #6b7280; font-size: 10px; }
        .info-box .value { font-weight: 600; color: #111827; }

        /* Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table thead th { background: #1b1b18; color: #fff; padding: 10px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .items-table thead th:last-child { text-align: right; }
        .items-table tbody td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        .items-table tbody td:last-child { text-align: right; font-weight: 600; }
        .items-table tbody tr:nth-child(even) { background: #f9fafb; }

        /* Totals */
        .totals-section { margin-bottom: 20px; text-align: right; }
        .totals-box { width: 300px; margin-left: auto; border-collapse: collapse; }
        .totals-row td { padding: 5px 0; font-size: 11px; }
        .totals-row .label { color: #6b7280; }
        .totals-row .value { font-weight: 500; color: #374151; text-align: right; }
        .totals-row.discount .value { color: #dc2626; }
        .totals-row.grand-total td { border-top: 2px solid #1b1b18; padding-top: 10px; }
        .totals-row.grand-total .label { font-size: 13px; font-weight: 700; color: #1b1b18; }
        .totals-row.grand-total .value { font-size: 15px; font-weight: 700; color: #f53003; }

        /* Payment Status */
        .payment-status { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 15px; margin-bottom: 20px; }
        .payment-status.paid { background: #f0fdf4; border-color: #bbf7d0; }
        .payment-status.partial { background: #fffbeb; border-color: #fde68a; }
        .payment-status.unpaid { background: #fef2f2; border-color: #fecaca; }
        .payment-status .icon { width: 20px; height: 20px; border-radius: 50%; display: inline-block; line-height: 20px; text-align: center; vertical-align: middle; margin-right: 10px; font-size: 11px; color: #fff; font-weight: 700; }
        .payment-status.paid .icon { background: #22c55e; }
        .payment-status.partial .icon { background: #f59e0b; }
        .payment-status.unpaid .icon { background: #ef4444; }
        .payment-status .text { display: inline-block; vertical-align: middle; font-size: 12px; font-weight: 600; }
        .payment-status.paid .text { color: #166534; }
        .payment-status.partial .text { color: #92400e; }
        .payment-status.unpaid .text { color: #991b1b; }

        /* Payment History */
        .payment-history { margin-bottom: 25px; }
        .payment-history h3 { font-size: 12px; font-weight: 600; color: #1b1b18; margin-bottom: 8px; }
        .payment-history table { width: 100%; border-collapse: collapse; }
        .payment-history th { background: #f3f4f6; padding: 6px 10px; text-align: left; font-size: 10px; color: #6b7280; text-transform: uppercase; }
        .payment-history td { padding: 6px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        .payment-history td:last-child { text-align: right; font-weight: 600; }

        /* Terms */
        .terms { margin-top: 25px; padding-top: 15px; border-top: 1px solid #e5e7eb; }
        .terms h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 6px; }
        .terms ul { list-style: none; padding: 0; }
        .terms li { font-size: 9px; color: #6b7280; margin-bottom: 2px; padding-left: 12px; position: relative; }
        .terms li:before { content: "•"; position: absolute; left: 0; color: #f53003; }

        /* Footer */
        .footer { position: fixed; left: 48px; right: 48px; bottom: 32px; color: #6aa0e8; font-family: 'Calibri', Arial, sans-serif; font-size: 8px; line-height: 1.35; }

        /* Signature */
        .signature-section { margin-top: 8px; padding-top: 0; }
        .signature-box { width: 150px; margin-left: 0px; }
        .signature-box img { width: 150px; height: auto; display: block; }
    </style>
</head>
<body>
    @php
        $kopHeaderPath = public_path('storage/invoice-assets/kop-header.png');
        $kopSignaturePath = public_path('storage/invoice-assets/kop-signature.png');
        $kopHeaderBase64 = file_exists($kopHeaderPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($kopHeaderPath)) : '';
        $kopSignatureBase64 = file_exists($kopSignaturePath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($kopSignaturePath)) : '';
    @endphp

    <div class="letterhead">
        @if($kopHeaderBase64)
            <img src="{{ $kopHeaderBase64 }}" alt="M2Way letterhead">
        @endif
    </div>

    <div class="footer">
        Head Office :JL. MAMPANG PRAPATAN RAYA NO. 73 A LT. 3 RT/RW: 010/002, kelurahan Tegal Parang, kecamatan Mampang Prapatan, Kota Adm. Jakarta<br>
        Selatan, DKI Jakarta | E-mail: m2waytour@gmail.com website: www.m2way.com
    </div>

    <div class="invoice-container">

        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="invoice-meta">
                        <strong>Tanggal Faktur:</strong> {{ now()->format('d M Y') }}<br>
                        <strong>No. Transaksi:</strong> {{ $transaction->transaction_number }}
                    </td>
                    <td class="invoice-title">
                        <h2>FAKTUR</h2>
                        <div class="inv-number">{{ $transaction->transaction_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Info Grid -->
        <table class="info-grid">
            <tr>
                <td class="info-box">
                    <h3>Ditagihkan Kepada</h3>
                    <p><span class="label">Nama:</span> <span class="value">{{ $transaction->customer->name ?? '-' }}</span></p>
                    <p><span class="label">Telepon:</span> <span class="value">{{ $transaction->customer->phone ?? '-' }}</span></p>
                    @if($transaction->customer->email)
                        <p><span class="label">Email:</span> <span class="value">{{ $transaction->customer->email }}</span></p>
                    @endif
                    @if($transaction->customer->id_number)
                        <p><span class="label">KTP/Paspor:</span> <span class="value">{{ $transaction->customer->id_number }}</span></p>
                    @endif
                    @if($transaction->customer->address)
                        <p><span class="label">Alamat:</span> <span class="value">{{ $transaction->customer->address }}</span></p>
                    @endif
                </td>
                <td class="info-box-spacer"></td>
                <td class="info-box">
                    @php
                        $packageType = $transaction->package->type ?? null;
                        $packageTypeLabel = $packageType === 'hajj' ? 'Haji' : ($packageType === 'umroh' ? 'Umroh' : ucfirst($packageType ?? '-'));
                    @endphp
                    <h3>Detail Paket</h3>
                    <p><span class="label">Paket:</span> <span class="value">{{ $transaction->package->name ?? '-' }}</span></p>
                    <p><span class="label">Jenis:</span> <span class="value">{{ $packageTypeLabel }}</span></p>
                    <p><span class="label">Durasi:</span> <span class="value">{{ $transaction->package->duration ?? '-' }} Hari</span></p>
                    @if($transaction->package->airline)
                        <p><span class="label">Maskapai:</span> <span class="value">{{ $transaction->package->airline }}</span></p>
                    @endif
                    @if($transaction->package->hotel_name)
                        <p><span class="label">Hotel:</span> <span class="value">{{ $transaction->package->hotel_name }} {{ $transaction->package->hotel_star ? '(' . $transaction->package->hotel_star . ' Bintang)' : '' }}</span></p>
                    @endif
                    <p><span class="label">Keberangkatan:</span> <span class="value">{{ $transaction->departure_date ? $transaction->departure_date->format('d M Y') : '-' }}</span></p>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 55%">Deskripsi</th>
                    <th style="width: 20%">Kategori</th>
                    <th style="width: 20%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><strong>{{ $transaction->package->name ?? 'Paket' }}</strong><br>
                        <span style="font-size:10px; color:#6b7280;">
                            {{ $packageTypeLabel }} - {{ $transaction->package->duration ?? '-' }} Hari
                            @if($transaction->package->departure_city) | Berangkat dari {{ $transaction->package->departure_city }} @endif
                        </span>
                    </td>
                    <td>Paket Perjalanan</td>
                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
                @if($transaction->discount > 0)
                <tr>
                    <td>2</td>
                    <td>Diskon</td>
                    <td>Diskon</td>
                    <td style="color: #dc2626;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-box">
                <tr class="totals-row">
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
                @if($transaction->discount > 0)
                <tr class="totals-row discount">
                    <td class="label">Diskon</td>
                    <td class="value">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="totals-row grand-total">
                    <td class="label">TOTAL</td>
                    <td class="value">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment Status -->
        @php
            $statusClass = $transaction->payment_status;
            $statusText = match($transaction->payment_status) {
                'paid' => 'Lunas',
                'partial' => 'Sebagian',
                'unpaid' => 'Belum Lunas',
                default => ucfirst($transaction->payment_status ?? '-'),
            };
            $statusIcon = $transaction->payment_status === 'paid' ? 'OK' : ($transaction->payment_status === 'partial' ? '!' : 'X');
        @endphp
        <div class="payment-status {{ $statusClass }}">
            <div class="icon">{{ $statusIcon }}</div>
            <div class="text">Status Pembayaran: {{ $statusText }}</div>
        </div>

        <!-- Payment History -->
        @if($transaction->payments && $transaction->payments->count() > 0)
        <div class="payment-history">
            <h3>Riwayat Pembayaran</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Referensi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->payments as $payment)
                    @php
                        $paymentMethodLabel = match($payment->payment_method) {
                            'cash' => 'Tunai',
                            'transfer' => 'Transfer Bank',
                            'credit_card' => 'Kartu Kredit',
                            'debit_card' => 'Kartu Debit',
                            'ewallet' => 'E-Wallet',
                            default => ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-')),
                        };
                    @endphp
                    <tr>
                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                        <td>{{ $paymentMethodLabel }}</td>
                        <td>{{ $payment->payment_number }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Terms & Conditions -->
        <div class="terms">
            <h3>Syarat & Ketentuan</h3>
            <ul>
                <li>Faktur ini adalah bukti pembayaran yang sah dari Agen Perjalanan M2Way.</li>
                <li>Pembayaran harus dilakukan sesuai dengan jadwal yang telah ditentukan.</li>
                <li>Pembatalan harus dilakukan minimal 30 hari sebelum keberangkatan.</li>
                <li>Demikianlah faktur ini dibuat dengan benar dan dapat dipergunakan sebagaimana mestinya.</li>
            </ul>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                @if($kopSignatureBase64)
                    <img src="{{ $kopSignatureBase64 }}" alt="M2Way authorized signature">
                @endif
            </div>
        </div>

    </div>
</body>
</html>
