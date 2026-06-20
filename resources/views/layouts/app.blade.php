<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Dashboard' }} - M2Way Travel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1b4dff',
                        sidebar: '#1b1b18',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans bg-gray-100 text-gray-900 antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-sidebar text-white flex flex-col transition-all duration-300 ease-in-out flex-shrink-0">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
                <div class="flex items-center space-x-3" x-show="sidebarOpen">
                    <div class="w-9 h-9 bg-brand rounded-lg flex items-center justify-center font-bold text-white text-sm">M2</div>
                    <span class="text-lg font-bold tracking-tight">M2Way Travel</span>
                </div>
                <div class="w-9 h-9 bg-brand rounded-lg flex items-center justify-center font-bold text-white text-sm" x-show="!sidebarOpen">M2</div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-4 px-3 overflow-y-auto space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen">{{ __('app.dashboard') }}</span>
                </a>

                <!-- Planning group -->
                <div x-data="{ open: localStorage.getItem('grp_planning') === '1' || (localStorage.getItem('grp_planning') === null && {{ request()->routeIs('plan-schedules.*') || request()->routeIs('planning.timeline') || request()->routeIs('planning.calendar') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_planning', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_planning') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('plan-schedules.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('plan-schedules.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.schedule') }}</span>
                        </a>
                        <a href="{{ route('planning.timeline') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('planning.timeline') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.timeline') }}</span>
                        </a>
                        <a href="{{ route('planning.calendar') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('planning.calendar') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.calendar') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Operations group -->
                <div x-data="{ open: localStorage.getItem('grp_operations') === '1' || (localStorage.getItem('grp_operations') === null && {{ request()->routeIs('transactions.*') || request()->routeIs('jamaah-groups.*') || request()->routeIs('customers.*') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_operations', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_operations') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('transactions.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('transactions.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.transactions') }}</span>
                        </a>
                        <a href="{{ route('jamaah-groups.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('jamaah-groups.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.jamaah_groups') }}</span>
                        </a>
                        <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('customers.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.customers') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Catalog group -->
                <div x-data="{ open: localStorage.getItem('grp_catalog') === '1' || (localStorage.getItem('grp_catalog') === null && {{ request()->routeIs('packages.*') || request()->routeIs('package-categories.*') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_catalog', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_catalog') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('packages.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('packages.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.packages') }}</span>
                        </a>
                        <a href="{{ route('package-categories.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('package-categories.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.package_categories') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Finance group -->
                <div x-data="{ open: localStorage.getItem('grp_finance') === '1' || (localStorage.getItem('grp_finance') === null && {{ request()->routeIs('finance.*') || request()->routeIs('accounting.*') || request()->routeIs('accounting-categories.*') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_finance', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_finance') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('finance.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('finance.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.finance') }}</span>
                        </a>
                        <a href="{{ route('accounting.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('accounting.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.accounting') }}</span>
                        </a>
                        <a href="{{ route('accounting-categories.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('accounting-categories.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.accounting_categories') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Documents group -->
                <div x-data="{ open: localStorage.getItem('grp_documents') === '1' || (localStorage.getItem('grp_documents') === null && {{ request()->routeIs('travel-documents.*') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_documents', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_documents') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('travel-documents.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('travel-documents.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.travel_documents') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Administration group -->
                <div x-data="{ open: localStorage.getItem('grp_administration') === '1' || (localStorage.getItem('grp_administration') === null && {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('activity-logs.*') || request()->routeIs('user-guide.*') ? 'true' : 'false' }}) }">
                    <button @click="open = !open; localStorage.setItem('grp_administration', open ? '1' : '0')"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen">{{ __('app.sidebar_group_administration') }}</span>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="ml-4 space-y-1 border-l border-white/10 pl-2">
                        <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('users.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.users') }}</span>
                        </a>
                        <a href="{{ route('roles.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('roles.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.roles') }}</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('activity-logs.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.activity_logs') }}</span>
                        </a>
                        <a href="{{ route('user-guide.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('user-guide.*') ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                            <span>{{ __('app.user_guide') }}</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-white/10 p-3">
                <div class="flex items-center space-x-3 px-2 py-2" x-show="sidebarOpen">
                    <div class="w-8 h-8 rounded-full bg-brand/20 flex items-center justify-center text-brand text-sm font-semibold">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? 'admin@m2way.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 flex-shrink-0">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('l, d M Y') }}</span>
                    <div class="flex items-center space-x-1 border border-gray-200 rounded-lg p-0.5">
                        <a href="{{ url()->current() }}?lang=en" class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ App::getLocale() === 'en' ? 'bg-brand text-white' : 'text-gray-500 hover:bg-gray-100' }}">EN</a>
                        <a href="{{ url()->current() }}?lang=id" class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ App::getLocale() === 'id' ? 'bg-brand text-white' : 'text-gray-500 hover:bg-gray-100' }}">ID</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-brand transition-colors font-medium">{{ __('app.logout') }}</button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                         class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                        <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                         class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ session('error') }}
                        <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script>
        function formatCurrencyInput(el) {
            let val = el.value.replace(/\D/g, '');
            el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.currency-input').forEach(el => {
                el.addEventListener('input', () => formatCurrencyInput(el));
                el.addEventListener('blur', () => formatCurrencyInput(el));
                if (el.value) formatCurrencyInput(el);
            });
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    form.querySelectorAll('.currency-input').forEach(el => {
                        el.value = el.value.replace(/\D/g, '');
                    });
                });
            });
        });
    </script>
</body>
</html>
