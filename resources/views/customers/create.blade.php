@extends('layouts.app', ['pageTitle' => __('app.add_customer')])

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.customer_management') }}</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.full_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nama lengkap" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="email@example.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.phone') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('phone') border-red-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.address') }}</label>
                        <textarea name="address" id="address" rows="3" placeholder="Alamat lengkap"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Type -->
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.type') }}</label>
                        <select name="id_type" id="id_type"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('id_type') border-red-300 @enderror">
                            <option value="">{{ __('app.select') }} {{ __('app.type') }}</option>
                            <option value="nik" {{ old('id_type') === 'nik' ? 'selected' : '' }}>{{ __('app.ktp') }}</option>
                            <option value="passport" {{ old('id_type') === 'passport' ? 'selected' : '' }}>{{ __('app.passport') }}</option>
                        </select>
                        @error('id_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Number -->
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.id_number') }}</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" placeholder="No. KTP/Paspor"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all font-mono @error('id_number') border-red-300 @enderror">
                        @error('id_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.gender') }}</label>
                        <select name="gender" id="gender"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('gender') border-red-300 @enderror">
                            <option value="">{{ __('app.select') }} {{ __('app.gender') }}</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>{{ __('app.male') }}</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.birth_date') }}</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('date_of_birth') border-red-300 @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.nationality') }}</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', 'Indonesian') }}" placeholder="Indonesia"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('nationality') border-red-300 @enderror">
                        @error('nationality')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.occupation') }}</label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" placeholder="Pekerjaan"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('occupation') border-red-300 @enderror">
                        @error('occupation')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Catatan tambahan"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Attachments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.id_card_attachment') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('app.max_file_size') }}</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- KTP Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.ktp') }}</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand transition-colors"
                             x-data="{ dragging: false, file: null }"
                             x-on:dragover.prevent="dragging = true"
                             x-on:dragleave.prevent="dragging = false"
                             x-on:drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.ktpInput.files = $event.dataTransfer.files;"
                             :class="dragging ? 'border-brand bg-brand/5' : ''">
                            <template x-if="!file">
                                <div>
                                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ __('app.drop_files_here') }} <label class="relative text-brand font-medium cursor-pointer hover:underline">{{ __('app.browse') }}<input type="file" name="ktp" x-ref="ktpInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.pdf" @change="file = $el.files[0]"></label></p>
                                    <p class="text-xs text-gray-400 mt-1">{{ __('app.max_file_size') }}</p>
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
                                    <button type="button" @click="file = null; $refs.ktpInput.value = ''" class="text-gray-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('ktp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Passport Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.passport') }}</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-brand transition-colors"
                             x-data="{ dragging: false, file: null }"
                             x-on:dragover.prevent="dragging = true"
                             x-on:dragleave.prevent="dragging = false"
                             x-on:drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.passportInput.files = $event.dataTransfer.files;"
                             :class="dragging ? 'border-brand bg-brand/5' : ''">
                            <template x-if="!file">
                                <div>
                                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ __('app.drop_files_here') }} <label class="relative text-brand font-medium cursor-pointer hover:underline">{{ __('app.browse') }}<input type="file" name="passport" x-ref="passportInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.pdf" @change="file = $el.files[0]"></label></p>
                                    <p class="text-xs text-gray-400 mt-1">{{ __('app.max_file_size') }}</p>
                                </div>
                            </template>
                            <template x-if="file">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium text-gray-700 truncate" x-text="file?.name"></p>
                                        <p class="text-xs text-gray-400" x-text="file ? (file.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                                    </div>
                                    <button type="button" @click="file = null; $refs.passportInput.value = ''" class="text-gray-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('passport')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('customers.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.save') }}</button>
        </div>
    </form>
</div>
@endsection
