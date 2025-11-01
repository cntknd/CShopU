@can('user-access')
@extends('layouts.Users.app')

@section('content')

<style>
    /* Minimal responsive size adjustments only - preserving your design */
    .hero {
        padding: 6rem 10% !important;
        min-height: 60vh !important;
        background-blend-mode: overlay;
        /* Maroon glow: inset tint to darken image subtly + external maroon drop shadow */
        box-shadow: inset 0 0 80px rgba(128,0,0,0.18), 0 24px 80px rgba(128,0,0,0.28);
        position: relative; /* enable pseudo-element overlay */
        overflow: hidden;
    }

    /* Maroon overlay directly over the background image (below content) */
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(128,0,0,0.22);
        mix-blend-mode: multiply;
        pointer-events: none;
        z-index: 0;
    }

    /* ensure hero content sits above overlay */
    .hero-inner { position: relative; z-index: 2; }

    /* make typed words clearly visible beside the main subtitle */
    .typed-subtitle { position: relative; z-index: 3; color: #FFD700; text-shadow: 1px 1px 6px rgba(0,0,0,0.6); font-weight:700; }
    .hero-subtitle { position: relative; z-index: 3; }
    .hero-title {
        font-size: 70px;
        font-weight: 900;
        color: #FFD700;
        text-shadow: 2px 2px 8px rgba(128,0,0,0.8);
        line-height: 1;
    }
    /* main subtitle kept static (no entrance animation) */
    /* removed fadeInUp animation per design request */
    @media (max-width: 1024px) {
        .hero { padding: 4rem 6% !important; }
        .hero-title { font-size: 48px; }
    }
    @media (max-width: 640px) {
        .hero { padding: 2.5rem 4% !important; }
        .hero-title { font-size: 28px; }
    }

    /* Top product image minor responsive tweak */
    .top-prod-img { width: 6rem; height: 6rem; object-fit: cover; }
    @media (max-width:640px) { .top-prod-img { width: 5rem; height: 5rem; } }

    /* Stronger shop now shadow */
    .shopnow-shadow {
        box-shadow: 0 12px 36px rgba(0,0,0,0.45);
    }
    .shopnow-shadow:hover {
        box-shadow: 0 18px 48px rgba(0,0,0,0.55);
    }
</style>

<!-- Hero Section (homepage style) - single background image -->
<section class="hero relative text-center text-white bg-cover bg-center" style="background-image: url('{{ asset("images/building.jpg") }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="hero-inner max-w-4xl mx-auto px-4 py-20 sm:py-28 lg:py-36">
        <h1 class="hero-title mb-4 text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-extrabold text-yellow-400 leading-tight drop-shadow-lg">
            Welcome back, <span class="block sm:inline">{{ Auth::user()->first_name }}</span>
        </h1>

            <div class="subtitle-group max-w-2xl mx-auto mb-6 flex flex-col sm:flex-row items-center justify-center gap-4">
                <p class="hero-subtitle text-base sm:text-lg md:text-2xl font-semibold text-white m-0">
                    One Stop Shop for your Campus
                </p>

                <p class="typed-subtitle text-lg sm:text-2xl md:text-3xl font-extrabold text-yellow-300 m-0">
                    <span id="typed-text" class="typed-text"></span>
                </p>
            </div>

        <a href="{{ route('user.products.index') }}" class="inline-block bg-red-900 text-white font-semibold py-2 px-6 sm:py-3 sm:px-6 rounded-full shopnow-shadow transition-transform hover:scale-105 hover:bg-red-700 text-sm sm:text-base">
            Shop Now
        </a>
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
            <div class="mb-8">
                <h3 class="text-xl font-bold text-center text-red-800 mb-4">Top 3 Best Sellers</h3>
                <div class="max-w-5xl mx-auto px-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @php $labels = ['1', '2', '3']; @endphp
                        @foreach($topProducts as $index => $tp)
                            <div class="bg-white rounded-lg p-4 shadow-sm flex flex-col items-center text-center">
                                <div class="text-sm font-semibold text-yellow-500 mb-2">{{ $labels[$index] ?? ($index+1) }}</div>
                                <img src="{{ asset('images/'.$tp->image) }}" alt="{{ $tp->name }}" class="w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover rounded mb-3 top-prod-img">
                                <div class="font-semibold text-sm truncate w-40">{{ $tp->name }}</div>
                                <div class="text-red-700 font-bold mt-1">₱{{ number_format($tp->price,2) }}</div>
                                <a href="{{ route('user.products.show', $tp->id) }}" class="mt-3 text-sm text-yellow-600 font-semibold">View Details</a>
                            </div>
                        @endforeach
                    </div>
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
                        <a href="{{ route('user.products.show', $prod->id) }}" class="inline-block mt-3 text-sm text-yellow-600 font-semibold">View Details</a>
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
                            strings: ["Products", "Services", "Needs"],
                            typeSpeed: 60,
                            backSpeed: 40,
                            backDelay: 900,
                            startDelay: 300,
                            loop: true,
                            showCursor: true,
                            cursorChar: "|"
                        });
        });
    </script>
@endpush

@endsection
@endcan
