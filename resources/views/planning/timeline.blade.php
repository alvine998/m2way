@extends('layouts.app', ['pageTitle' => __('app.planning_timeline')])

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-sidebar to-gray-800 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-brand">{{ __('app.planning_center') }}</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight">{{ __('app.departure_timeline') }}</h2>
                <p class="mt-2 max-w-2xl text-sm text-gray-300">{{ __('app.sorted_by_departure') }}
                    Track package departures, remaining quota, booking progress, and operational readiness in one timeline.
                </p>
            </div>
            <a href="{{ route('planning.calendar') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-sidebar transition hover:bg-gray-100">
                {{ __('app.open_calendar') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('app.package_milestones') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('app.sorted_by_departure') }}</p>
            </div>

            <div class="p-6">
                @forelse($packages as $package)
                    @php
                        $quota = max((int) $package->quota, 0);
                        $remaining = max((int) $package->quota_remaining, 0);
                        $booked = $quota > 0 ? max($quota - $remaining, 0) : (int) $package->transactions_count;
                        $progress = $quota > 0 ? min(100, round(($booked / max($quota, 1)) * 100)) : 0;
                        $daysLeft = $package->departure_date ? now()->startOfDay()->diffInDays($package->departure_date, false) : null;
                        $statusClass = match ($package->status) {
                            'active' => 'bg-green-100 text-green-700',
                            'draft' => 'bg-gray-100 text-gray-700',
                            'inactive' => 'bg-red-100 text-red-700',
                            default => 'bg-blue-100 text-blue-700',
                        };
                    @endphp

                    <div class="relative flex gap-5 pb-7 last:pb-0">
                        <div class="flex flex-col items-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/10 text-brand">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            @unless($loop->last)
                                <div class="mt-3 h-full min-h-16 w-px bg-gray-200"></div>
                            @endunless
                        </div>

                        <div class="flex-1 rounded-2xl border border-gray-100 bg-gray-50/60 p-5">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-base font-semibold text-gray-900">{{ $package->name }}</h4>
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($package->status) }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ ucfirst($package->type) }} package
                                        @if($package->departure_city)
                                            from {{ $package->departure_city }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-left lg:text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ optional($package->departure_date)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($daysLeft === null)
                                            Date not set
                                        @elseif($daysLeft > 0)
                                            {{ $daysLeft }} days left
                                        @elseif($daysLeft === 0)
                                            Departing today
                                        @else
                                            {{ abs($daysLeft) }} days ago
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Duration</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-800">{{ $package->duration }} days</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Bookings</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-800">{{ $booked }} / {{ $quota ?: 'No quota' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Price</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-800">Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="mb-2 flex items-center justify-between text-xs text-gray-500">
                                    <span>Quota progress</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200">
                                    <div class="h-full rounded-full bg-brand" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-200 p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">{{ __('app.no_departures_planned') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('app.create_first_package') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.upcoming_bookings') }}</h3>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse($upcomingTransactions as $transaction)
                        <div class="rounded-xl border border-gray-100 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->customer->name ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $transaction->package->name ?? $transaction->transaction_number }}</p>
                                </div>
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700">{{ ucfirst($transaction->status) }}</span>
                            </div>
                            <p class="mt-3 text-xs font-medium text-gray-500">{{ optional($transaction->departure_date)->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">{{ __('app.no_dated_bookings') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.plan_schedule') }}</h3>
                    <a href="{{ route('plan-schedules.index') }}" class="text-sm font-medium text-brand hover:text-brand/80">{{ __('app.view_all') }} &rarr;</a>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse($plannedSchedules as $schedule)
                        <div class="rounded-xl border border-gray-100 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $schedule->package->name ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $schedule->package->type ? ucfirst($schedule->package->type) : '-' }}</p>
                                </div>
                                @php
                                    $statusClasses = [
                                        'planned' => 'bg-blue-100 text-blue-700',
                                        'active' => 'bg-green-100 text-green-700',
                                        'completed' => 'bg-gray-100 text-gray-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses[$schedule->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-xs font-medium text-gray-500">{{ $schedule->planned_date->format('d M Y') }}</p>
                                <p class="text-xs font-medium text-gray-600">{{ $schedule->booked_pax }} / {{ $schedule->target_pax }} pax</p>
                            </div>
                            @if($schedule->notes)
                                <p class="mt-2 text-xs text-gray-400">{{ Str::limit($schedule->notes, 60) }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">{{ __('app.no_schedules_found') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-brand/20 bg-brand/5 p-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('app.planning_notes') }}</h3>
                <ul class="mt-4 space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-brand"></span><span>{{ __('app.use_timeline_note') }}</span></li>
                    <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-brand"></span><span>{{ __('app.use_calendar_note') }}</span></li>
                    <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-brand"></span><span>{{ __('app.data_from_records_note') }}</span></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection
