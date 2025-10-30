@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-header">
    <h1 class="page-title">👤 User Details</h1>
    <p class="page-subtitle">View detailed information about this user.</p>
</div>

<style>
    .detail-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
        transition: all .3s ease;
        border: 1px solid #e9ecef;
    }
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,.15);
    }
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        display: inline-block;
    }
</style>

<div class="detail-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5 fw-bold mb-0">User Information</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-key me-1"></i>Reset Password
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Full Name</h6>
                    <p class="fs-5 fw-semibold mb-0">{{ $user->name }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Email</h6>
                    <p class="fs-5 fw-semibold mb-0">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        @if($user->student_id)
        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Student ID</h6>
                    <p class="fs-5 fw-semibold mb-0">{{ $user->student_id }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($user->student_employee_id)
        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Student/Employee ID</h6>
                    <p class="fs-5 fw-semibold mb-0">{{ $user->student_employee_id }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Roles</h6>
                    @if($user->roles()->count() > 0)
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    @else
                        <span class="badge bg-secondary">User</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Password</h6>
                    <p class="fs-5 fw-semibold mb-0 text-muted">•••••••• (hidden)</p>
                    <small class="text-muted">Password is encrypted and cannot be viewed</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Account Created</h6>
                    <p class="fs-5 fw-semibold mb-0">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        @if($user->first_name || $user->last_name)
        <div class="col-12 mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Additional Information</h6>
                    <div class="row">
                        @if($user->first_name)
                        <div class="col-md-4">
                            <small class="text-muted">First Name:</small>
                            <p class="mb-0 fw-semibold">{{ $user->first_name }}</p>
                        </div>
                        @endif
                        @if($user->last_name)
                        <div class="col-md-4">
                            <small class="text-muted">Last Name:</small>
                            <p class="mb-0 fw-semibold">{{ $user->last_name }}</p>
                        </div>
                        @endif
                        @if($user->middle_initial)
                        <div class="col-md-4">
                            <small class="text-muted">Middle Initial:</small>
                            <p class="mb-0 fw-semibold">{{ $user->middle_initial }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
@endcan

