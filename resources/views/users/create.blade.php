@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-user-plus me-2"></i>
            Add New User
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
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
                           value="{{ old('name') }}" 
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
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 