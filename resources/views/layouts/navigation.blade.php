<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('order') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('order')" :active="request()->routeIs('order')">
                        {{ __('Order') }}
                    </x-nav-link>
                    <x-nav-link :href="route('deals')" :active="request()->routeIs('deals')">
                        {{ __('Deals') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cart')" :active="request()->routeIs('cart')">
                        {{ __('Cart') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
                
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" >
                         @csrf

                        <x-nav-link class="h-full" :href="route('logout')" onclick="event.preventDefault();
                            this.closest('form').submit();">
                            {{ __('Log out') }}
                        </x-nav-link>
                    </form>
                        
            @endauth
            @guest
                <x-nav-link :href="route('login')">
                    {{ __('Log in') }}
                </x-nav-link>

            @endguest

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('order')" :active="request()->routeIs('order')">
                {{ __('Order') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('deals')" :active="request()->routeIs('deals')">
                {{ __('Deals') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart')" :active="request()->routeIs('cart')">
                {{ __('Cart') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="ml-3">
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                </div>

                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
