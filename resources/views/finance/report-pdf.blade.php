<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 48px 48px 82px; size: A4 portrait; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10.5px; color: #1f2937; line-height: 1.45; background: #fff; padding-top: 20px; }
        .letterhead { text-align: center; margin: -22px -48px 24px; }
        .letterhead img { width: 100%; height: auto; display: block; }
        .footer { position: fixed; left: 0; right: 0; bottom: -52px; color: #6aa0e8; font-family: 'Calibri', Arial, sans-serif; font-size: 8px; line-height: 1.35; }
        .container { position: relative; padding: 20px; }
        .report-heading { margin-bottom: 18px; padding-bottom: 12px; border-bottom: 2px solid #1d4ed8; }
        .report-heading-table { width: 100%; border-collapse: collapse; }
        .report-eyebrow { font-size: 10px; letter-spacing: 1.4px; text-transform: uppercase; color: #64748b; font-weight: 700; }
        .report-title { font-size: 22px; font-weight: 700; color: #1d4ed8; margin-top: 4px; }
        .report-period { font-size: 11px; color: #475569; margin-top: 4px; }
        .generated-box { text-align: right; font-size: 10px; color: #64748b; line-height: 1.6; }
        .generated-box strong { color: #0f172a; }
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 18px 0 20px; }
        .summary-spacer { width: 12px; }
        .summary-card { padding: 13px 12px; border-radius: 7px; text-align: center; border: 1px solid #dbeafe; }
        .summary-card.income { background: #f0fdf4; border-color: #bbf7d0; }
        .summary-card.expense { background: #fef2f2; border-color: #fecaca; }
        .summary-card.profit { background: #eff6ff; border-color: #bfdbfe; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: .7px; }
        .summary-value { font-size: 15px; font-weight: 700; margin-top: 4px; white-space: nowrap; }
        .summary-value.green { color: #047857; }
        .summary-value.red { color: #dc2626; }
        .summary-value.blue { color: #1d4ed8; }
        .section-title { margin: 8px 0 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #334155; font-weight: 700; }
        .entries-table { width: 100%; border-collapse: collapse; }
        .entries-table thead { display: table-header-group; }
        .entries-table tr { page-break-inside: avoid; }
        .entries-table th { background: #1d4ed8; color: white; padding: 8px 9px; text-align: left; font-size: 9px; text-transform: uppercase; font-weight: 700; letter-spacing: .4px; }
        .entries-table th:first-child { border-top-left-radius: 5px; }
        .entries-table th:last-child { border-top-right-radius: 5px; }
        .entries-table td { padding: 8px 9px; border-bottom: 1px solid #e5e7eb; font-size: 10px; vertical-align: top; }
        .entries-table tbody tr:nth-child(even) { background: #f8fafc; }
        .entry-number { color: #1d4ed8; font-weight: 700; white-space: nowrap; }
        .badge { display: inline-block; padding: 3px 7px; border-radius: 999px; font-size: 8.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
        .badge.income { background: #dcfce7; color: #047857; }
        .badge.expense { background: #fee2e2; color: #b91c1c; }
        .category { color: #475569; }
        .description { color: #1f2937; }
        .amount { text-align: right; font-weight: 600; }
        .income-text { color: #047857; }
        .expense-text { color: #dc2626; }
        .no-data { margin-top: 18px; text-align: center; padding: 28px; color: #64748b; font-style: italic; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; }
    </style>
</head>
<body>
    @php
        $kopHeaderPath = public_path('invoice-assets/kop-header.png');
        $kopStorageHeaderPath = public_path('storage/invoice-assets/kop-header.png');
        if (! file_exists($kopHeaderPath) && file_exists($kopStorageHeaderPath)) {
            $kopHeaderPath = $kopStorageHeaderPath;
        }
        $kopHeaderBase64 = file_exists($kopHeaderPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($kopHeaderPath)) : '';
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

    <div class="container">
        <div class="report-heading">
            <table class="report-heading-table">
                <tr>
                    <td>
                        <div class="report-eyebrow">M2Way Travel</div>
                        <div class="report-title">{{ $title }}</div>
                        <div class="report-period">
                            Periode: {{ $monthName }} {{ $year }}
                        </div>
                    </td>
                    <td class="generated-box">
                        <strong>{{ __('app.finance_report') }}</strong><br>
                        Dibuat: {{ now()->format('d M Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="summary-table">
            <tr>
                <td class="summary-card income">
                    <div class="summary-label">{{ __('app.total_income') }}</div>
                    <div class="summary-value green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </td>
                <td class="summary-spacer"></td>
                <td class="summary-card expense">
                    <div class="summary-label">{{ __('app.total_expense') }}</div>
                    <div class="summary-value red">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                </td>
                <td class="summary-spacer"></td>
                <td class="summary-card profit">
                    <div class="summary-label">{{ __('app.net_profit') }}</div>
                    <div class="summary-value {{ $profit >= 0 ? 'green' : 'red' }}">Rp {{ number_format($profit, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        @if($entries->count() > 0)
        <div class="section-title">Detail Transaksi Keuangan</div>
        <table class="entries-table">
            <thead>
                <tr>
                    <th style="width: 16%">{{ __('app.entry_number') }}</th>
                    <th style="width: 12%">{{ __('app.date') }}</th>
                    <th style="width: 12%">{{ __('app.type') }}</th>
                    <th style="width: 17%">{{ __('app.category') }}</th>
                    <th style="width: 25%">{{ __('app.description') }}</th>
                    <th class="amount" style="width: 18%">{{ __('app.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr>
                    <td class="entry-number">{{ $entry->entry_number }}</td>
                    <td>{{ $entry->date->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $entry->type }}">
                            {{ $entry->type === 'income' ? __('app.income') : __('app.expense') }}
                        </span>
                    </td>
                    <td class="category">{{ ucfirst(str_replace('_', ' ', $entry->category)) }}</td>
                    <td class="description">{{ $entry->description }}</td>
                    <td class="amount {{ $entry->type === 'income' ? 'income-text' : 'expense-text' }}">
                        {{ $entry->type === 'income' ? '+' : '-' }} Rp {{ number_format($entry->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">{{ __('app.no_accounting_entries_found') }}</div>
        @endif
    </div>
</body>
</html>
