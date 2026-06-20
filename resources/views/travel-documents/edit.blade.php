@extends('layouts.app', ['pageTitle' => __('app.edit_document')])

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('travel-documents.update', $travelDocument) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.edit_document') }}</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-search-select name="customer_id" :value="old('customer_id', $travelDocument->customer_id)" label="{{ __('app.customer') }}" placeholder="{{ __('app.customer') }}" required
                        :options="$customers->map(fn($c) => ['value' => $c->id, 'label' => $c->name])" />
                    @error('customer_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.document_type') }} <span class="text-red-500">*</span></label>
                    <select name="document_type" id="document_type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('document_type') border-red-300 @enderror">
                        <option value="">Select Type</option>
                        <option value="passport" {{ old('document_type', $travelDocument->document_type) === 'passport' ? 'selected' : '' }}>{{ __('app.passport') }}</option>
                        <option value="ktp" {{ old('document_type', $travelDocument->document_type) === 'ktp' ? 'selected' : '' }}>{{ __('app.ktp') }}</option>
                        <option value="visa" {{ old('document_type', $travelDocument->document_type) === 'visa' ? 'selected' : '' }}>{{ __('app.visa') }}</option>
                        <option value="vaccination" {{ old('document_type', $travelDocument->document_type) === 'vaccination' ? 'selected' : '' }}>{{ __('app.vaccination') }}</option>
                        <option value="insurance" {{ old('document_type', $travelDocument->document_type) === 'insurance' ? 'selected' : '' }}>{{ __('app.insurance') }}</option>
                        <option value="ticket" {{ old('document_type', $travelDocument->document_type) === 'ticket' ? 'selected' : '' }}>{{ __('app.ticket') }}</option>
                        <option value="hotel" {{ old('document_type', $travelDocument->document_type) === 'hotel' ? 'selected' : '' }}>{{ __('app.hotel') }}</option>
                        <option value="other" {{ old('document_type', $travelDocument->document_type) === 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('document_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="document_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.document_number') }}</label>
                    <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $travelDocument->document_number) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all font-mono @error('document_number') border-red-300 @enderror"
                           placeholder="No. dokumen">
                    @error('document_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="issuing_country" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.issuing_country') }}</label>
                    <input type="text" name="issuing_country" id="issuing_country" value="{{ old('issuing_country', $travelDocument->issuing_country) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('issuing_country') border-red-300 @enderror"
                           placeholder="Negara penerbit">
                    @error('issuing_country') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.issue_date') }}</label>
                    <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', $travelDocument->issue_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('issue_date') border-red-300 @enderror">
                    @error('issue_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.expiry_date') }}</label>
                    <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $travelDocument->expiry_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('expiry_date') border-red-300 @enderror">
                    @error('expiry_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    @if($travelDocument->file_name)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">{{ $travelDocument->file_name }}</p>
                                <p class="text-xs text-gray-400">{{ $travelDocument->formatted_size }}</p>
                            </div>
                            <span class="text-xs text-green-600 font-medium">{{ __('app.upload_document') }}</span>
                        </div>
                    @endif
                    <label class="block text-sm font-medium text-gray-700 mb-2">Replace File (optional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-brand transition-colors"
                         x-data="{ file: null }">
                        <template x-if="!file">
                            <div>
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm text-gray-600 mb-2">Drag & drop or <label class="text-brand font-medium cursor-pointer hover:underline">browse<input type="file" name="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="file = $el.files[0]"></label></p>
                                <p class="text-xs text-gray-400">JPG, PNG, PDF (max 20MB)</p>
                            </div>
                        </template>
                        <template x-if="file">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="text-sm font-medium text-gray-700 truncate" x-text="file?.name"></p>
                                    <p class="text-xs text-gray-400" x-text="file ? (file.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                                </div>
                                <button type="button" @click="file = null" class="text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('notes') border-red-300 @enderror"
                              placeholder="Catatan">{{ old('notes', $travelDocument->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('travel-documents.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.save') }}</button>
        </div>
    </form>
</div>
@endsection
