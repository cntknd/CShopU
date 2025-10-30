@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 90vh; background-color: #ffffff;">
  <div class="text-center p-4 rounded shadow" style="max-width: 700px; width: 100%; color: #000;">
    <img src="{{ asset('images/logo.png') }}" alt="CSU Logo" style="height: 80px;" class="mb-4">

    <h1 class="mb-4" style="color: #800000;">PAYMENT</h1>

    <p style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">
      ACCEPTING PAYMENTS VIA:
    </p>

    <ol class="text-start" style="max-width: 400px; margin: 0 auto; font-size: 1.1rem;">
                <li><strong>CASHIER'S OFFICE</strong></li>
</ol>

  </div>
</div>
@endsection
