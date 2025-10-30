@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-header">
    <h1 class="page-title">✏️ Edit User</h1>
    <p class="page-subtitle">Update user information and permissions.</p>
</div>

<style>
    .edit-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
        border: 1px solid #e9ecef;
    }
</style>

<div class="edit-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h5 fw-bold mb-0">Edit User Information</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Cancel
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            @if($user->student_id)
            <div class="col-md-6 mb-3">
                <label for="student_id" class="form-label fw-semibold">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}">
            </div>
            @endif

            @if($user->student_employee_id)
            <div class="col-md-6 mb-3">
                <label for="student_employee_id" class="form-label fw-semibold">Student/Employee ID</label>
                <input type="text" class="form-control" id="student_employee_id" name="student_employee_id" value="{{ old('student_employee_id', $user->student_employee_id) }}">
            </div>
            @endif

            @if($user->first_name)
            <div class="col-md-4 mb-3">
                <label for="first_name" class="form-label fw-semibold">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
            </div>
            @endif

            @if($user->last_name)
            <div class="col-md-4 mb-3">
                <label for="last_name" class="form-label fw-semibold">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
            </div>
            @endif

            @if($user->middle_initial)
            <div class="col-md-4 mb-3">
                <label for="middle_initial" class="form-label fw-semibold">Middle Initial</label>
                <input type="text" class="form-control" id="middle_initial" name="middle_initial" value="{{ old('middle_initial', $user->middle_initial) }}" maxlength="1">
            </div>
            @endif

            <div id="password-section" class="col-12 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h6 class="text-warning fw-semibold mb-2">
                            <i class="bi bi-shield-lock me-2"></i>Reset/Change Password
                        </h6>
                        <p class="text-muted small mb-3">
                            Use this to reset the user's password if they forgot it. Leave blank if you don't want to change it.
                        </p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">New Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="eye-password"></i>
                                    </button>
                                </div>
                                <small class="text-muted">⚠️ Minimum 8 characters - Will override current password</small>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye" id="eye-password_confirmation"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label fw-semibold">Roles</label>
                <div class="card border-0 bg-light p-3">
                    @foreach($roles as $role)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" 
                            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Update User
            </button>
        </div>
    </form>
</div>

@endsection

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById('eye-' + fieldId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endcan

