@extends('layouts.app', ['pageTitle' => __('app.plan_schedule')])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-brand">{{ __('app.planning_center') }}</p>
            <h2 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">{{ $selectedMonth->format('F Y') }}</h2>
            <p class="mt-2 text-sm text-gray-500">{{ __('app.plan_schedule_subtitle') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex overflow-hidden rounded-xl border border-gray-200">
                <a href="{{ route('plan-schedules.index', ['month' => $previousMonth]) }}" class="px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.previous') }}</a>
                <a href="{{ route('plan-schedules.index') }}" class="border-x border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.today') }}</a>
                <a href="{{ route('plan-schedules.index', ['month' => $nextMonth]) }}" class="px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">{{ __('app.next') }}</a>
            </div>
            <a href="{{ route('plan-schedules.create') }}" class="inline-flex items-center justify-center rounded-xl bg-brand px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand/90">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('app.add_schedule') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.package') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.target_pax') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.booked_pax') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->planned_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $schedule->package->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($schedule->package->type ?? '') === 'hajj' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $schedule->package->type ? ucfirst($schedule->package->type) : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">{{ $schedule->target_pax }} pax</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $schedule->booked_pax }} pax</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'planned' => 'bg-blue-100 text-blue-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'completed' => 'bg-gray-100 text-gray-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$schedule->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('plan-schedules.edit', $schedule) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('plan-schedules.destroy', $schedule) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete_schedule') }}')) this.$el.submit();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('app.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_schedules_found') }}</p>
                            <a href="{{ route('plan-schedules.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">{{ __('app.add_first_schedule') }} &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection