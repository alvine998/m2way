@extends('layouts.app', ['pageTitle' => __('app.accounting_categories')])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('accounting-categories.index') }}" class="flex items-center gap-3">
            <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all_types') }}</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>{{ __('app.income') }}</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>{{ __('app.expense') }}</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.filter') }}</button>
        </form>
        <a href="{{ route('accounting-categories.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('app.add_category') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories ?? [] as $category)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $category->color }}20">
                        <span class="text-lg">{{ $category->icon ?? '📁' }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $category->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    @if(!$category->is_active)
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">{{ __('app.inactive') }}</span>
                    @endif
                    <a href="{{ route('accounting-categories.edit', $category) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('accounting-categories.destroy', $category) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete_category') }}')) this.$el.submit();">
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
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_categories_found') }}</p>
            <a href="{{ route('accounting-categories.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">
                {{ __('app.add_first_category') }} &rarr;
            </a>
        </div>
        @endforelse
    </div>

    @if(isset($categories) && $categories->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
