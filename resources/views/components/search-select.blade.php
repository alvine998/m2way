@props([
    'name',
    'id' => null,
    'options' => [],
    'value' => null,
    'label' => null,
    'placeholder' => 'Search...',
    'required' => false,
    'searchable' => true,
])

@php
    $id = $id ?? $name;
@endphp

<div x-data="{
    open: false,
    search: '',
    selected: '{{ $value }}',
    selectedLabel: '',
    init() {
        this.updateLabel();
        this.$watch('selected', () => this.updateLabel());
    },
    updateLabel() {
        const options = {{ json_encode($options->pluck('label', 'value')->toArray()) }};
        this.selectedLabel = options[this.selected] || '';
    },
    filteredOptions() {
        const opts = {{ json_encode($options->values()->toArray()) }};
        if (!this.search) return opts;
        return opts.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
    },
    select(value, label) {
        this.selected = value;
        this.selectedLabel = label;
        this.open = false;
        this.search = '';
    },
    clear() {
        this.selected = '';
        this.selectedLabel = '';
        this.search = '';
    }
}" x-init="init()" class="relative">
    <input type="hidden" name="{{ $name }}" :value="selected" {{ $required ? 'required' : '' }}>

    @if($label)
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif

    <div class="relative">
        <button type="button" @click="open = !open" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all bg-white flex items-center justify-between"
                :class="open ? 'ring-2 ring-brand/20 border-brand' : ''">
            <span x-text="selectedLabel || '{{ $placeholder }}'" :class="selectedLabel ? 'text-gray-900' : 'text-gray-400'"></span>
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" x-cloak x-transition
             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-hidden" style="display: none;">

            @if($searchable)
            <div class="p-2 border-b border-gray-100">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" @click.stop placeholder="Type to search..."
                           class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none">
                </div>
            </div>
            @endif

            <div class="overflow-y-auto max-h-48">
                <template x-for="option in filteredOptions()" :key="option.value">
                    <button type="button" @click="select(option.value, option.label)"
                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-brand/5 transition-colors flex items-center gap-2"
                            :class="selected == option.value ? 'bg-brand/10 text-brand font-medium' : 'text-gray-700'">
                        <span x-text="option.label"></span>
                    </button>
                </template>
                <div x-show="filteredOptions().length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                    No results found
                </div>
            </div>
        </div>
    </div>
</div>
