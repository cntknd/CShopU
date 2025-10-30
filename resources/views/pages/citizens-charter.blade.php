@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  body {
    background-color: #f9f9f9;
  }
  .navbar {
    background-color: #a00d0d !important;
  }
  .navbar-brand {
    color: #fff !important;
    font-weight: bold;
    letter-spacing: 0.5px;
  }
  .charter-header {
    background-color: #a00d0d;
    color: white;
    text-align: center;
    padding: 50px 20px;
  }
  .charter-header h1 {
    font-size: 2.5rem;
    font-weight: bold;
  }
  .charter-section {
    padding: 40px 0;
  }
  .charter-image {
    width: 100%;
    height: auto;
    display: block;
    margin: 20px auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
</style>

<!-- Minimal Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      <i class="bi bi-shop"></i> CShopU
    </a>
  </div>
</nav>

<!-- Citizens Charter Header -->
<section class="charter-header">
  <h1>Citizen’s Charter</h1>
  <p class="lead mb-0">Cagayan State University - Aparri Campus</p>
</section>

<!-- Charter Images Section -->
<div class="container charter-section">
  <img src="{{ asset('images/RM - CC (1)-1.jpg') }}" alt="CSU Charter Page 1" class="charter-image">
  <img src="{{ asset('images/RM - CC (1)-2.jpg') }}" alt="CSU Charter Page 2" class="charter-image">
   <img src="{{ asset('images/RM - CC (1)-3.jpg') }}" alt="CSU Charter Page 2" class="charter-image">
    <img src="{{ asset('images/RM - CC (1)-4.jpg') }}" alt="CSU Charter Page 2" class="charter-image">
     <img src="{{ asset('images/RM - CC (1)-5.jpg') }}" alt="CSU Charter Page 2" class="charter-image">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
