<p class="mt-4 text-amber-700">
    @auth
        Welcome to your dashboard, {{ Auth::user()->name }}!
    @else
        Welcome to your dashboard, Guest!
    @endauth
</p>

