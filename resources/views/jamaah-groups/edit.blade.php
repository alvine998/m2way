@extends('layouts.app', ['pageTitle' => __('app.edit_group')])

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('jamaah-groups.update', $jamaahGroup) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.edit_group') }} - {{ $jamaahGroup->group_number }}</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.group_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $jamaahGroup->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('name') border-red-300 @enderror"
                           placeholder="Nama kelompok">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.package') }} <span class="text-red-500">*</span></label>
                    <select name="package_id" id="package_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('package_id') border-red-300 @enderror">
                        <option value="">{{ __('app.select_package') }}</option>
                        @foreach($packages ?? [] as $pkg)
                        <option value="{{ $pkg->id }}" {{ old('package_id', $jamaahGroup->package_id) == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }} ({{ strtoupper($pkg->type) }})</option>
                        @endforeach
                    </select>
                    @error('package_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-search-select name="leader_id" :value="old('leader_id', $jamaahGroup->leader_id)" label="{{ __('app.group_leader') }}" placeholder="{{ __('app.select_leader') }}"
                        :options="$customers->map(fn($c) => ['value' => $c->id, 'label' => $c->name])" />
                    @error('leader_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="quota" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.quota') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="quota" id="quota" value="{{ old('quota', $jamaahGroup->quota) }}" min="1" max="100" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('quota') border-red-300 @enderror"
                           placeholder="Jumlah">
                    @error('quota') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.departure_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date', $jamaahGroup->departure_date?->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('departure_date') border-red-300 @enderror">
                    @error('departure_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.return_date') }}</label>
                    <input type="date" name="return_date" id="return_date" value="{{ old('return_date', $jamaahGroup->return_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('return_date') border-red-300 @enderror">
                    @error('return_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }} <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('status') border-red-300 @enderror">
                        <option value="active" {{ old('status', $jamaahGroup->status) === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                        <option value="completed" {{ old('status', $jamaahGroup->status) === 'completed' ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                        <option value="cancelled" {{ old('status', $jamaahGroup->status) === 'cancelled' ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all resize-none @error('notes') border-red-300 @enderror"
                              placeholder="Catatan">{{ old('notes', $jamaahGroup->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.members') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('app.add_members') }}</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                    @foreach($customers ?? [] as $customer)
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="customers[]" value="{{ $customer->id }}"
                               {{ in_array($customer->id, old('customers', $selectedCustomers ?? [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-brand border-gray-300 rounded focus:ring-brand">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('customers') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('jamaah-groups.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.save') }}</button>
        </div>
    </form>
</div>
@endsection
