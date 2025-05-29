@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Water Rate Details</h5>
                    <div>
                        <a href="{{ route('water-rates.edit', $waterRate) }}" class="btn btn-primary me-2">Edit</a>
                        <a href="{{ route('water-rates.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Category</h6>
                            <p class="h5">{{ ucfirst($waterRate->category) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-{{ $waterRate->is_active ? 'success' : 'secondary' }} fs-6">
                                {{ $waterRate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Minimum Rate</h6>
                            <p class="h5">₱{{ number_format($waterRate->minimum_rate, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Rate per Cubic Meter</h6>
                            <p class="h5">₱{{ number_format($waterRate->cubic_meter_rate, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Minimum Cubic Meters</h6>
                            <p class="h5">{{ number_format($waterRate->minimum_cubic_meters) }} m³</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Effective Date</h6>
                            <p class="h5">{{ $waterRate->effective_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if($waterRate->description)
                        <div class="mb-4">
                            <h6 class="text-muted">Description</h6>
                            <p class="mb-0">{{ $waterRate->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Created</h6>
                            <p class="mb-0">{{ $waterRate->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Last Updated</h6>
                            <p class="mb-0">{{ $waterRate->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 