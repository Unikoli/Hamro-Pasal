<nav x-data="{ open: false }" class="bg-gradient-to-r from-steel-900 to-steel-800 border-b border-metallic-dark shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="bg-gradient-to-br from-metallic-light via-metallic-mid to-metallic-dark rounded-lg p-2 shadow-metallic-btn group-hover:scale-105 transition-transform duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold metallic-text font-orbitron tracking-tight">
                            BAZAAR BUDDY
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-metallic-light hover:text-metallic-gold hover:border-metallic-gold/50' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        DASHBOARD
                    </a>
                    
                    @can('manage products')
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('products.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-metallic-light hover:text-metallic-gold hover:border-metallic-gold/50' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            PRODUCTS
                        </a>
                    @endcan
                    
                    @can('manage sales')
                        <a href="{{ route('sales.create') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('sales.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-metallic-light hover:text-metallic-gold hover:border-metallic-gold/50' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            SALES
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-metallic-light bg-steel-800/50 hover:text-metallic-gold focus:outline-none transition-colors duration-200">
                        <div class="mr-2">{{ Auth::user()->name }}</div>
                        <svg class="fill-current h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-steel-800 border border-steel-700 z-50">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-metallic-light hover:bg-steel-700 hover:text-metallic-gold transition-colors duration-200">
                                Profile
                            </a>
                            
                            @can('manage users')
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-metallic-light hover:bg-steel-700 hover:text-metallic-gold transition-colors duration-200">
                                    Users
                                </a>
                            @endcan
                            
                            @can('manage categories')
                                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-metallic-light hover:bg-steel-700 hover:text-metallic-gold transition-colors duration-200">
                                    Categories
                                </a>
                            @endcan
                            
                            @can('manage suppliers')
                                <a href="{{ route('admin.suppliers.index') }}" class="block px-4 py-2 text-sm text-metallic-light hover:bg-steel-700 hover:text-metallic-gold transition-colors duration-200">
                                    Suppliers
                                </a>
                            @endcan
                            
                            @can('manage settings')
                                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-metallic-light hover:bg-steel-700 hover:text-metallic-gold transition-colors duration-200">
                                    Settings
                                </a>
                            @endcan
                            
                            <div class="border-t border-steel-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-steel-700 hover:text-red-300 transition-colors duration-200">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-metallic-light hover:text-metallic-gold hover:bg-steel-700 focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-steel-800 border-t border-steel-700">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'border-metallic-gold text-metallic-gold bg-steel-700' : 'border-transparent text-metallic-light hover:text-metallic-gold hover:bg-steel-700 hover:border-metallic-gold' }}">
                Dashboard
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-steel-700">
            <div class="px-4">
                <div class="font-medium text-base text-metallic-light">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-metallic-mid">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-metallic-light hover:text-metallic-gold hover:bg-steel-700 transition-colors duration-200">
                    Profile
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-400 hover:text-red-300 hover:bg-steel-700 transition-colors duration-200">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
