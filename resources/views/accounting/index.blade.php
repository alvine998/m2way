@extends('layouts.app', ['pageTitle' => __('app.accounting')])

@section('content')
<div class="space-y-6">
    <!-- Filters & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('accounting.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all_types') }}</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>{{ __('app.income') }}</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>{{ __('app.expense') }}</option>
            </select>
            <select name="category" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all_categories') }}</option>
                <option value="booking_income" {{ request('category') === 'booking_income' ? 'selected' : '' }}>{{ __('app.booking_income') }}</option>
                <option value="refund" {{ request('category') === 'refund' ? 'selected' : '' }}>{{ __('app.refund') }}</option>
                <option value="operational_cost" {{ request('category') === 'operational_cost' ? 'selected' : '' }}>{{ __('app.operational_cost') }}</option>
                <option value="salary" {{ request('category') === 'salary' ? 'selected' : '' }}>{{ __('app.salary') }}</option>
                <option value="marketing" {{ request('category') === 'marketing' ? 'selected' : '' }}>{{ __('app.marketing') }}</option>
                <option value="office_cost" {{ request('category') === 'office_cost' ? 'selected' : '' }}>{{ __('app.office_cost') }}</option>
                <option value="transport" {{ request('category') === 'transport' ? 'selected' : '' }}>{{ __('app.transport') }}</option>
                <option value="visa_fee" {{ request('category') === 'visa_fee' ? 'selected' : '' }}>{{ __('app.visa_fee') }}</option>
                <option value="miscellaneous" {{ request('category') === 'miscellaneous' ? 'selected' : '' }}>{{ __('app.miscellaneous') }}</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.filter') }}</button>
        </form>
        <a href="{{ route('accounting.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('app.add_entry') }}
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.entry_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.category') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.description') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.amount') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($accountings ?? [] as $entry)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entry->entry_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($entry->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $entry->category)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $entry->description }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-right {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $entry->type === 'income' ? '+' : '-' }} Rp {{ number_format($entry->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('accounting.edit', $entry) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('accounting.destroy', $entry) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete_entry') }}')) this.$el.submit();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('app.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_entries_found') }}</p>
                            <a href="{{ route('accounting.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">
                                {{ __('app.add_first_entry') }} &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($accountings) && $accountings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $accountings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
