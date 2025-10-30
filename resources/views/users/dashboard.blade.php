@can('user-access')
@extends('layouts.Users.app')

@section('content')

<!-- Hero Section (homepage style) -->
<section class="hero relative text-center text-white" style="min-height:60vh; padding:6rem 10%; background: linear-gradient(rgba(128,0,0,0.45), rgba(128,0,0,0.45)), url('{{ asset("images/building.jpg") }}') center/cover no-repeat; background-blend-mode: overlay;">
    <div class="max-w-3xl mx-auto">
   <h1 style="font-size:70px; font-weight:900; color:#FFD700; text-shadow:2px 2px 8px rgba(128,0,0,0.8);" class="mb-4">
  Welcome back, {{ Auth::user()->first_name }}
</h1>




    <p class="hero-subtitle text-2xl font-semibold mb-6 text-white">
  One Stop Shop for your Campus Needs
  <span class="typed-wrapper">
      <span id="typed-text" class="typed-text"></span>
  </span>
</p>

        <a href="{{ route('user.products.index') }}" class="inline-block bg-yellow-400 text-gray-900 font-semibold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-transform hover:scale-105">🛒 Shop Now</a>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-red-900 mb-8">Best Selling Products</h2>
        @php
            // avoid referencing undefined variables
            $products = collect(isset($bestSelling) ? $bestSelling : (isset($trending) ? $trending : (isset($produktomo) ? $produktomo : [])));

            // compute top 3 best-selling products via OrderItem (sum of quantity)
            $topOrders = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
                        ->groupBy('product_id')
                        ->orderByDesc('total_sold')
                        ->take(3)
                        ->get();

            $topProducts = $topOrders->map(function($o) {
                return \App\Models\Product::find($o->product_id);
            })->filter()->values();

            $topIds = $topProducts->pluck('id')->toArray();

            // prepare products for grid (exclude top products to avoid duplicates)
            $gridProducts = $products->filter(function($p) use ($topIds) {
                return isset($p->id) ? !in_array($p->id, $topIds) : true;
            })->values();
        @endphp

        @if($topProducts->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-lg font-bold text-center text-red-800 mb-3">Top 3 Best Sellers</h3>
                <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @php $labels = ['1', '2', '3']; @endphp
                    @foreach($topProducts as $index => $tp)
                        <div class="bg-white rounded-lg p-3 shadow-sm flex flex-col items-center text-center">
                            <div class="text-sm font-semibold text-yellow-500">{{ $labels[$index] ?? ($index+1) }}</div>
                            <img src="{{ asset('images/'.$tp->image) }}" alt="{{ $tp->name }}" class="w-24 h-24 object-cover rounded my-2">
                            <div class="font-semibold text-sm">{{ $tp->name }}</div>
                            <div class="text-red-700 font-bold">₱{{ number_format($tp->price,2) }}</div>
                            <a href="{{ route('user.products.show', $tp->id) }}" class="mt-2 text-sm text-yellow-600">View</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($gridProducts->take(4) as $prod)
                <div class="bg-white rounded-lg overflow-hidden shadow-sm border">
                    <img src="{{ asset('images/'.$prod->image) }}" alt="{{ $prod->name }}" class="w-full h-44 object-cover">
                    <div class="p-4 text-left">
                        <h3 class="font-semibold text-lg">{{ $prod->name }}</h3>
                        <div class="price text-red-700 font-bold mt-1">₱{{ number_format($prod->price, 2) }}</div>
                        <a href="{{ route('user.products.show', $prod->id) }}" class="inline-block mt-3 text-sm text-yellow-600 font-semibold">View</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Typed.js Animation (keep on dashboard) -->
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          const typed = new Typed("#typed-text", {
            strings: ["Products", "Services", "Needs."],
            typeSpeed: 40,
            backSpeed: 30,
            backDelay: 1000,
            startDelay: 200,
            loop: true,
            showCursor: true,
            cursorChar: "|"
          });
        });
    </script>
@endpush

@endsection
@endcan
