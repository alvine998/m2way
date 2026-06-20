@extends('layouts.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('customers.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h2>
                <p class="text-sm text-gray-500">{{ __('app.customer_detail') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('app.edit') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.name') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xl font-bold">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($customer->gender ?? 'N/A') }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.email') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.phone') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.address') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ strtoupper($customer->id_type ?? 'NIK') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->id_number ?? '-' }}</p>
                            </div>
                        </div>

                        @if($customer->date_of_birth)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.birth_date') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->date_of_birth->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($customer->occupation)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.occupation') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->occupation }}</p>
                            </div>
                        </div>
                        @endif

                        @if($customer->notes)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('app.notes') }}</p>
                                <p class="text-sm text-gray-700">{{ $customer->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.total_transactions') }}</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $customer->transactions()->count() }}</p>
                        <p class="text-sm text-gray-500">{{ __('app.total_transactions') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="attachmentManager()">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('app.customer_attachments') }} ({{ $customer->attachments()->count() }})</h3>
                    <button type="button" @click="showUpload = true" class="inline-flex items-center px-3 py-1.5 bg-brand text-white text-xs font-semibold rounded-lg hover:bg-brand/90 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        {{ __('app.upload_file') }}
                    </button>
                </div>

                <!-- Upload Modal -->
                <div x-show="showUpload" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-500/75" @click="showUpload = false"></div>
                        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('app.upload_file') }}</h3>
                                <button @click="showUpload = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('customer-attachments.store', $customer) }}" enctype="multipart/form-data" id="uploadForm">
                                @csrf
                                <div class="space-y-4">
                                    <!-- Drop Zone -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-brand transition-colors"
                                         x-on:dragover.prevent="dragging = true"
                                         x-on:dragleave.prevent="dragging = false"
                                         x-on:drop.prevent="dragging = false; handleDrop($event)"
                                         :class="dragging ? 'border-brand bg-brand/5' : ''">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 mb-2">{{ __('app.drop_files_here') }}</p>
                                        <label class="relative inline-flex items-center px-4 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:bg-brand/90 transition-colors cursor-pointer">
                                            {{ __('app.browse') }}
                                            <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx" @change="handleFileSelect($event)">
                                        </label>
                                        <p class="text-xs text-gray-400 mt-3">{{ __('app.max_file_size') }}</p>
                                    </div>

                                    <!-- Selected File Preview -->
                                    <div x-show="selectedFile" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                             :class="isImage ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-700 truncate" x-text="selectedFile?.name"></p>
                                            <p class="text-xs text-gray-400" x-text="formatSize(selectedFile?.size || 0)"></p>
                                        </div>
                                        <button type="button" @click="clearFile()" class="text-gray-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.description') }} ({{ __('app.optional') }})</label>
                                        <input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none" placeholder="{{ __('app.description') }}">
                                    </div>

                                    <!-- Error message -->
                                    <div x-show="errorMsg" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600" x-text="errorMsg" style="display: none;"></div>
                                </div>

                                <div class="flex items-center justify-end gap-3 mt-6">
                                    <button type="button" @click="showUpload = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ __('app.cancel') }}</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand rounded-lg hover:bg-brand/90 transition-colors" :disabled="!selectedFile">{{ __('app.save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Attachments List -->
                <div class="p-6">
                    @forelse($customer->attachments as $attachment)
                        <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors {{ !$loop->last ? 'mb-3' : '' }}">
                            <!-- File Icon/Thumbnail -->
                            @if($attachment->isImage())
                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                    <img src="{{ $attachment->url }}" alt="{{ $attachment->file_name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center shrink-0
                                    {{ $attachment->isPdf() ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                    @if($attachment->isPdf())
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            @endif

                            <!-- File Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->file_name }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-400 mt-0.5">
                                    <span>{{ $attachment->formatted_size }}</span>
                                    <span>•</span>
                                    <span>{{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }}</span>
                                    @if($attachment->description)
                                        <span>•</span>
                                        <span>{{ $attachment->description }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ $attachment->url }}" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('app.preview') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('customer-attachments.download', [$customer, $attachment]) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="{{ __('app.download') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('customer-attachments.destroy', [$customer, $attachment]) }}" x-data @submit.prevent="if(confirm('{{ __('app.confirm_delete') }}')) this.$el.submit();">
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
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-3 text-sm text-gray-500">{{ __('app.no_data') }}</p>
                            <button @click="showUpload = true" class="mt-2 text-sm font-medium text-brand hover:text-brand/80">{{ __('app.upload_file') }} &rarr;</button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function attachmentManager() {
    return {
        showUpload: false,
        selectedFile: null,
        dragging: false,
        errorMsg: '',
        isImage: false,

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.validateAndSet(file);
        },

        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file) this.validateAndSet(file);
        },

        validateAndSet(file) {
            this.errorMsg = '';
            const maxSize = 20 * 1024 * 1024; // 20MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf',
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            if (file.size > maxSize) {
                this.errorMsg = 'File size exceeds 20 MB limit.';
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                this.errorMsg = 'File type not allowed. Please upload JPG, PNG, PDF, DOC, or XLS files.';
                return;
            }

            this.selectedFile = file;
            this.isImage = file.type.startsWith('image/');

            // Auto-set description based on file type
            const descInput = document.querySelector('input[name="description"]');
            if (descInput && !descInput.value) {
                const ext = file.name.split('.').pop().toLowerCase();
                const types = { 'jpg': 'Photo', 'jpeg': 'Photo', 'png': 'Photo', 'pdf': 'PDF Document', 'doc': 'Word Document', 'docx': 'Word Document', 'xls': 'Excel Spreadsheet', 'xlsx': 'Excel Spreadsheet' };
                descInput.value = types[ext] || 'Document';
            }
        },

        clearFile() {
            this.selectedFile = null;
            this.isImage = false;
            this.errorMsg = '';
            document.querySelector('input[name="file"]').value = '';
        },

        formatSize(bytes) {
            if (!bytes) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
            return Math.round(bytes * 10) / 10 + ' ' + units[i];
        }
    }
}
</script>
@endsection
