@extends('layouts.app', ['pageTitle' => __('app.planning_calendar')])

@section('content')
<div class="space-y-6" x-data="calendarModal()">
    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-brand">{{ __('app.planning_center') }}</p>
            <h2 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">{{ $month->format('F Y') }}</h2>
            <p class="mt-2 text-sm text-gray-500">{{ __('app.sorted_by_departure') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('planning.timeline') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                {{ __('app.timeline') }}
            </a>
            <div class="inline-flex overflow-hidden rounded-xl border border-gray-200">
                <a href="{{ route('planning.calendar', ['month' => $previousMonth]) }}" class="px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.previous') }}</a>
                <a href="{{ route('planning.calendar') }}" class="border-x border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.today') }}</a>
                <a href="{{ route('planning.calendar', ['month' => $nextMonth]) }}" class="px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.next') }}</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">{{ __('app.package_departures') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($packagesCount) }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ __('app.this_month') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">{{ __('app.customer_bookings') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($bookingsCount) }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ __('app.with_departure_dates') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
            <p class="text-sm font-medium text-gray-500">{{ __('app.legend') }}</p>
            <div class="mt-4 flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-brand/10 px-3 py-1.5 text-xs font-semibold text-brand">
                        <span class="h-2 w-2 rounded-full bg-brand"></span>{{ __('app.package') }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>{{ __('app.booking') }}
                    </span>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                <div class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-gray-500">{{ $dayName }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-7">
            @foreach($calendarDays as $day)
                @php
                    $dateKey = $day->toDateString();
                    $dayEvents = $eventsByDate->get($dateKey, collect());
                    $isCurrentMonth = $day->isSameMonth($month);
                    $isToday = $day->isToday();
                @endphp

                <div class="min-h-[150px] border-b border-r border-gray-100 p-3 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50/70' }}">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold {{ $isToday ? 'bg-brand text-white' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400') }}">
                            {{ $day->format('j') }}
                        </span>
                        @if($dayEvents->count() > 0)
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-600">{{ $dayEvents->count() }}</span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        @foreach($dayEvents->take(3) as $event)
                            @php
                                $eventClass = $event['type'] === 'package'
                                    ? 'border-brand/20 bg-brand/10 text-brand'
                                    : 'border-blue-100 bg-blue-50 text-blue-700';
                            @endphp
                            <button @click="showEvent({{ json_encode($event) }})" class="w-full text-left block rounded-lg border px-2.5 py-2 {{ $eventClass }} hover:opacity-80 transition-opacity cursor-pointer">
                                <p class="truncate text-xs font-bold">{{ $event['title'] }}</p>
                                <p class="mt-0.5 truncate text-[11px] opacity-80">{{ $event['subtitle'] }}</p>
                            </button>
                        @endforeach

                        @if($dayEvents->count() > 3)
                            <div class="rounded-lg bg-gray-100 px-2.5 py-2 text-xs font-semibold text-gray-600">
                                +{{ $dayEvents->count() - 3 }} more
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('app.monthly_schedule') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('app.sorted_by_departure') }}</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($eventsByDate->sortKeys() as $date => $events)
                <div class="grid grid-cols-1 gap-4 px-6 py-4 md:grid-cols-[150px_minmax(0,1fr)]">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('l') }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($events as $event)
                            <button @click="showEvent({{ json_encode($event) }})" class="w-full text-left rounded-xl border border-gray-100 bg-gray-50 p-4 hover:bg-gray-100 transition-colors cursor-pointer">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $event['title'] }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $event['subtitle'] }}</p>
                                    </div>
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $event['type'] === 'package' ? 'bg-brand/10 text-brand' : 'bg-blue-50 text-blue-700' }}">
                                        {{ ucfirst($event['type']) }}
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center">
                    <h3 class="text-base font-semibold text-gray-900">{{ __('app.no_events_this_month') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('app.create_first_package') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500/75" @click="showModal = false"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" x-text="event.title"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Type Badge -->
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                              :class="event.type === 'package' ? 'bg-brand/10 text-brand' : 'bg-blue-50 text-blue-700'"
                              x-text="event.type === 'package' ? '{{ __('app.package') }}' : '{{ __('app.booking') }}'">
                        </span>
                        <span class="text-xs text-gray-400" x-text="event.date"></span>
                    </div>

                    <!-- Description -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700">{{ __('app.description') }}</p>
                        <p class="mt-1 text-sm text-gray-600" x-text="event.title"></p>
                    </div>

                    <!-- Subtitle / Details -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700">{{ __('app.details') }}</p>
                        <p class="mt-1 text-sm text-gray-600" x-text="event.subtitle"></p>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700">{{ __('app.status') }}</p>
                        <p class="mt-1 text-sm text-gray-600" x-text="event.status ? event.status.charAt(0).toUpperCase() + event.status.slice(1) : '-'"></p>
                    </div>

                    <!-- Action Button -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a :href="event.route"
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors">
                            {{ __('app.view_details') }}
                        </a>
                        <button @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            {{ __('app.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calendarModal() {
    return {
        showModal: false,
        event: { title: '', subtitle: '', type: '', status: '', date: '', route: '' },
        showEvent(eventData) {
            this.event = eventData;
            this.showModal = true;
        }
    }
}
</script>
@endsection