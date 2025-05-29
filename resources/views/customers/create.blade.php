@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-user-plus me-2"></i>
            Add New Customer
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="meter_number" class="form-label">Meter Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('meter_number') is-invalid @enderror" 
                               id="meter_number" 
                               name="meter_number" 
                               value="{{ old('meter_number') }}" 
                               required>
                        @error('meter_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="connection_type" class="form-label">Connection Type</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-plug"></i>
                        </span>
                        <select class="form-select @error('connection_type') is-invalid @enderror" 
                                id="connection_type" 
                                name="connection_type" 
                                required>
                            <option value="">Select Type</option>
                            <option value="residential" {{ old('connection_type') === 'residential' ? 'selected' : '' }}>
                                Residential
                            </option>
                            <option value="commercial" {{ old('connection_type') === 'commercial' ? 'selected' : '' }}>
                                Commercial
                            </option>
                            <option value="industrial" {{ old('connection_type') === 'industrial' ? 'selected' : '' }}>
                                Industrial
                            </option>
                        </select>
                        @error('connection_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('first_name') is-invalid @enderror" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}" 
                               required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('last_name') is-invalid @enderror" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}" 
                               required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
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

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="3" 
                              required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 