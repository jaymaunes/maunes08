@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Water Rate</h5>
                    <a href="{{ route('water-rates.index') }}" class="btn btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('water-rates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="minimum_rate" class="form-label">Minimum Rate (₱)</label>
                            <input type="number" step="0.01" name="minimum_rate" id="minimum_rate" 
                                class="form-control @error('minimum_rate') is-invalid @enderror"
                                value="{{ old('minimum_rate') }}" required>
                            @error('minimum_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cubic_meter_rate" class="form-label">Rate per Cubic Meter (₱)</label>
                            <input type="number" step="0.01" name="cubic_meter_rate" id="cubic_meter_rate" 
                                class="form-control @error('cubic_meter_rate') is-invalid @enderror"
                                value="{{ old('cubic_meter_rate') }}" required>
                            @error('cubic_meter_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="minimum_cubic_meters" class="form-label">Minimum Cubic Meters</label>
                            <input type="number" name="minimum_cubic_meters" id="minimum_cubic_meters" 
                                class="form-control @error('minimum_cubic_meters') is-invalid @enderror"
                                value="{{ old('minimum_cubic_meters') }}" required>
                            @error('minimum_cubic_meters')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="effective_date" class="form-label">Effective Date</label>
                            <input type="date" name="effective_date" id="effective_date" 
                                class="form-control @error('effective_date') is-invalid @enderror"
                                value="{{ old('effective_date', date('Y-m-d')) }}" required>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" 
                                    class="form-check-input @error('is_active') is-invalid @enderror"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Set as Active Rate
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" 
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Water Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 