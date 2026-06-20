@extends('layouts.app', ['pageTitle' => __('app.add_schedule')])

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('plan-schedules.store') }}" class="space-y-6">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ __('app.basic_information') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.package') }} <span class="text-red-500">*</span></label>
                    <select name="package_id" id="package_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
                        <option value="">{{ __('app.select_package') }}</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - {{ ucfirst($package->type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="planned_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.planned_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="planned_date" id="planned_date" value="{{ old('planned_date', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
                    @error('planned_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="target_pax" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.target_pax') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="target_pax" id="target_pax" value="{{ old('target_pax', 1) }}" min="1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none"
                        placeholder="Jumlah">
                    @error('target_pax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none"
                        placeholder="{{ __('app.notes_optional') }}">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('plan-schedules.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.save') }}</button>
        </div>
    </form>
</div>
@endsection