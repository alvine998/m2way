@extends('layouts.app', ['pageTitle' => __('app.roles')])

@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500">{{ __('app.roles_description') }}</p>
        </div>
        <a href="{{ route('roles.create') }}" class="px-4 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('app.add_role') }}</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.name') }}</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.slug') }}</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.users_count') }}</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.permissions_count') }}</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $role->name }}</td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $role->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $role->users_count }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $role->permissions_count ?? $role->permissions()->count() }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('roles.edit', $role) }}" class="p-2 text-gray-400 hover:text-brand rounded-lg hover:bg-brand/5 transition-colors" title="{{ __('app.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($role->users_count === 0)
                                <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                      x-data
                                      @submit.prevent="if(confirm('{{ __('app.confirm_delete_role') }}')) this.$el.submit();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="{{ __('app.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-sm text-gray-500">{{ __('app.no_roles_found') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
