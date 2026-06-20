@extends('layouts.app', ['pageTitle' => __('app.packages')])

@section('content')
<div class="space-y-6">
    <!-- Filters & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('packages.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all_types') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('type') === $cat->slug ? 'selected' : '' }}>{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.filter') }}</button>
        </form>
        <a href="{{ route('packages.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('app.add_package') }}
        </a>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages ?? [] as $package)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                @php
                    $cat = $categories->firstWhere('slug', $package->type);
                @endphp
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                      style="background-color: {{ $cat->bg_color ?? '#f3f4f6' }}; color: {{ $cat->color ?? '#6b7280' }};">
                    {{ $cat->icon ? $cat->icon . ' ' : '' }}{{ strtoupper($package->type) }}
                </span>
                @php
                    $statusColors = [
                        'draft' => 'bg-purple-100 text-purple-700',
                        'active' => 'bg-green-100 text-green-700',
                        'inactive' => 'bg-gray-100 text-gray-700',
                        'full' => 'bg-yellow-100 text-yellow-700',
                    ];
                @endphp
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$package->status ?? 'active'] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($package->status ?? 'active') }}
                </span>
            </div>
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $package->duration }} {{ __('app.days') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('app.departure') }}: {{ $package->departure_date?->format('d M Y') ?? '-' }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('app.quota') }}: {{ $package->quota }} {{ __('app.seats') }}
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-end justify-between">
                    <div>
                        <p class="text-xs text-gray-500">{{ __('app.price_per_person') }}</p>
                        <p class="text-xl font-bold text-brand">Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('packages.edit', $package) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('packages.destroy', $package) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete_package') }}')) this.$el.submit();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('app.delete') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="mt-3 text-sm text-gray-500">{{ __('app.no_packages_found') }}</p>
                <a href="{{ route('packages.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">
                    {{ __('app.create_first_package') }} &rarr;
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if(isset($packages) && $packages->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $packages->links() }}
    </div>
    @endif
</div>
@endsection
