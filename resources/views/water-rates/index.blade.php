@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        @foreach($stats['categories'] as $category)
            <div class="col-md-4">
                <div class="card {{ $category['active'] ? 'bg-success' : 'bg-secondary' }} text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category['name'] }} Rates</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Rates: {{ $category['count'] }}</span>
                            @if($category['active'])
                                <div>
                                    <small>Active Rate:</small>
                                    <h6 class="mb-0">₱{{ number_format($category['active']->minimum_rate, 2) }} min</h6>
                                    <small>₱{{ number_format($category['active']->cubic_meter_rate, 2) }}/m³</small>
                                </div>
                            @else
                                <span class="badge bg-warning text-dark">No Active Rate</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Water Rates</h5>
                    <a href="{{ route('water-rates.create') }}" class="btn btn-primary">Add New Rate</a>
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
                                    <th>Category</th>
                                    <th>Minimum Rate</th>
                                    <th>Rate per m³</th>
                                    <th>Min. m³</th>
                                    <th>Effective Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $rate)
                                    <tr>
                                        <td>{{ ucfirst($rate->category) }}</td>
                                        <td>₱{{ number_format($rate->minimum_rate, 2) }}</td>
                                        <td>₱{{ number_format($rate->cubic_meter_rate, 2) }}</td>
                                        <td>{{ $rate->minimum_cubic_meters }}</td>
                                        <td>{{ $rate->effective_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $rate->is_active ? 'success' : 'secondary' }}">
                                                {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('water-rates.edit', $rate) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="{{ route('water-rates.show', $rate) }}" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No water rates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $rates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 