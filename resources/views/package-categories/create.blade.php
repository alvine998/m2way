@extends('layouts.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('package-categories.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf

        <div class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300' }} bg-white px-3 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-colors"
                    placeholder="e.g., Hajj, Umroh, Ziarah" />
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                    class="w-full rounded-lg border {{ $errors->has('slug') ? 'border-red-300' : 'border-gray-300' }} bg-white px-3 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-colors"
                    placeholder="auto-generated-from-name" />
                <p class="mt-1 text-xs text-gray-400">Leave blank to auto-generate from name.</p>
                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border {{ $errors->has('description') ? 'border-red-300' : 'border-gray-300' }} bg-white px-3 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-colors"
                    placeholder="Optional description for this category">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Icon & Colors Row -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon (Emoji)</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon') }}" maxlength="10"
                        class="w-full rounded-lg border {{ $errors->has('icon') ? 'border-red-300' : 'border-gray-300' }} bg-white px-3 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-colors text-center text-xl"
                        placeholder="🕋" />
                    @error('icon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Text Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="color" name="color" value="{{ old('color', '#f53003') }}"
                            class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5" />
                        <input type="text" value="{{ old('color', '#f53003') }}" id="color_text"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-mono focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none"
                            oninput="document.getElementById('color').value = this.value; updatePreview();" />
                    </div>
                    @error('color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Background Color -->
                <div>
                    <label for="bg_color" class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="bg_color" name="bg_color" value="{{ old('bg_color', '#fff4ed') }}"
                            class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5" />
                        <input type="text" value="{{ old('bg_color', '#fff4ed') }}" id="bg_color_text"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-mono focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none"
                            oninput="document.getElementById('bg_color').value = this.value; updatePreview();" />
                    </div>
                    @error('bg_color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Badge Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Badge Preview</label>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span id="badge-preview" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold"
                          style="background-color: {{ old('bg_color', '#fff4ed') }}; color: {{ old('color', '#f53003') }};">
                        <span id="preview-icon">{{ old('icon') }}</span><span id="preview-name">Category Name</span>
                    </span>
                </div>
            </div>

            <!-- Sort Order & Active -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full rounded-lg border {{ $errors->has('sort_order') ? 'border-red-300' : 'border-gray-300' }} bg-white px-3 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-colors"
                        placeholder="Urutan" />
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-brand focus:ring-brand/20" />
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
            <a href="{{ route('package-categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">Create Category</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('name').addEventListener('input', function() {
        if (!document.getElementById('slug').value || document.getElementById('slug').dataset.auto === 'true') {
            const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            document.getElementById('slug').value = slug;
            document.getElementById('slug').dataset.auto = 'true';
        }
        updatePreview();
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.auto = 'false';
    });

    document.getElementById('icon').addEventListener('input', updatePreview);
    document.getElementById('color').addEventListener('input', function() {
        document.getElementById('color_text').value = this.value;
        updatePreview();
    });
    document.getElementById('bg_color').addEventListener('input', function() {
        document.getElementById('bg_color_text').value = this.value;
        updatePreview();
    });

    function updatePreview() {
        const name = document.getElementById('name').value || 'Category Name';
        const icon = document.getElementById('icon').value;
        const color = document.getElementById('color').value;
        const bgColor = document.getElementById('bg_color').value;

        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-icon').textContent = icon ? icon + ' ' : '';

        const badge = document.getElementById('badge-preview');
        badge.style.backgroundColor = bgColor;
        badge.style.color = color;
    }
</script>
@endsection
