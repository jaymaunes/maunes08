@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Meter Reading</h5>
                        <a href="{{ route('meter-readings.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
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

                    <form action="{{ route('meter-readings.update', $meterReading) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $meterReading->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reading" class="form-label">Reading (m³)</label>
                            <input type="number" step="0.01" class="form-control @error('reading') is-invalid @enderror" 
                                id="reading" name="reading" value="{{ old('reading', $meterReading->reading) }}" required>
                            @error('reading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reading_date" class="form-label">Reading Date</label>
                            <input type="date" class="form-control @error('reading_date') is-invalid @enderror" 
                                id="reading_date" name="reading_date" value="{{ old('reading_date', $meterReading->reading_date->format('Y-m-d')) }}" required>
                            @error('reading_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $meterReading->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ old('status', $meterReading->status) === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="disputed" {{ old('status', $meterReading->status) === 'disputed' ? 'selected' : '' }}>Disputed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes', $meterReading->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Reading</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 