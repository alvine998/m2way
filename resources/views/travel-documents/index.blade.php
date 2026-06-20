@extends('layouts.app', ['pageTitle' => __('app.travel_documents')])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('travel-documents.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search') }}..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all">
            </div>
            <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none bg-white">
                <option value="">{{ __('app.all') }} {{ __('app.type') }}</option>
                <option value="passport" {{ request('type') === 'passport' ? 'selected' : '' }}>{{ __('app.passport') }}</option>
                <option value="ktp" {{ request('type') === 'ktp' ? 'selected' : '' }}>{{ __('app.ktp') }}</option>
                <option value="visa" {{ request('type') === 'visa' ? 'selected' : '' }}>{{ __('app.visa') }}</option>
                <option value="vaccination" {{ request('type') === 'vaccination' ? 'selected' : '' }}>{{ __('app.vaccination') }}</option>
                <option value="insurance" {{ request('type') === 'insurance' ? 'selected' : '' }}>{{ __('app.insurance') }}</option>
                <option value="ticket" {{ request('type') === 'ticket' ? 'selected' : '' }}>{{ __('app.ticket') }}</option>
                <option value="hotel" {{ request('type') === 'hotel' ? 'selected' : '' }}>{{ __('app.hotel') }}</option>
                <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.filter') }}</button>
        </form>
        <a href="{{ route('travel-documents.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand/90 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('app.add_document') }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.document_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.issue_date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.expiry_date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents ?? [] as $doc)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/10 flex items-center justify-center text-brand text-xs font-semibold">
                                    {{ substr($doc->customer->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $doc->customer->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'passport' => 'bg-blue-100 text-blue-700',
                                    'ktp' => 'bg-green-100 text-green-700',
                                    'visa' => 'bg-purple-100 text-purple-700',
                                    'vaccination' => 'bg-cyan-100 text-cyan-700',
                                    'insurance' => 'bg-amber-100 text-amber-700',
                                    'ticket' => 'bg-indigo-100 text-indigo-700',
                                    'hotel' => 'bg-pink-100 text-pink-700',
                                    'other' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$doc->document_type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $doc->document_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $doc->document_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $doc->issue_date?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $doc->expiry_date?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($doc->isExpired())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ __('app.expired') }}</span>
                            @elseif($doc->isExpiringSoon())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">{{ __('app.expiring_soon') }}</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ __('app.valid') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @if($doc->file_path)
                                <a href="{{ route('travel-documents.download', $doc) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="{{ __('app.download_document') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ route('travel-documents.edit', $doc) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('travel-documents.destroy', $doc) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete', ['item' => __('app.travel_documents')]) }}')) this.$el.submit();">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_data') }}</p>
                            <a href="{{ route('travel-documents.create') }}" class="mt-3 inline-flex items-center text-sm font-medium text-brand hover:text-brand/80">
                                {{ __('app.add_document') }} &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($documents) && $documents->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
