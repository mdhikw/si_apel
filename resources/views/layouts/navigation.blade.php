<nav x-data="{ open: false }" class="bg-gradient-to-r from-white via-gray-50 to-white dark:from-gray-800 dark:via-gray-850 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                            <x-application-logo class="relative block h-8 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent hidden sm:inline-block">POLRES</span>
                    </a>
                </div>

                <!-- Navigation Links (DESKTOP) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <!-- 1. DASHBOARD (Semua Orang Bisa Lihat) -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- 2. MENU KHUSUS ADMIN (Dicek pakai IF) -->
                    @if(Auth::user()->role == 'admin')
                        <x-nav-link :href="route('offices.index')" :active="request()->routeIs('offices.*')">
                            {{ __('Data Kantor') }}
                        </x-nav-link>

                        <x-nav-link :href="route('shifts.index')" :active="request()->routeIs('shifts.*')">
                            {{ __('Shift Kerja') }}
                        </x-nav-link>
                    @endif

                    <!-- 3. DATA ABSENSI (Semua Orang Bisa Lihat) -->
                    <!-- Admin lihat semua, User lihat punya sendiri (diatur di Controller) -->
                    <x-nav-link :href="route('attendances.history')" :active="request()->routeIs('attendances.*')">
                        {{ __('Data Absensi') }}
                    </x-nav-link>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-700 text-sm leading-4 font-semibold rounded-lg text-gray-700 dark:text-gray-300 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 hover:from-indigo-50 hover:to-indigo-100 dark:hover:from-indigo-900 dark:hover:to-purple-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <!-- Tampilkan Nama User & Role -->
                            <div class="flex items-center space-x-2">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (TOMBOL MENU HP) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (TAMPILAN HP) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            
            <!-- 1. Dashboard HP -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- 2. Menu Admin HP (Juga disembunyikan pakai IF) -->
            @if(Auth::user()->role == 'admin')
                <x-responsive-nav-link :href="route('offices.index')" :active="request()->routeIs('offices.*')">
                    {{ __('Data Kantor') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('shifts.index')" :active="request()->routeIs('shifts.*')">
                    {{ __('Shift Kerja') }}
                </x-responsive-nav-link>
            @endif

            <!-- 3. Absensi HP -->
            <x-responsive-nav-link :href="route('attendances.history')" :active="request()->routeIs('attendances.*')">
                {{ __('Data Absensi') }}
            </x-responsive-nav-link>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>