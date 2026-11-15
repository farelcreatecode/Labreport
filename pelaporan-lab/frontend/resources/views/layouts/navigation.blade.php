<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="font-bold text-xl text-indigo-600 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>LabReport</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('admin.complaints.index')" :active="request()->routeIs('admin.complaints.*')">
                        Kelola Laporan
                    </x-nav-link>

                    <!-- Dropdown Master Data -->
                    <div class="inline-flex items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.facilities.*') || request()->routeIs('admin.labs.*') || request()->routeIs('admin.technicians.*') || request()->routeIs('admin.statistics.*') ? 'border-indigo-500 text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    <span>Data Master</span>
                                    <svg class="ms-1.5 h-4 w-4 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Master Data</div>
                                <x-dropdown-link :href="route('admin.labs.index')">
                                    Data Laboratorium
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.facilities.index')">
                                    Data Fasilitas & Unit
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.technicians.index')">
                                    Data Teknisi Lapangan
                                </x-dropdown-link>
                                <div class="border-t border-gray-100 my-1"></div>
                                <x-dropdown-link :href="route('admin.statistics.index')">
                                    Statistik Lengkap
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <a href="{{ route('pelapor.create') }}" target="_blank" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-indigo-600 transition">
                        Portal Publik &nearr;
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <span class="me-3 px-2.5 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-800 uppercase">
                    {{ session('admin_user.role', 'ADMIN') }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ session('admin_user.name', 'Administrator') }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-400">
                            {{ session('admin_user.email', 'admin@labreport.com') }}
                        </div>
                        <div class="border-t border-gray-100"></div>

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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.complaints.index')" :active="request()->routeIs('admin.complaints.*')">
                Kelola Laporan
            </x-responsive-nav-link>
            <div class="pt-2 pb-1 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Data Master</div>
            <x-responsive-nav-link :href="route('admin.labs.index')" :active="request()->routeIs('admin.labs.*')">
                Data Laboratorium
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.facilities.index')" :active="request()->routeIs('admin.facilities.*')">
                Data Fasilitas & Unit
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.technicians.index')" :active="request()->routeIs('admin.technicians.*')">
                Data Teknisi Lapangan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.statistics.index')" :active="request()->routeIs('admin.statistics.*')">
                Statistik Lengkap
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ session('admin_user.name', 'Administrator') }}</div>
                <div class="font-medium text-sm text-gray-500">{{ session('admin_user.email', 'admin@labreport.com') }}</div>
            </div>

            <div class="mt-3 space-y-1">
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
