@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Unpaid</h5>
                    <h3 class="mb-0">₱{{ number_format($stats['total_unpaid'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Paid</h5>
                    <h3 class="mb-0">₱{{ number_format($stats['total_paid'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Overdue Bills</h5>
                    <h3 class="mb-0">{{ $stats['overdue_count'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bills</h5>
                    <a href="{{ route('bills.create') }}" class="btn btn-primary">Generate New Bill</a>
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
                                    <th>Bill #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Consumption</th>
                                    <th>Bill Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bills as $bill)
                                    <tr>
                                        <td>{{ str_pad($bill->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $bill->customer->name }}</td>
                                        <td>₱{{ number_format($bill->amount, 2) }}</td>
                                        <td>{{ number_format($bill->consumption, 2) }} m³</td>
                                        <td>{{ $bill->bill_date->format('M d, Y') }}</td>
                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->is_overdue ? 'danger' : 'warning') }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('bills.show', $bill) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('bills.edit', $bill) }}" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No bills found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $bills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 