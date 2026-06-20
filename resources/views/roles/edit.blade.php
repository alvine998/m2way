@extends('layouts.app', ['pageTitle' => __('app.edit_role')])

@section('content')
<div>
    <div class="max-w-2xl mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('app.edit_role') }} - {{ $role->name }}</h2>
            </div>
            <form method="POST" action="{{ route('roles.update', $role) }}" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('name') border-red-300 @enderror"
                               placeholder="Nama peran">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.slug') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $role->slug) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('slug') border-red-300 @enderror"
                               placeholder="admin">
                        <p class="mt-1 text-xs text-gray-400">{{ __('app.slug_help') }}</p>
                        @error('slug')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('roles.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permissions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('app.permissions') }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ __('app.permissions_help') }}</p>
        </div>
        <form method="POST" action="{{ route('roles.update', $role) }}" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $role->name }}">
            <input type="hidden" name="slug" value="{{ $role->slug }}">

            @php $rolePermissionSlugs = $role->permissions->pluck('permission')->toArray(); @endphp

            <div class="space-y-6">
                @foreach(\App\Models\Role::allPermissions() as $group => $permissions)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2">{{ $group }}</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($permissions as $slug => $label)
                                <label class="flex items-center space-x-2 px-3 py-2 border border-gray-200 rounded-lg cursor-pointer hover:border-brand transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand/5">
                                    <input type="checkbox" name="permissions[]" value="{{ $slug }}"
                                           {{ in_array($slug, $rolePermissionSlugs) ? 'checked' : '' }}
                                           class="text-brand focus:ring-brand rounded">
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('roles.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">{{ __('app.cancel') }}</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">{{ __('app.update_permissions') }}</button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('name')?.addEventListener('input', function() {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
