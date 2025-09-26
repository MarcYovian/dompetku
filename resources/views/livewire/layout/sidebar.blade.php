{{-- resources/views/livewire/layout/sidebar.blade.php (Tidak banyak berubah, hanya memastikan kontennya) --}}

<div class="flex flex-col h-full bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700">
    {{-- App Logo --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-2">
            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Dompetku</span>
        </a>

        <button @click="sidebarOpen = false"
            class="text-gray-500 lg:hidden hover:text-gray-600 focus:outline-none focus:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-4 py-4 space-y-2">
        <x-sidebar-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            {{ __('Dashboard') }}
        </x-sidebar-nav-link>
        <x-sidebar-nav-link :href="route('fund-sources')" :active="request()->routeIs('fund-sources')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            {{ __('Fund Sources') }}
        </x-sidebar-nav-link>
        <x-sidebar-nav-link :href="route('transfer')" :active="request()->routeIs('transfer')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            {{ __('Transfer') }}
        </x-sidebar-nav-link>
        <x-sidebar-nav-link :href="route('categories')" :active="request()->routeIs('categories')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                </path>
            </svg>
            {{ __('Categories') }}
        </x-sidebar-nav-link>
        <x-sidebar-nav-link :href="route('transactions')" :active="request()->routeIs('transactions')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 15h.01">
                </path>
            </svg>
            {{ __('Transactions') }}
        </x-sidebar-nav-link>
        <x-sidebar-nav-link :href="route('reports')" :active="request()->routeIs('reports')" wire:navigate>
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 2v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            {{ __('Reports') }}
        </x-sidebar-nav-link>
    </nav>
</div>
