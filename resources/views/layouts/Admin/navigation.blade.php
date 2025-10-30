<nav x-data="{ open: false }" class="bg-red-900 border-b border-red-800 shadow-lg">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
<div class="flex items-center">
    <!-- Logo -->
    <div class="flex-shrink-0 flex items-center mr-8">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-xl font-bold">
            🏪 CShopU Admin
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            📊 {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('admin.manageproducts.index')" :active="request()->routeIs('admin.manageproducts.*')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            📦 {{ __('Products') }}
        </x-nav-link>

        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            🏷️ {{ __('Categories') }}
        </x-nav-link>

        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 relative">
            📋 {{ __('Orders') }}
            <span id="navbar-order-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;"></span>
        </x-nav-link>

        <x-nav-link :href="route('admin.sales.overview')" :active="request()->routeIs('admin.sales.*')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            💰 {{ __('Sales') }}
        </x-nav-link>

        <x-nav-link :href="route('admin.feedbacks.index')" :active="request()->routeIs('admin.feedbacks.*')" class="text-white hover:text-red-300 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            💬 {{ __('Feedbacks') }}
        </x-nav-link>
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

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>

                    <x-dropdown-link>
                        logged in as {{ Auth::user()->roles[0]->name}}
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
<!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                class="text-white hover:bg-red-800"

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

<!-- Order Badge Update Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const navbarBadge = document.getElementById("navbar-order-badge");
    const mobileBadge = document.getElementById("mobile-order-badge");

    function updateOrderBadges() {
        fetch("{{ route('admin.orders.count') }}")
            .then(res => res.json())
            .then(data => {
                if (data.count > 0) {
                    if (navbarBadge) {
                        navbarBadge.textContent = data.count;
                        navbarBadge.style.display = "flex";
                    }
                    if (mobileBadge) {
                        mobileBadge.textContent = data.count;
                        mobileBadge.style.display = "flex";
                    }
                } else {
                    if (navbarBadge) navbarBadge.style.display = "none";
                    if (mobileBadge) mobileBadge.style.display = "none";
                }
            })
            .catch(err => console.error("Error fetching order count:", err));
    }

    // Initial load + refresh every 10 seconds
    updateOrderBadges();
    setInterval(updateOrderBadges, 10000);
});
</script>

</nav>