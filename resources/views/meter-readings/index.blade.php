@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Meter Readings</h5>
                    <a href="{{ route('meter-readings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Reading
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Reading</th>
                                    <th>Previous Reading</th>
                                    <th>Consumption</th>
                                    <th>Reading Date</th>
                                    <th>Status</th>
                                    <th>Read By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($readings as $reading)
                                    <tr>
                                        <td>
                                            {{ $reading->customer->full_name }}
                                            <br>
                                            <small class="text-muted">{{ $reading->customer->meter_number }}</small>
                                        </td>
                                        <td>{{ number_format($reading->reading, 2) }} m³</td>
                                        <td>{{ number_format($reading->previous_reading, 2) }} m³</td>
                                        <td>{{ number_format($reading->consumption, 2) }} m³</td>
                                        <td>{{ $reading->reading_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $reading->status === 'verified' ? 'success' : ($reading->status === 'disputed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($reading->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $reading->reader->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('meter-readings.edit', $reading) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('meter-readings.show', $reading) }}" 
                                                   class="btn btn-sm btn-info"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No meter readings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $readings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 