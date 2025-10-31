<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Compact Header -->
    <div class="sidebar-header py-2 px-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-shop text-danger"></i>
            <span class="fw-bold">CShopU</span>
        </div>
        <button class="btn btn-link text-danger p-0 d-md-none" id="sidebarToggle">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Compact User Profile -->
    <div class="px-3 py-2 border-bottom border-light">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle text-danger"></i>
            <div class="small">
                <div class="fw-medium">{{ Auth::user()->first_name }}</div>
                <div class="text-muted small">Admin</div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-navigation">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </span>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.manageproducts.index') }}" class="nav-link {{ request()->routeIs('admin.manageproducts.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    <span class="nav-label">Products</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-tags"></i>
                    </span>
                    <span class="nav-label">Categories</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </span>
                    <span class="nav-label">Orders</span>
                    <span class="nav-badge" id="orderBadge" style="display: none;">0</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.sales.overview') }}" class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-graph-up"></i>
                    </span>
                    <span class="nav-label">Sales</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.feedbacks.index') }}" class="nav-link {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-chat-dots"></i>
                    </span>
                    <span class="nav-label">Feedbacks</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-people"></i>
                    </span>
                    <span class="nav-label">Users</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.backups.index') }}" class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <span class="nav-label">Backups</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-nav">
            <a href="{{ route('profile.edit') }}" class="footer-link">
                <span class="footer-icon">
                    <i class="bi bi-person-gear"></i>
                </span>
                <span class="footer-label">Profile</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="footer-link logout-btn">
                    <span class="footer-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </span>
                    <span class="footer-label">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
/* ===========================================
   ADMIN SIDEBAR STYLES - CONSISTENT LAYOUT
   =========================================== */

/* Main Sidebar Container */
.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #ffffff;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Sidebar Header */
.sidebar-header {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid #404040;
    flex-shrink: 0;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.brand-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #800000 0%, #a00000 100%);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
}

.brand-icon i {
    font-size: 1.5rem;
    color: #ffffff;
}

.brand-text {
    display: flex;
    flex-direction: column;
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 0.75rem;
    color: #a0a0a0;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.sidebar-toggle:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* User Profile Section */
.sidebar-user {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid #404040;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.user-avatar {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #800000 0%, #a00000 100%);
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
}

.user-avatar i {
    font-size: 1.75rem;
    color: #ffffff;
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 1rem;
    color: #ffffff;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.875rem;
    color: #a0a0a0;
    font-weight: 500;
}

/* Navigation Menu */
.sidebar-navigation {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
    overflow-x: hidden;
    padding-left: 20px;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    color: #e0e0e0;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    border-radius: 0 25px 25px 0;
    margin-right: 1rem;
    min-height: 48px;
}

.nav-link:hover {
    background: linear-gradient(90deg, #800000 0%, #a00000 100%);
    color: #ffffff;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
}

.nav-link.active {
    background: linear-gradient(90deg, #800000 0%, #a00000 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(128, 0, 0, 0.4);
    transform: translateX(5px);
}

.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.nav-icon i {
    font-size: 1.125rem;
}

.nav-label {
    font-weight: 500;
    font-size: 0.95rem;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.nav-badge {
    background: #dc3545;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

/* Sidebar Footer */
.sidebar-footer {
    border-top: 1px solid #404040;
    padding: 1rem 0;
    flex-shrink: 0;
}

.footer-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.footer-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    color: #a0a0a0;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 0 25px 25px 0;
    margin-right: 1rem;
    min-height: 44px;
}

.footer-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    transform: translateX(3px);
}

.logout-form {
    width: 100%;
}

.logout-btn {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    color: #dc3545;
}

.logout-btn:hover {
    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
    color: #ffffff;
    transform: translateX(3px);
}

.footer-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.footer-icon i {
    font-size: 1rem;
}

.footer-label {
    font-weight: 500;
    font-size: 0.9rem;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
    }

    .admin-sidebar.open {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }
}

/* Main Content Adjustment */
.main-content {
    margin-left: 280px;
    min-height: 100vh;
    background: #f8f9fa;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
}

/* Scrollbar Styling */
.sidebar-navigation::-webkit-scrollbar {
    width: 4px;
}

.sidebar-navigation::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-navigation::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-navigation::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Animation for smooth transitions */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-item {
    animation: slideIn 0.3s ease forwards;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.2s; }
.nav-item:nth-child(3) { animation-delay: 0.3s; }
.nav-item:nth-child(4) { animation-delay: 0.4s; }
.nav-item:nth-child(5) { animation-delay: 0.5s; }
.nav-item:nth-child(6) { animation-delay: 0.6s; }
.nav-item:nth-child(7) { animation-delay: 0.7s; }
.nav-item:nth-child(8) { animation-delay: 0.8s; }
</style>
