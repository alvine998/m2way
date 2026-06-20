@extends('layouts.app', ['pageTitle' => 'Edit Category'])

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('accounting-categories.update', $accountingCategory) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Edit Category - {{ $accountingCategory->name }}</h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $accountingCategory->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('name') border-red-300 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white @error('type') border-red-300 @enderror">
                        <option value="">Select Type</option>
                        <option value="income" {{ old('type', $accountingCategory->type) === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type', $accountingCategory->type) === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji)</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon', $accountingCategory->icon) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('icon') border-red-300 @enderror">
                        @error('icon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" id="color" value="{{ old('color', $accountingCategory->color) }}"
                                   class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                            <input type="text" value="{{ old('color', $accountingCategory->color) }}" readonly
                                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700 font-mono">
                        </div>
                        @error('color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $accountingCategory->sort_order) }}" min="0"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all @error('sort_order') border-red-300 @enderror"
                               placeholder="Urutan">
                        @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $accountingCategory->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-brand border-gray-300 rounded focus:ring-brand">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                        @error('is_active') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('accounting-categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors shadow-sm">Update Category</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('color')?.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
</script>
@endsection
