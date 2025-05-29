@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Meter Readings</h5>
                    <a href="{{ route('meter-readings.create') }}" class="btn btn-primary">Add New Reading</a>
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
                                    <th>Reading Date</th>
                                    <th>Status</th>
                                    <th>Read By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($readings as $reading)
                                    <tr>
                                        <td>{{ $reading->customer->name }}</td>
                                        <td>{{ number_format($reading->reading, 2) }}</td>
                                        <td>{{ $reading->reading_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $reading->status === 'verified' ? 'success' : ($reading->status === 'disputed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($reading->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $reading->reader->name }}</td>
                                        <td>
                                            <a href="{{ route('meter-readings.edit', $reading) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="{{ route('meter-readings.show', $reading) }}" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No meter readings found.</td>
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