@extends('layouts.app', ['pageTitle' => __('app.create_package')])

@section('content')
<div class="max-w-4xl" x-data="packageForm()">
    <form method="POST" action="{{ route('packages.store') }}">
        @csrf
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.basic_information') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.package_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Paket" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('name') border-red-300 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.type') }} <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('type') border-red-300 @enderror">
                            <option value="">{{ __('app.select_type') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ old('type') === $cat->slug ? 'selected' : '' }}>{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.duration_days') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration') }}" placeholder="Hari" min="1" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('duration') border-red-300 @enderror">
                        @error('duration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }} <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('status') border-red-300 @enderror">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('app.draft') }}</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.description') }}</label>
                        <textarea name="description" id="description" rows="3" placeholder="Deskripsi paket"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.schedule') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.departure_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('departure_date') border-red-300 @enderror">
                        @error('departure_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.return_date') }}</label>
                        <input type="date" name="return_date" id="return_date" value="{{ old('return_date') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('return_date') border-red-300 @enderror">
                        @error('return_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="departure_city" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.departure_city') }}</label>
                        <input type="text" name="departure_city" id="departure_city" value="{{ old('departure_city', 'Jakarta') }}" placeholder="Kota keberangkatan"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('departure_city') border-red-300 @enderror">
                        @error('departure_city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Quota -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.pricing_quota') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.base_price_rp') }} <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" name="base_price" id="base_price" value="{{ old('base_price') }}" placeholder="Harga dasar" min="0" required
                               class="currency-input w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('base_price') border-red-300 @enderror">
                        @error('base_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="quota" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.quota_seats') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="quota" id="quota" value="{{ old('quota') }}" placeholder="Jumlah kursi" min="1" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('quota') border-red-300 @enderror">
                        @error('quota') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- HPP Cost Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.cost_breakdown_hpp') }}</h2>
                    <button type="button" @click="addCostItem()" class="inline-flex items-center px-3 py-1.5 bg-brand/10 text-brand text-xs font-semibold rounded-lg hover:bg-brand/20 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('app.add_item') }}
                    </button>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <template x-for="(item, index) in costItems" :key="index">
                            <div class="flex items-center gap-3">
                                <input type="text" :name="'cost_details[' + index + '][label]'" x-model="item.label" placeholder="{{ __('app.cost_label_placeholder') }}"
                                       class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all">
                                <input type="number" :name="'cost_details[' + index + '][amount]'" x-model.number="item.amount" placeholder="{{ __('app.amount') }}" min="0"
                                       class="w-48 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all text-right">
                                <button type="button" @click="removeCostItem(index)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" x-show="costItems.length > 1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ __('app.total_hpp') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-xl font-bold text-gray-900">Rp <span x-text="formatNumber(totalHpp)"></span></span>
                            <input type="hidden" name="hpp" :value="totalHpp">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('app.additional_details') }}</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="hotel_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.hotel') }}</label>
                        <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name') }}" placeholder="Nama hotel"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('hotel_name') border-red-300 @enderror">
                        @error('hotel_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="airline" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.airline') }}</label>
                        <input type="text" name="airline" id="airline" value="{{ old('airline') }}" placeholder="Nama maskapai"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('airline') border-red-300 @enderror">
                        @error('airline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="includes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.includes') }}</label>
                        <textarea name="includes" id="includes" rows="3" placeholder="{{ __('app.one_item_per_line') }}"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('includes') border-red-300 @enderror">{{ old('includes') }}</textarea>
                        @error('includes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="excludes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.excludes') }}</label>
                        <textarea name="excludes" id="excludes" rows="3" placeholder="{{ __('app.one_item_per_line') }}"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('excludes') border-red-300 @enderror">{{ old('excludes') }}</textarea>
                        @error('excludes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('packages.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.create_package_button') }}</button>
            </div>
        </div>
    </form>
</div>

<script>
function packageForm() {
    return {
        costItems: [{ label: '', amount: 0 }],
        get totalHpp() {
            return this.costItems.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
        },
        addCostItem() {
            this.costItems.push({ label: '', amount: 0 });
        },
        removeCostItem(index) {
            this.costItems.splice(index, 1);
        },
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endsection
