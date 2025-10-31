<nav x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{'bg-red-900/95 shadow-lg': scrolled, 'bg-red-900': !scrolled }"
    class="sticky top-0 w-full left-0 z-50 border-b border-red-800 transition-all duration-300">
    
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
    <!-- Logo Section (Left) -->
    <div class="flex items-center">
        <div class="flex items-center flex-shrink-0 gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="CShopU Logo" class="h-8 w-auto">
            <span class="text-white font-bold text-lg">CShopU</span>
        </div>
    </div>

    <!-- Navigation Links (Right) -->
    <div class="hidden sm:flex sm:items-center" style="gap: 2rem">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-yellow-400 font-bold">
            <i class="bi bi-house-door mr-1"></i>{{ __('Home') }}
        </x-nav-link>

                <x-nav-link :href="route('user.products.index')" :active="request()->routeIs('user.products.index')" class="text-white hover:text-yellow-400 font-semibold">
                    <i class="bi bi-shop mr-1"></i>{{ __('Products') }}
                </x-nav-link>

                <x-nav-link :href="route('users.feedback.create')" :active="request()->routeIs('users.feedback.create')" class="text-white hover:text-yellow-400 font-semibold">
                    <i class="bi bi-chat-dots mr-1"></i>{{ __('Feedback') }}
                </x-nav-link>

                <!-- Cart Icon -->
                <div class="inline-flex">
                    <a href="{{ route('user.cart.view') }}" class="text-white hover:text-yellow-400">
                        <i class="bi bi-cart3 text-2xl"></i>
                    </a>
                </div>
            </div>

        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-900 hover:text-red-300 focus:outline-none transition ease-in-out duration-150">
                        <div>
                            {{ Auth::user()->first_name }}
                            @if(Auth::user()->middle_initial)
                                {{ Auth::user()->middle_initial }}.
                            @endif
                            {{ Auth::user()->last_name }}
                        </div>

                        <div class="ms-1">
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

                    <x-dropdown-link :href="route('orders.index')" class="flex items-center">
                        <i class="bi bi-bag mr-1"></i>{{ __('Orders') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>

                    <x-dropdown-link>
                        logged in as {{ Auth::user()->roles->first() ? Auth::user()->roles->first()->name : 'User' }}
                    </x-dropdown-link>

                </x-slot>
            </x-dropdown>
        </div>

        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-red-200 hover:text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 focus:text-white transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>


<!-- Responsive Navigation Menu -->
<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-red-800">
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-red-700 font-bold">
            <i class="bi bi-house-door mr-1"></i>{{ __('Home') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('user.products.index')" :active="request()->routeIs('user.products.index')" class="text-white hover:bg-red-700 font-bold">
            <i class="bi bi-shop mr-1"></i>{{ __('Products') }}
        </x-responsive-nav-link>

        {{-- Orders link for mobile menu (stacked under Products) --}}
        <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-white hover:bg-red-700 font-bold">
            <i class="bi bi-bag mr-1"></i>{{ __('Orders') }}
        </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('users.feedback.create')" :active="request()->routeIs('users.feedback.create')" class="text-white hover:bg-red-700 font-bold">

            <i class="bi bi-chat-dots mr-1"></i>{{ __('Feedback') }}

        </x-responsive-nav-link>



        <x-responsive-nav-link :href="route('user.cart.view')" :active="request()->routeIs('user.cart.view')" class="text-white hover:bg-red-700 font-bold">
            <div class="inline-flex items-center">
                <i class="bi bi-cart3 mr-1"></i>{{ __('Cart') }}
            </div>
        </x-responsive-nav-link>
    </div>

    <div class="pt-4 pb-1 border-t border-red-800">
        <div class="px-4">
            <div class="font-medium text-base text-white">
                {{ Auth::user()->first_name }}
                @if(Auth::user()->middle_initial)
                    {{ Auth::user()->middle_initial }}.
                @endif
                {{ Auth::user()->last_name }}
            </div>
            <div class="font-medium text-sm text-red-200">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-red-800">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();" class="text-white hover:bg-red-800">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>


</nav>
