@extends('layouts.app', ['pageTitle' => __('app.add_accounting_entry')])

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('app.new_accounting_entry') }}</h2>
        </div>
        <form method="POST" action="{{ route('accounting.store') }}" enctype="multipart/form-data" class="p-6" x-data="{ type: '{{ old('type', 'income') }}' }">
            @csrf
            <div class="space-y-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.type') }} <span class="text-red-500">*</span></label>
                    <div class="flex space-x-4">
                        <label class="flex items-center px-4 py-3 border border-gray-200 rounded-lg cursor-pointer hover:border-brand transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/5">
                            <input type="radio" name="type" value="income" {{ old('type') === 'income' ? 'checked' : '' }} class="text-brand focus:ring-brand" required @change="type = 'income'">
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ __('app.income') }}</span>
                        </label>
                        <label class="flex items-center px-4 py-3 border border-gray-200 rounded-lg cursor-pointer hover:border-brand transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/5">
                            <input type="radio" name="type" value="expense" {{ old('type') === 'expense' ? 'checked' : '' }} class="text-brand focus:ring-brand" required @change="type = 'expense'">
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ __('app.expense') }}</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.category') }} <span class="text-red-500">*</span></label>
                    <select name="category" id="category" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('category') border-red-300 @enderror">
                        <option value="">{{ __('app.select_category') }}</option>
                        <option value="booking_income" {{ old('category') === 'booking_income' ? 'selected' : '' }}>{{ __('app.booking_income') }}</option>
                        <option value="refund" {{ old('category') === 'refund' ? 'selected' : '' }}>{{ __('app.refund') }}</option>
                        <option value="operational_cost" {{ old('category') === 'operational_cost' ? 'selected' : '' }}>{{ __('app.operational_cost') }}</option>
                        <option value="salary" {{ old('category') === 'salary' ? 'selected' : '' }}>{{ __('app.salary') }}</option>
                        <option value="marketing" {{ old('category') === 'marketing' ? 'selected' : '' }}>{{ __('app.marketing') }}</option>
                        <option value="office_cost" {{ old('category') === 'office_cost' ? 'selected' : '' }}>{{ __('app.office_cost') }}</option>
                        <option value="transport" {{ old('category') === 'transport' ? 'selected' : '' }}>{{ __('app.transport') }}</option>
                        <option value="visa_fee" {{ old('category') === 'visa_fee' ? 'selected' : '' }}>{{ __('app.visa_fee') }}</option>
                        <option value="miscellaneous" {{ old('category') === 'miscellaneous' ? 'selected' : '' }}>{{ __('app.miscellaneous') }}</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.description') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('description') border-red-300 @enderror"
                           placeholder="Keterangan transaksi">
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.amount_rp') }} <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" name="amount" id="amount" value="{{ old('amount') }}" min="0" required
                               class="currency-input w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('amount') border-red-300 @enderror"
                               placeholder="Jumlah">
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('date') border-red-300 @enderror">
                    @error('date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('notes') border-red-300 @enderror"
                              placeholder="Catatan">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Evidence -->
                <div x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.evidence') }} <span class="text-xs text-gray-400 font-normal">({{ __('app.optional') }})</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand transition-colors"
                         x-data="{ dragging: false, file: null }"
                         x-on:dragover.prevent="dragging = true"
                         x-on:dragleave.prevent="dragging = false"
                         x-on:drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.evidenceInput.files = $event.dataTransfer.files;"
                         :class="dragging ? 'border-brand bg-brand/5' : ''">
                        <div x-show="!file">
                            <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-gray-600">{{ __('app.drop_files_here') }} <label class="relative text-brand font-medium cursor-pointer hover:underline">{{ __('app.browse') }}<input type="file" name="evidence" x-ref="evidenceInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.pdf" @change="file = $el.files[0]"></label></p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF - {{ __('app.max_file_size') }}</p>
                        </div>
                        <div x-show="file" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium text-gray-700 truncate" x-text="file?.name"></p>
                                <p class="text-xs text-gray-400" x-text="file ? (file.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                            </div>
                            <button type="button" @click="file = null; $refs.evidenceInput.value = ''" class="text-gray-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                </button>
                            </div>
                    </div>
                    @error('evidence')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('accounting.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.save_entry') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
