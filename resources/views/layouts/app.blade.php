<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CshopU Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<body class="bg-gray-100 text-gray-900">
<nav style="background-color: maroon; color: white; padding: 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="text-sm flex items-center space-x-4">
        @auth
            <a href="{{ route('dashboard') }}" class="hover:underline text-white/90">Dashboard</a>
            <span>Hello, <span class="font-semibold">{{ Auth::user()->name }}</span></span> |
            
            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:underline text-white/90">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="hover:underline text-white/90">Login</a> |
            <a href="{{ route('register') }}" class="hover:underline text-white/90">Register</a>
            <a href="{{ route('home') }}" class="hover:underline text-white/90">Home</a>
   
        @endauth

       
  
  
    </div>
</nav>



<main class="p-6">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
