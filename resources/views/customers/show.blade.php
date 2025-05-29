@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Customer Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customer Information</h5>
                    <div>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Personal Details</h6>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Full Name:</div>
                                <div class="col-sm-8 fw-bold">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Email:</div>
                                <div class="col-sm-8">{{ $customer->email }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Phone:</div>
                                <div class="col-sm-8">{{ $customer->phone }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Address:</div>
                                <div class="col-sm-8">{{ $customer->address }}</div>
                            </div>
                        </div>
                        <!-- Account Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Account Details</h6>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Meter Number:</div>
                                <div class="col-sm-8 fw-bold">{{ $customer->meter_number }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Connection Type:</div>
                                <div class="col-sm-8">
                                    <span class="badge bg-info">{{ ucfirst($customer->connection_type) }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Status:</div>
                                <div class="col-sm-8">
                                    @if($customer->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($customer->status === 'inactive')
                                        <span class="badge bg-warning">Inactive</span>
                                    @else
                                        <span class="badge bg-danger">Disconnected</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Customer Since:</div>
                                <div class="col-sm-8">{{ $customer->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Summary Card -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body">
                            <h6 class="card-title">Total Bills</h6>
                            <h3 class="mb-0">{{ $customer->bills->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            <h6 class="card-title">Paid Bills</h6>
                            <h3 class="mb-0">{{ $customer->bills->where('status', 'paid')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                            <h6 class="card-title">Unpaid Bills</h6>
                            <h3 class="mb-0">{{ $customer->bills->where('status', 'unpaid')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body">
                            <h6 class="card-title">Average Monthly Bill</h6>
                            <h3 class="mb-0">₱{{ number_format($customer->bills->avg('total_amount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bills and Readings -->
            <div class="row">
                <!-- Recent Bills -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Bills</h5>
                            <a href="{{ route('bills.create', ['customer_id' => $customer->id]) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> New Bill
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Bill #</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customer->bills->sortByDesc('billing_date')->take(5) as $bill)
                                            <tr>
                                                <td>{{ $bill->bill_number }}</td>
                                                <td>{{ date('M d, Y', strtotime($bill->billing_date)) }}</td>
                                                <td>₱{{ number_format($bill->total_amount, 2) }}</td>
                                                <td>
                                                    @if($bill->status === 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('bills.show', $bill) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No bills found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Meter Readings -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Meter Readings</h5>
                            <a href="{{ route('meter-readings.create', ['customer_id' => $customer->id]) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> New Reading
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reading</th>
                                            <th>Consumption</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customer->meterReadings->sortByDesc('reading_date')->take(5) as $reading)
                                            <tr>
                                                <td>{{ date('M d, Y', strtotime($reading->reading_date)) }}</td>
                                                <td>{{ $reading->reading }} m³</td>
                                                <td>
                                                    @if($reading->previous_reading)
                                                        {{ $reading->reading - $reading->previous_reading }} m³
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($reading->status === 'verified')
                                                        <span class="badge bg-success">Verified</span>
                                                    @elseif($reading->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Disputed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('meter-readings.show', $reading) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No readings found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 