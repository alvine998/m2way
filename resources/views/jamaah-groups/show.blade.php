@extends('layouts.app', ['pageTitle' => $jamaahGroup->name])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('jamaah-groups.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $jamaahGroup->name }}</h2>
                <p class="text-sm text-gray-500">{{ $jamaahGroup->group_number }}</p>
            </div>
        </div>
        <a href="{{ route('jamaah-groups.edit', $jamaahGroup) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('app.edit') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.group_detail') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.package') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $jamaahGroup->package->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.leader') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $jamaahGroup->leader->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.departure_date') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $jamaahGroup->departure_date->format('d M Y') }}</p>
                    </div>
                    @if($jamaahGroup->return_date)
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.return_date') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $jamaahGroup->return_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.status') }}</p>
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jamaahGroup->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($jamaahGroup->status) }}
                        </span>
                    </div>
                    @if($jamaahGroup->notes)
                    <div>
                        <p class="text-xs text-gray-400">{{ __('app.notes') }}</p>
                        <p class="text-sm text-gray-700">{{ $jamaahGroup->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.quota') }}</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $jamaahGroup->memberCount() }} <span class="text-lg text-gray-400">/ {{ $jamaahGroup->quota }}</span></p>
                        <p class="text-sm text-gray-500">{{ __('app.members') }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-brand rounded-full h-2 transition-all" style="width: {{ $jamaahGroup->quota > 0 ? ($jamaahGroup->memberCount() / $jamaahGroup->quota) * 100 : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ $jamaahGroup->availableSlots() }} {{ __('app.available_slots') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.members') }} ({{ $jamaahGroup->customers->count() }})</h3>
                </div>
                <div class="p-6">
                    @forelse($jamaahGroup->customers as $customer)
                        <div class="flex items-center gap-4 p-3 border border-gray-100 rounded-lg {{ !$loop->last ? 'mb-3' : '' }}">
                            <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-brand text-sm font-semibold">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                {{ __('app.' . ($customer->pivot->room_type ?? 'double')) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">{{ __('app.no_data') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
