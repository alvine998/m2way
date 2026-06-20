@extends('layouts.app', ['pageTitle' => 'Transactions'])

@section('content')
<div class="space-y-6" x-data="transactionPage()">
    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="border-b border-gray-100">
            <nav class="flex -mb-px">
                <a href="{{ route('transactions.index', ['tab' => 'belum_lunas'] + request()->except(['tab'])) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $tab === 'belum_lunas' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $tab === 'belum_lunas' ? 'bg-brand' : 'bg-gray-400' }}"></span>
                        {{ __('app.tab_belum_lunas') }}
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'belum_lunas' ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-500' }}">{{ $counts['belum_lunas'] }}</span>
                    </span>
                </a>
                <a href="{{ route('transactions.index', ['tab' => 'lunas'] + request()->except(['tab'])) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $tab === 'lunas' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $tab === 'lunas' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ __('app.tab_lunas') }}
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'lunas' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">{{ $counts['lunas'] }}</span>
                    </span>
                </a>
                <a href="{{ route('transactions.index', ['tab' => 'refund'] + request()->except(['tab'])) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $tab === 'refund' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $tab === 'refund' ? 'bg-red-500' : 'bg-gray-400' }}"></span>
                        {{ __('app.tab_refund') }}
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'refund' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500' }}">{{ $counts['refund'] }}</span>
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-3">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <select name="payment_type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all_payment_types') }}</option>
                <option value="cash" {{ request('payment_type') === 'cash' ? 'selected' : '' }}>{{ __('app.cash') }}</option>
                <option value="cicilan" {{ request('payment_type') === 'cicilan' ? 'selected' : '' }}>{{ __('app.cicilan_installment') }}</option>
            </select>
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_transactions_placeholder') }}"
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all">
            </div>
        </form>
        <div class="flex items-center space-x-3">
            <button type="button" id="bulkInvoiceBtn" disabled onclick="submitBulkInvoice()" class="inline-flex items-center px-4 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('app.bulk_invoice') }}
            </button>
            <a href="{{ route('transactions.export.excel', ['tab' => $tab] + request()->except(['tab'])) }}" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('app.export_excel') }}
            </a>
            <a href="{{ route('transactions.export.pdf', ['tab' => $tab] + request()->except(['tab'])) }}" class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('app.export_pdf') }}
            </a>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('app.new_transaction') }}
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="rounded border-gray-300 text-brand focus:ring-brand/30">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.transaction_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.package') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.payment_type') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.grand_total') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.payment') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions ?? [] as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors {{ $transaction->status === 'refund' ? 'bg-red-50/50' : '' }}">
                        <td class="px-4 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $transaction->id }}" onchange="updateBulkInvoiceBtn()" class="transaction-checkbox rounded border-gray-300 text-brand focus:ring-brand/30">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $transaction->transaction_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->customer->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->package->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->payment_type === 'cicilan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $transaction->payment_type_label }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $transaction->payment_method_label }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'refund' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $transaction->status === 'refund' ? __('app.refund') : ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $paymentColors = [
                                    'paid' => 'bg-green-100 text-green-700',
                                    'partial' => 'bg-yellow-100 text-yellow-700',
                                    'unpaid' => 'bg-red-100 text-red-700',
                                    'refunded' => 'bg-gray-100 text-gray-500',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$transaction->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('transactions.invoice', $transaction) }}" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="{{ __('app.download_invoice') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                @if($transaction->status !== 'refund')
                                <button type="button" @click="openPaymentModal({{ $transaction->id }}, @js($transaction->transaction_number), {{ $transaction->grand_total }}, {{ $transaction->paidAmount() }}, @js($transaction->payments->map(fn($p) => ['number' => $p->payment_number, 'date' => $p->payment_date->format('d M Y'), 'method' => $p->payment_method, 'amount' => (float) $p->amount, 'notes' => $p->notes])->values()->toArray()))" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="{{ __('app.record_payment') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </button>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($transaction->payments_count > 0)
                                <button type="button" @click="openRefundModal({{ $transaction->id }}, @js($transaction->transaction_number))" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="{{ __('app.refund') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </button>
                                @endif
                                @endif
                                @if($transaction->payment_status !== 'paid' && $transaction->status !== 'refund')
                                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" x-data @submit.prevent="if(confirm('{{ __("app.confirm_delete_transaction") }}')) this.$el.submit();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('app.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_transactions_found') }}</p>
                            <a href="{{ route('transactions.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">
                                {{ __('app.create_first_transaction') }} &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($transactions) && $transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
<!-- Refund Modal -->
<div x-show="showRefund" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" @click="showRefund = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('app.refund_transaction') }}</h3>
                <button @click="showRefund = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="'{{ url('transactions') }}/' + refundTransactionId + '/refund'" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700">{{ __('app.refund_warning') }} <strong x-text="refundTransactionNumber"></strong>.</p>
                        <p class="text-xs text-red-500 mt-1">{{ __('app.refund_warning_description') }}</p>
                    </div>

                    <div>
                        <label for="refund_reason" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.refund_reason') }} <span class="text-red-500">*</span></label>
                        <textarea name="refund_reason" id="refund_reason" rows="3" required
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none"
                                  placeholder="{{ __('app.refund_reason_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" @click="showRefund = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ __('app.cancel') }}</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">{{ __('app.process_refund') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div x-show="showPayment" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" @click="showPayment = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('app.record_payment') }}</h3>
                <button @click="showPayment = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="'{{ url('transactions') }}/' + paymentTransactionId + '/payments'" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-700">{{ __('app.transaction') }}: <strong x-text="paymentTransactionNumber"></strong></p>
                        <div class="mt-2 flex items-center justify-between text-sm">
                            <span class="text-blue-600">{{ __('app.grand_total') }}: <strong>Rp <span x-text="formatNumber(paymentGrandTotal)"></span></strong></span>
                            <span class="text-blue-600">{{ __('app.paid') }}: <strong>Rp <span x-text="formatNumber(paymentPaidAmount)"></span></strong></span>
                            <span class="text-blue-800 font-semibold">{{ __('app.remaining') }}: <strong>Rp <span x-text="formatNumber(paymentRemainingAmount)"></span></strong></span>
                        </div>
                    </div>

                    <div x-show="paymentHistory.length > 0" class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('app.payment_history') }}</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            <template x-for="(p, i) in paymentHistory" :key="i">
                                <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-700" x-text="p.number"></span>
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            <span x-text="p.date"></span>
                                            <span class="mx-1">•</span>
                                            <span x-text="p.method"></span>
                                            <template x-if="p.notes">
                                                <span class="ml-1">— <span x-text="p.notes"></span></span>
                                            </template>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-green-600">Rp <span x-text="new Intl.NumberFormat('id-ID').format(p.amount)"></span></span>
                                </div>
                            </template>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">{{ __('app.total_paid') }}</span>
                            <span class="font-bold text-green-600">Rp <span x-text="formatNumber(paymentPaidAmount)"></span></span>
                        </div>
                    </div>

                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_amount') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" id="payment_amount" x-model="paymentAmount" min="1" :max="paymentRemainingAmount" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all"
                               placeholder="{{ __('app.enter_payment_amount') }}">
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all">
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_method') }} <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white">
                            <option value="">{{ __('app.select_payment_method') }}</option>
                            <option value="cash">{{ __('app.cash') }}</option>
                            <option value="transfer">{{ __('app.bank_transfer') }}</option>
                            <option value="credit_card">{{ __('app.credit_card') }}</option>
                            <option value="debit_card">{{ __('app.debit_card') }}</option>
                            <option value="ewallet">{{ __('app.ewallet') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                        <textarea name="notes" id="payment_notes" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none"
                                  placeholder="{{ __('app.payment_notes_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" @click="showPayment = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ __('app.cancel') }}</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors">{{ __('app.record_payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function transactionPage() {
    return {
        // Refund modal
        showRefund: false,
        refundTransactionId: null,
        refundTransactionNumber: '',
        openRefundModal(id, number) {
            this.refundTransactionId = id;
            this.refundTransactionNumber = number;
            this.showRefund = true;
        },

        // Payment modal
        showPayment: false,
        paymentTransactionId: null,
        paymentTransactionNumber: '',
        paymentGrandTotal: 0,
        paymentPaidAmount: 0,
        paymentAmount: 0,
        paymentHistory: [],
        get paymentRemainingAmount() {
            return Math.max(0, this.paymentGrandTotal - this.paymentPaidAmount);
        },
        openPaymentModal(id, number, grandTotal, paid, payments = []) {
            this.paymentTransactionId = id;
            this.paymentTransactionNumber = number;
            this.paymentGrandTotal = grandTotal;
            this.paymentPaidAmount = paid;
            this.paymentAmount = this.paymentRemainingAmount;
            this.paymentHistory = payments;
            this.showPayment = true;
        },
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}

function toggleSelectAll(source) {
    document.querySelectorAll('.transaction-checkbox').forEach(cb => cb.checked = source.checked);
    updateBulkInvoiceBtn();
}

function updateBulkInvoiceBtn() {
    const checked = document.querySelectorAll('.transaction-checkbox:checked').length;
    document.getElementById('bulkInvoiceBtn').disabled = checked === 0;
}

function submitBulkInvoice() {
    const checked = document.querySelectorAll('.transaction-checkbox:checked');
    if (checked.length === 0) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("transactions.invoices.bulk") }}';
    form.appendChild(csrfTokenInput());

    checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

function csrfTokenInput() {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_token';
    input.value = '{{ csrf_token() }}';
    return input;
}
</script>
</div>
@endsection
