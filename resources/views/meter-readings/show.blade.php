@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Meter Reading Details</h5>
                        <div>
                            <a href="{{ route('meter-readings.edit', $meterReading) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('meter-readings.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $meterReading->customer->name }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $meterReading->customer->address }}</p>
                            <p class="mb-1"><strong>Contact:</strong> {{ $meterReading->customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Reading Information</h6>
                            <p class="mb-1"><strong>Reading Date:</strong> {{ $meterReading->reading_date->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Read By:</strong> {{ $meterReading->reader->name }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $meterReading->status === 'verified' ? 'success' : ($meterReading->status === 'disputed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($meterReading->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Reading Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Current Reading:</strong></p>
                                            <h3 class="text-primary">{{ number_format($meterReading->reading, 2) }} m³</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($meterReading->notes)
                        <div class="mt-4">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0">{{ $meterReading->notes }}</p>
                        </div>
                    @endif

                    @if($meterReading->status !== 'verified')
                        <div class="mt-4">
                            <form action="{{ route('meter-readings.destroy', $meterReading) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this reading?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Reading</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 