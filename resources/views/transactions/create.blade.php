@extends('layouts.app', ['pageTitle' => __('app.new_transaction')])

@section('content')
<div class="max-w-4xl" x-data="transactionForm()" x-init="init()">
    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.transaction_details') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-search-select name="customer_id" :value="old('customer_id')" label="{{ __('app.customer') }}" placeholder="{{ __('app.select_customer') }}" required
                            :options="$customers->map(fn($c) => ['value' => $c->id, 'label' => $c->name . ' - ' . $c->phone])" />
                        @error('customer_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.package') }} <span class="text-red-500">*</span></label>
                        <select name="package_id" id="package_id" x-model="packageId" @change="updatePackageInfo()" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('package_id') border-red-300 @enderror">
                            <option value="">{{ __('app.select_package') }}</option>
                            @foreach($packages ?? [] as $pkg)
                            <option value="{{ $pkg->id }}" data-price="{{ $pkg->base_price }}" data-name="{{ $pkg->name }}" data-type="{{ $pkg->type }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} ({{ strtoupper($pkg->type) }}) - Rp {{ number_format($pkg->base_price, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                        @error('package_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="packageName" x-transition class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">{{ __('app.package') }}</span>
                                <p class="font-semibold text-gray-900" x-text="packageName">-</p>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('app.type') }}</span>
                                <p class="font-semibold text-gray-900" x-text="packageType ? packageType.toUpperCase() : '-'">-</p>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('app.base_price') }}</span>
                                <p class="font-semibold text-brand">Rp <span x-text="formatNumber(packagePrice)"></span></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.departure_date') }}</label>
                        <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('departure_date') border-red-300 @enderror">
                        @error('departure_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }} <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('status') border-red-300 @enderror">
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                            <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>{{ __('app.confirmed') }}</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.payment_info') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_type') }} <span class="text-red-500">*</span></label>
                        <select name="payment_type" id="payment_type" x-model="paymentType" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('payment_type') border-red-300 @enderror">
                            <option value="cash">{{ __('app.cash') }}</option>
                            <option value="cicilan" {{ old('payment_type') === 'cicilan' ? 'selected' : '' }}>{{ __('app.cicilan_installment') }}</option>
                        </select>
                        @error('payment_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_method') }} <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('payment_method') border-red-300 @enderror">
                            <option value="">{{ __('app.select_method') }}</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('app.tunai_cash') }}</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>{{ __('app.bank_transfer') }}</option>
                            <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>{{ __('app.credit_card') }}</option>
                            <option value="debit_card" {{ old('payment_method') === 'debit_card' ? 'selected' : '' }}>{{ __('app.debit_card') }}</option>
                            <option value="ewallet" {{ old('payment_method') === 'ewallet' ? 'selected' : '' }}>{{ __('app.e_wallet') }}</option>
                        </select>
                        @error('payment_method') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="paymentType === 'cicilan'" x-transition class="md:col-span-2 p-4 bg-amber-50 border border-amber-200 rounded-lg space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">{{ __('app.installment_payment') }}</p>
                                <p class="text-xs text-amber-600 mt-1">{{ __('app.first_payment_info') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_payment_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.first_payment_amount') }} <span class="text-red-500">*</span></label>
                                <input type="text" inputmode="numeric" name="first_payment_amount" id="first_payment_amount" value="{{ old('first_payment_amount') }}" min="1" placeholder="Jumlah pembayaran pertama"
                                       class="currency-input w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('first_payment_amount') border-red-300 @enderror"
                                       :required="paymentType === 'cicilan'">
                                @error('first_payment_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="first_payment_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.first_payment_date') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="first_payment_date" id="first_payment_date" value="{{ old('first_payment_date', date('Y-m-d')) }}"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('first_payment_date') border-red-300 @enderror"
                                       :required="paymentType === 'cicilan'">
                                @error('first_payment_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="first_payment_method" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.payment_method') }} <span class="text-red-500">*</span></label>
                                <select name="first_payment_method" id="first_payment_method"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('first_payment_method') border-red-300 @enderror"
                                        :required="paymentType === 'cicilan'">
                                    <option value="">{{ __('app.select_method') }}</option>
                                    <option value="cash" {{ old('first_payment_method') === 'cash' ? 'selected' : '' }}>{{ __('app.tunai_cash') }}</option>
                                    <option value="transfer" {{ old('first_payment_method') === 'transfer' ? 'selected' : '' }}>{{ __('app.bank_transfer') }}</option>
                                    <option value="credit_card" {{ old('first_payment_method') === 'credit_card' ? 'selected' : '' }}>{{ __('app.credit_card') }}</option>
                                    <option value="debit_card" {{ old('first_payment_method') === 'debit_card' ? 'selected' : '' }}>{{ __('app.debit_card') }}</option>
                                    <option value="ewallet" {{ old('first_payment_method') === 'ewallet' ? 'selected' : '' }}>{{ __('app.e_wallet') }}</option>
                                </select>
                                @error('first_payment_method') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.evidence') }} <span class="text-red-500">*</span></label>
                                <div class="border-2 border-dashed border-amber-300 rounded-lg p-3 text-center hover:border-brand transition-colors bg-white"
                                     x-data="{ dragging: false, evidenceFile: null }"
                                     x-on:dragover.prevent="dragging = true"
                                     x-on:dragleave.prevent="dragging = false"
                                     x-on:drop.prevent="dragging = false; evidenceFile = $event.dataTransfer.files[0]; $refs.firstPaymentEvidence.files = $event.dataTransfer.files;"
                                     :class="dragging ? 'border-brand bg-brand/5' : ''">
                                    <div x-show="!evidenceFile">
                                        <p class="text-xs text-gray-600"><label class="relative text-brand font-medium cursor-pointer hover:underline">{{ __('app.browse') }}<input type="file" name="first_payment_evidence" x-ref="firstPaymentEvidence" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.pdf" :required="paymentType === 'cicilan'" @change="evidenceFile = $el.files[0]"></label></p>
                                    </div>
                                    <div x-show="evidenceFile" class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-xs text-gray-700 truncate" x-text="evidenceFile?.name"></span>
                                        <button type="button" @click="evidenceFile = null; $refs.firstPaymentEvidence.value = ''" class="text-gray-400 hover:text-red-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </div>
                                </div>
                                @error('first_payment_evidence') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.pricing') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.total_price') }}</label>
                        <input type="text" readonly :value="formatNumber(packagePrice)"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700">
                        <input type="hidden" name="total_price" :value="packagePrice">
                    </div>

                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.discount') }}</label>
                        <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}" min="0" x-model.number="discount" @input="calculateTotal()" placeholder="0"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('discount') border-red-300 @enderror">
                        @error('discount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">{{ __('app.grand_total') }}</span>
                        <div>
                            <span class="text-2xl font-bold text-brand">Rp <span x-text="formatNumber(grandTotal)"></span></span>
                            <input type="hidden" name="grand_total" :value="grandTotal">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Catatan"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.create_transaction') }}</button>
            </div>
        </div>
    </form>
</div>

<script>
function transactionForm() {
    return {
        customerId: '{{ old("customer_id") }}',
        packageId: '{{ old("package_id") }}',
        packagePrice: 0,
        packageName: '',
        packageType: '',
        discount: {{ old('discount', 0) }},
        grandTotal: 0,
        paymentType: '{{ old("payment_type", "cash") }}',
        init() {
            this.updatePackageInfo();
        },
        updatePackageInfo() {
            const select = document.getElementById('package_id');
            const option = select.options[select.selectedIndex];
            if (this.packageId && option.dataset.price) {
                this.packagePrice = parseFloat(option.dataset.price) || 0;
                this.packageName = option.dataset.name || '';
                this.packageType = option.dataset.type || '';
            } else {
                this.packagePrice = 0;
                this.packageName = '';
                this.packageType = '';
            }
            this.calculateTotal();
        },
        calculateTotal() {
            this.grandTotal = this.packagePrice - (this.discount || 0);
        },
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    }
}
</script>
@endsection
