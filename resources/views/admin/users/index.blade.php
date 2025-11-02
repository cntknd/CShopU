@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title">👥 Manage Users</h1>
    <p class="page-subtitle">View and manage user accounts and permissions.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
.user-card{background:white;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,.1);transition:all .3s ease;border:1px solid #e9ecef}
.user-card:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,.15)}
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 600;
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
    display: inline-block;
}
.search-filter-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

/* Responsive: convert users table into stacked cards on small screens */
@media (max-width: 768px) {
    .table-modern thead { display: none; }
    .table-modern tbody tr { display: block; margin-bottom: 0.9rem; background: #fff; border-radius: 10px; box-shadow: 0 6px 18px rgba(0,0,0,0.04); padding: 0.75rem; }
    .table-modern tbody tr td { display: flex; justify-content: space-between; padding: 0.45rem 0.5rem; border: none; font-size: 0.95rem; }
    .table-modern tbody tr td:before { content: attr(data-label); font-weight: 700; color: #495057; margin-right: 0.75rem; flex: 0 0 45%; text-transform: capitalize; font-size: 0.85rem; }
    .table-modern tbody tr td .badge { margin: 0; }

    /* Actions: stack and full width */
    .table-modern tbody tr td .btn-group { display:flex; flex-direction:column; gap:0.5rem; width:100%; }
    .table-modern tbody tr td .btn-group a, .table-modern tbody tr td .btn-group button { width:100%; display:block; padding:0.55rem 0.6rem; text-align:center; border-radius:6px; }

    /* Improve form layout on small screens */
    .row.g-3 { gap: 0.75rem; }
    .col-md-6, .col-md-4, .col-md-2 { flex:0 0 100%; max-width:100%; }
}
</style>
<div class="user-card p-4 mb-4">
    <!-- Search and Filter Section -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label fw-semibold">Search Users</label>
                <input type="text" 
                       class="form-control" 
                       id="search" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search by name, email, or student ID...">
            </div>
            <div class="col-md-4">
                <label for="role_filter" class="form-label fw-semibold">Filter by Role</label>
                <select class="form-control" id="role_filter" name="role_filter">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $roleFilter == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if($search || $roleFilter)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Clear Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="h5 fw-bold mb-0">Users ({{ $allusers->total() }})</h5>
        
        @if($search || $roleFilter)
        <div class="text-muted small">
            <i class="bi bi-funnel me-1"></i>Showing filtered results
        </div>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table-modern table align-middle">
            <thead class="table-light">
                <tr>
                    <th class="fw-semibold">Name</th>
                    <th class="fw-semibold">Email</th>
                    <th class="fw-semibold">Roles</th>
                    <th class="fw-semibold">Date Created</th>
                    <th class="fw-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allusers as $user)
                <tr>
                    <td data-label="name" class="fw-semibold">{{ $user->name }}</td>
                    <td data-label="email">{{ $user->email }}</td>
                    <td data-label="roles">
                        @if($user->roles()->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </td>
                    <td data-label="created" class="small text-muted">{{ date('M d, Y h:i A', strtotime($user->created_at)) }}</td>
                    <td data-label="actions" class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-modern" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit User Info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}#password-section" class="btn btn-sm btn-outline-warning" title="Reset Password">
                                <i class="bi bi-key"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-people display-4 mb-3"></i>
                            <p class="h5">No users found</p>
                            <p class="small">Users will appear here once they register</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{$allusers->links()}}
    </div>
</div>

@endsection
@endcan