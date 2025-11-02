<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CShopU.com</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding-top: 70px;
      overflow-x: hidden;
    }

    /* NAVBAR */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 0.8rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: background-color 0.5s ease, box-shadow 0.5s ease;
      z-index: 1000;
    }

    nav.transparent {
      background-color: rgba(128,0,0,0.2);
      box-shadow: none;
    }

    nav.solid {
      background-color: #800000;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    nav .logo-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    nav .logo-container img {
      height: 36px;
    }

    nav .logo-container span {
      font-weight: 700;
      font-size: 1.2rem;
      color: #fff;
    }

    nav .nav-links a {
      color: #fff;
      text-decoration: none;
      margin-left: 2rem;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    /* Nav is hidden by default and opened via hamburger overlay */
    .nav-links {
      display: none; /* hidden by default; opened via hamburger */
    }

    /* Fullscreen overlay menu (used for all viewports when hamburger is clicked) */
    .nav-links.overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1200;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .nav-links.overlay .menu-inner {
      background: #800000;
      width: 90%;
      max-width: 420px;
      padding: 1.25rem 1rem;
      border-radius: 12px;
      color: #fff;
      box-shadow: 0 20px 50px rgba(0,0,0,0.4);
      text-align: center;
    }

    .nav-links.overlay .menu-inner a {
      display: block;
      color: #fff;
      font-weight: 700;
      padding: 0.85rem 0;
      text-decoration: none;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .nav-links.overlay .menu-inner a.cta-btn {
      margin-top: 0.75rem;
      display: inline-block;
      background: #b30000;
      border-radius: 9999px;
      padding: 0.6rem 1.25rem;
    }

    .nav-links.show { display: block; }

    nav .nav-links a:hover {
      color: #facc15;
    }

    nav .cta-btn {
      margin-left: 2rem;
      background-color: #b30000;
      color: #fff;
      padding: 0.5rem 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    nav .cta-btn:hover {
      background-color: #8b0000;
    }

    .hamburger {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0.5rem;
    }

    .hamburger:focus {
      outline: none;
    }

    /* HERO SECTION */
    .hero {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      min-height: 85vh;
      padding: 100px 10%;
      color: #ffffff;
      background:
        linear-gradient(
          rgba(128, 0, 0, 0.4),
          rgba(128, 0, 0, 0.4)
        ),
        url('{{ asset("images/csu.png") }}') center/cover no-repeat;
      background-blend-mode: overlay;
      filter: brightness(0.9);
    }

    .hero-title {
      font-size: 4rem;
      font-weight: 800;
      margin-bottom: 1rem;
    }

    .hero-title span {
      color: #facc15;
    }

    .hero-subtitle {
      font-size: 1.8rem;
      font-weight: 600;
      line-height: 1.2;
      margin-bottom: 2rem;
      color: #ffffff;
      transition: color 0.5s ease;
    }

    /* FOOTER */
    footer {
      width: 100vw;
      margin: 0;
      padding: 0;
      position: relative;
      left: 50%;
      right: 50%;
      margin-left: -50vw;
      margin-right: -50vw;
      background: #ffffff;
      border-top: 1px solid #e5e7eb;
    }

    /* Make the inner footer div span the full viewport width if requested */
    .footer-content {
      width: 100vw;
      box-sizing: border-box;
      padding: 2.5rem 1rem;
      text-align: center;
    }

    .footer-links {
      display: inline-flex;
      gap: 0.75rem;
      align-items: center;
      justify-content: center;
      margin-top: 0.5rem;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: #374151;
      text-decoration: none;
      font-weight: 600;
      box-shadow: none !important; /* remove any shadow style */
      background: transparent !important;
      padding: 0.25rem 0.5rem;
      transition: color 0.2s ease, background 0.15s ease;
    }

    .link-separator {
      color: #6b7280;
      font-weight: 600;
    }

    .typed-wrapper {
      display: inline-block;
      white-space: nowrap;
      margin-left: 0.3rem;
    }

    .typed-text {
      font-weight: 700;
      display: inline-block;
      white-space: nowrap;
      color: inherit;
    }

    .btn-shop {
      background-color: #b30000;
      color: #fff;
      padding: 0.8rem 2rem;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .btn-shop:hover {
      background-color: #8b0000;
      transform: scale(1.05);
    }

    /* FEATURED PRODUCTS */
    .section {
      padding: 6rem 0;
      background: linear-gradient(180deg, #fff 0%, #f8f8f8 100%);
      border-radius: 60px 60px 0 0;
      text-align: center;
    }

    .section h2 {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 3rem;
      color: #800000;
    }

    .product-card {
      border-radius: 16px;
      background: #fff;
      overflow: hidden;
      transition: 0.3s ease;
      border: 1px solid #eee;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 1px solid #f1f1f1;
    }

    .product-card h3 {
      font-size: 1rem;
      font-weight: 600;
      color: #333;
      margin-top: 0.5rem;
    }

    .price {
      color: #b30000;
      font-weight: 700;
      margin-top: 0.25rem;
    }

    /* FOOTER */
    footer {
      background: #f5f5f5;
      padding: 2rem 1rem;
      text-align: center;
      font-size: 0.95rem;
      color: #2d2d2d;
      border-top: 1px solid #ddd;
      box-shadow: none;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
    }

    .university-text {
      margin-bottom: 1rem;
      line-height: 1.4;
    }

    footer .social {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin: 1.5rem 0;
    }

    footer .social i {
      font-size: 1.5rem;
      color: #2d2d2d;
      transition: 0.3s ease;
      padding: 0.5rem;
      border-radius: 50%;
      background: none;
      box-shadow: none;
      text-shadow: none;
    }

    footer .social i:hover {
      color: #800000;
      background: none;
      box-shadow: none;
      text-shadow: none;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin: 1rem 0;
    }

    .footer-links a {
      color: #2d2d2d;
      text-decoration: none;
      font-weight: 500;
      padding: 0.25rem 0.5rem;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: #800000;
    }

    .link-separator {
      color: #666;
      font-weight: 300;
    }

    .copyright {
      margin-top: 1.5rem !important;
      font-size: 0.85rem;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
      .hero {
        padding: 80px 5%;
      }
      /* FOOTER */
      footer {
        width: 100vw;
        margin: 0;
        padding: 0;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
      }

      .footer-content {
        width: 100%;
        padding: 3rem 1rem;
        text-align: center;
        max-width: 1280px;
        margin: 0 auto;
      }

      .footer-links {
        display: inline-flex;
        gap: 0.75rem;
        align-items: center;
        justify-content: center;
        margin-top: 0.5rem;
        flex-wrap: wrap;
      }

      .footer-links a {
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0;
        transition: color 0.2s ease;
      }

      .footer-links a:hover {
        color: #b30000;
      }
      }

      nav .nav-links.show {
        display: flex;
      }

      nav .nav-links a {
        margin: 0.5rem 2rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
      }

      nav .nav-links a:last-child {
        border-bottom: none;
      }

      .hamburger {
        display: block;
      }

      .product-card img {
        height: 180px;
      }

      .product-card h3 {
        font-size: 0.9rem;
      }

      .btn-shop {
        font-size: 0.9rem;
        padding: 0.7rem 1.5rem;
      }

      footer {
        padding: 2rem 1rem;
      }

      .university-text {
        font-size: 0.9rem;
        line-height: 1.5;
      }

      footer .social {
        gap: 1.5rem;
        margin: 2rem 0;
      }

      footer .social i {
        font-size: 1.8rem;
        padding: 0.75rem;
      }

      .footer-links {
        flex-direction: column;
        gap: 0.75rem;
        margin: 1.5rem 0;
      }

      .footer-links a {
        font-size: 0.95rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        background: rgba(128, 0, 0, 0.05);
        transition: all 0.3s ease;
      }

      .footer-links a:hover {
        background: rgba(128, 0, 0, 0.1);
        transform: translateY(-1px);
      }

      .link-separator {
        display: none;
      }

      .copyright {
        font-size: 0.8rem;
        margin-top: 2rem !important;
      }
    }

    /* Additional mobile/responsive adjustments */
    @media (max-width: 640px) {
      /* Show hamburger and hide default nav links */
      .hamburger {
        display: block;
      }

      .nav-links {
        display: none;
        position: absolute;
        top: 64px;
        left: 0;
        right: 0;
        background: rgba(128,0,0,0.98);
        padding: 0.75rem 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        z-index: 999;
      }

      .nav-links.show {
        display: block;
      }

      .nav-links a {
        display: block;
        margin: 0.5rem 0;
        padding: 0.5rem 0.25rem;
        color: #fff;
        font-weight: 600;
      }

      .nav-links .cta-btn {
        display: inline-block;
        margin-top: 0.5rem;
      }

      /* Reduce hero padding and font sizes for small screens */
      .hero {
        min-height: 60vh;
        padding: 60px 6%;
      }

      .hero-title {
        font-size: 2rem;
      }

      .hero-subtitle {
        font-size: 1rem;
      }

      /* Product cards: smaller images on mobile */
      .product-card img {
        height: 160px;
      }

      .section {
        padding: 3.5rem 0 2.5rem;
        border-radius: 28px 28px 0 0;
      }

      /* Footer adjustments already applied — ensure readable spacing */
      .footer-content {
        padding: 2rem 0.75rem;
      }
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav id="navbar" class="transparent">
    <div class="logo-container">
      <img src="{{ asset('images/logo.png') }}" alt="CSU Logo">
      <span>CShopU</span>
    </div>
    <button class="hamburger" id="hamburger" aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list"></i>
    </button>
    <div id="nav-links" class="nav-links overlay" aria-hidden="true">
      <div class="menu-inner" role="menu" aria-label="Main menu">
        <a href="#trending" role="menuitem">Home</a>
        <a href="#trending" role="menuitem">Products</a>
        <a href="#trending" role="menuitem">Services</a>
        <a href="#trending" role="menuitem">Contact</a>
        @auth
          <a href="{{ route('user.products.index') }}" class="cta-btn" role="menuitem">Shop Now</a>
        @else
          <a href="{{ route('login') }}" class="cta-btn" role="menuitem">Login/Register</a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="hero">
    <div>
      <h1 class="hero-title">Welcome to <span>CShopU</span></h1>
      <p class="hero-subtitle">
        One Stop Shop for your Campus
        <span class="typed-wrapper">
          <span id="typed-text" class="typed-text"></span>
        </span>
      </p>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-shop">Shop Now</a>
      @else
        <a href="{{ route('login') }}" class="btn-shop">Shop Now</a>
      @endauth
    </div>
  </section>

  <!-- FEATURED PRODUCTS -->
  <section class="section" id="trending">
    <div class="container">
      <h2>Featured Products</h2>
      <div class="row g-4">
        @foreach(($trending ?? $produktomo)->take(4) as $prod)
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card">
              <img src="{{ asset('images/'.$prod->image) }}" alt="{{ $prod->name }}">
              <div class="p-3 text-start">
                <h3>{{ $prod->name }}</h3>
                <div class="price">₱{{ number_format($prod->price, 2) }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- FOOTER -->     
  <footer>
    <div class="footer-content">
      <p class="university-text"><b class="text-maroon">CAGAYAN STATE UNIVERSITY</b> | Empowering Students Through Innovation</p>
      <div class="social my-3">
        <a href="https://www.facebook.com/CSUABAO" target="_blank"><i class="bi bi-facebook"></i></a>
        <a href="https://instagram.com/csukomyu/" target="_blank"><i class="bi bi-instagram"></i></a>
        <a href="mailto:csubao@gmail.com"><i class="bi bi-envelope"></i></a>
      </div>
      <div class="footer-links" role="navigation" aria-label="footer links">
        <a href="{{ route('citizens-charter') }}">Citizens Charter</a>
        <span class="link-separator" aria-hidden="true">|</span>
        <a href="{{ route('payment') }}">Payment</a>
        <span class="link-separator" aria-hidden="true">|</span>
        <a href="{{ route('contact-us') }}">Contact Us</a>
      </div>
      <p class="copyright mt-3 small text-muted">&copy; 2025 CShopU - CSU Aparri Campus</p>
    </div>
  </footer>

  <!-- Typed.js Animation -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const subtitle = document.querySelector(".hero-subtitle");
      const typed = new Typed("#typed-text", {
        strings: ["Products", "Services", "Needs."],
        typeSpeed: 40,
        backSpeed: 30,
        backDelay: 1000,
        startDelay: 200,
        loop: true,
        showCursor: true,
        cursorChar: "|",
        onStringTyped: function(pos, self) {
          if(self.strings[pos] === "Needs.") {
            subtitle.style.color = "#b30000";
          } else {
            subtitle.style.color = "#ffffff";
          }
        },
        onReset: function(self) {
          subtitle.style.color = "#ffffff";
        }
      });
    });

    // Scroll-activated navbar
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      if(window.scrollY > 50){
        navbar.classList.remove('transparent');
        navbar.classList.add('solid');
      } else {
        navbar.classList.remove('solid');
        navbar.classList.add('transparent');
      }
    });

    // Mobile hamburger menu
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const pageBody = document.body;

    function openMenu() {
      hamburger.setAttribute('aria-expanded', 'true');
      navLinks.classList.add('show');
      navLinks.setAttribute('aria-hidden', 'false');
      pageBody.style.overflow = 'hidden'; // prevent background scroll
    }

    function closeMenu() {
      hamburger.setAttribute('aria-expanded', 'false');
      navLinks.classList.remove('show');
      navLinks.setAttribute('aria-hidden', 'true');
      pageBody.style.overflow = ''; // restore scrolling
    }

    hamburger.addEventListener('click', (e) => {
      const isOpen = navLinks.classList.contains('show');
      if (isOpen) closeMenu(); else openMenu();
    });

    // Close menu when a menu link is clicked
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        closeMenu();
      });
    });

    // Close on Esc key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navLinks.classList.contains('show')) {
        closeMenu();
      }
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!navbar.contains(e.target) && navLinks.classList.contains('show')) {
        navLinks.classList.remove('show');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });
  </script>

</body>
</html>
