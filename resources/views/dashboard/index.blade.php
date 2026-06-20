@extends('layouts.app', ['pageTitle' => __('app.dashboard')])

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.total_customers') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalCustomers ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.total_revenue') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.total_transactions') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalTransactions ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.pending_payments') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($pendingPayments ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.recent_transactions') }}</h2>
                <a href="{{ route('transactions.index') }}" class="text-sm text-brand hover:text-brand/80 font-medium">{{ __('app.view_all') }}</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.customer') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('app.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $transaction->transaction_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->customer->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'confirmed' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">{{ __('app.no_transactions') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Package Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.package_distribution') }}</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">{{ __('app.hajj_packages') }}</span>
                            <span class="text-gray-500">{{ $hajjCount ?? 0 }} {{ __('app.packages') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-4">
                            <div class="bg-brand rounded-full h-4 transition-all duration-500" style="width: {{ ($totalPackages ?? 0) > 0 ? (($hajjCount ?? 0) / ($totalPackages ?? 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">{{ __('app.umroh_packages') }}</span>
                            <span class="text-gray-500">{{ $umrohCount ?? 0 }} {{ __('app.packages') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-4">
                            <div class="bg-blue-500 rounded-full h-4 transition-all duration-500" style="width: {{ ($totalPackages ?? 0) > 0 ? (($umrohCount ?? 0) / ($totalPackages ?? 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('app.monthly_revenue_vs_expenses') }}</h3>
                    <div class="flex items-end justify-between h-40 gap-1">
                        @php
                            $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            $maxVal = max(array_merge($monthlyRevenue ?? array_fill(0, 12, 0), $monthlyExpenses ?? array_fill(0, 12, 0), [1]));
                        @endphp
                        @foreach($months as $i => $month)
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full flex gap-0.5 items-end" style="height: 120px;">
                                <div class="flex-1 bg-brand rounded-t-sm transition-all duration-500" style="height: {{ $maxVal > 0 ? (($monthlyRevenue[$i] ?? 0) / $maxVal) * 100 : 0 }}%"></div>
                                <div class="flex-1 bg-gray-300 rounded-t-sm transition-all duration-500" style="height: {{ $maxVal > 0 ? (($monthlyExpenses[$i] ?? 0) / $maxVal) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-500">{{ $month }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-center space-x-4 mt-3">
                        <div class="flex items-center space-x-1.5">
                            <div class="w-3 h-3 bg-brand rounded-sm"></div>
                            <span class="text-xs text-gray-500">{{ __('app.revenue') }}</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <div class="w-3 h-3 bg-gray-300 rounded-sm"></div>
                            <span class="text-xs text-gray-500">{{ __('app.expenses') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
