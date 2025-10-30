<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CShopU') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('build/bootstrap/bootstrap.v5.3.2.min.css')}}">
    
    <!-- Unified Admin Design System -->
    <link rel="stylesheet" href="{{asset('css/admin-unified.css')}}">
    
    <!-- Custom Admin Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            padding-top: 0;  /* Remove top padding */
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 220px; /* Reduced from 280px */
            min-height: 100vh;
            background: #f8f9fa;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding-top: 0;
        }

        .content-wrapper {
            padding: 1rem;
        }

        .page-header {
            background: linear-gradient(135deg, #800000 0%, #a00000 100%);
            padding: 1rem 1.5rem;
            margin: -1rem -1rem 1rem -1rem;
            border-radius: 0;
            color: white;
            padding: 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 0 0 20px 20px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 3px solid #800000;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: #800000;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #800000;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
        }

        .admin-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .admin-card {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .admin-card-icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            display: block;
            color: #800000;
        }

        .admin-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .admin-card-description {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .admin-btn {
            background: linear-gradient(135deg, #800000 0%, #a00000 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .admin-btn:hover {
            background: linear-gradient(135deg, #a00000 0%, #800000 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
        }

        /* Mobile Header */
        .mobile-header {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .mobile-menu-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #800000;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .mobile-menu-toggle:hover {
            background-color: #f8f9fa;
        }

        .mobile-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-left: 1rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .page-header {
                margin: -1rem -1rem 1rem -1rem;
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        @include('layouts.Admin.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Mobile Menu Toggle Button Only -->
            <button class="mobile-menu-toggle d-md-none position-fixed" id="mobileMenuToggle" type="button" style="top: 1rem; left: 1rem; z-index: 1030;">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="content-wrapper">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        
        if (mobileMenuToggle && sidebar) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }
    });
    </script>
</body>
</html>