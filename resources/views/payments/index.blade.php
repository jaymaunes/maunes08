@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Today's Collections</h5>
                    <h3 class="mb-0">₱{{ number_format($stats['total_today'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">This Month's Collections</h5>
                    <h3 class="mb-0">₱{{ number_format($stats['total_month'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Payment Methods</h5>
                    <div class="d-flex justify-content-between">
                        @foreach($stats['payment_methods'] as $method => $count)
                            <span>{{ ucfirst($method) }}: {{ $count }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payments</h5>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">Record New Payment</a>
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
                                    <th>Payment #</th>
                                    <th>Bill #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Received By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ str_pad($payment->bill_id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $payment->customer->name }}</td>
                                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td>{{ $payment->receiver->name }}</td>
                                        <td>
                                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No payments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 