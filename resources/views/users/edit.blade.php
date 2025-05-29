@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-user-edit me-2"></i>
            Edit User
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Leave blank to keep current password</div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Update User
                </button>
            </div>
        </form>

        @if(auth()->id() !== $user->id)
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    {{ __('Delete User') }}
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
