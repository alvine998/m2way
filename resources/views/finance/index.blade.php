@extends('layouts.app', ['pageTitle' => __('app.finance_overview')])

@section('content')
<div class="space-y-6">
    <!-- Header with Year/Month Filter and Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('finance.index') }}" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">{{ __('app.select_year') }}:</label>
            <select name="year" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        <div class="flex items-center gap-3">
            <div x-data="{ showExport: false }" class="relative">
                <button @click="showExport = !showExport" class="inline-flex items-center px-4 py-2 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    {{ __('app.download_report') }}
                </button>
                <div x-show="showExport" @click.away="showExport = false" x-cloak
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('app.select_period') }}</p>
                    </div>
                    <div class="px-4 py-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.select_month') }} (optional)</label>
                        <select id="exportMonth" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
                            <option value="">Full Year</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="border-t border-gray-100">
                        <a id="pdfLink" href="{{ route('finance.export.pdf', ['year' => $year]) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">{{ __('app.export_pdf') }}</p>
                                <p class="text-xs text-gray-500">{{ __('app.print_ready_report') }}</p>
                            </div>
                        </a>
                        <a id="excelLink" href="{{ route('finance.export.excel', ['year' => $year]) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">{{ __('app.export_excel') }}</p>
                                <p class="text-xs text-gray-500">{{ __('app.spreadsheet_csv') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('exportMonth')?.addEventListener('change', function() {
            const month = this.value;
            const year = '{{ $year }}';
            const params = new URLSearchParams({ year: year });
            if (month) params.append('month', month);

            document.getElementById('pdfLink').href = '{{ route("finance.export.pdf") }}?' + params.toString();
            document.getElementById('excelLink').href = '{{ route("finance.export.excel") }}?' + params.toString();
        });
    </script>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.total_income') }}</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.total_expense') }}</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">Rp {{ number_format($totalExpenses ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.net_profit') }}</p>
                    @php $profit = ($totalIncome ?? 0) - ($totalExpenses ?? 0); @endphp
                    <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">Rp {{ number_format($profit, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ __('app.monthly_income') }} vs {{ __('app.monthly_expense') }}</h2>
        <div class="flex items-end justify-between h-64 gap-2">
            @php
                $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $maxVal = max(array_merge($monthlyIncome ?? array_fill(0, 12, 0), $monthlyExpenses ?? array_fill(0, 12, 0), [1]));
            @endphp
            @foreach($months as $i => $month)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex gap-0.5 items-end" style="height: 200px;">
                    <div class="flex-1 bg-green-400 rounded-t-sm transition-all duration-500" style="height: {{ $maxVal > 0 ? (($monthlyIncome[$i] ?? 0) / $maxVal) * 100 : 0 }}%"></div>
                    <div class="flex-1 bg-red-400 rounded-t-sm transition-all duration-500" style="height: {{ $maxVal > 0 ? (($monthlyExpenses[$i] ?? 0) / $maxVal) * 100 : 0 }}%"></div>
                </div>
                <span class="text-xs font-medium text-gray-500">{{ $month }}</span>
            </div>
            @endforeach
        </div>
        <div class="flex items-center justify-center space-x-6 mt-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-400 rounded-sm"></div>
                <span class="text-sm text-gray-600">{{ __('app.income') }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-400 rounded-sm"></div>
                <span class="text-sm text-gray-600">{{ __('app.expense') }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Accounting Entries -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('app.recent_accounting_entries') }}</h2>
            <a href="{{ route('accounting.index') }}" class="text-sm text-brand hover:text-brand/80 font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.entry_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.category') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.description') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">{{ __('app.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentEntries ?? [] as $entry)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entry->entry_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($entry->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $entry->category)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $entry->description }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-right {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $entry->type === 'income' ? '+' : '-' }} Rp {{ number_format($entry->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">{{ __('app.no_accounting_entries') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
