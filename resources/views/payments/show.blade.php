@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Details</h5>
                    <div>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-primary me-2">Edit</a>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment #</h6>
                            <p class="h5">{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Date</h6>
                            <p class="h5">{{ $payment->payment_date }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer</h6>
                            <p class="h5">{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</p>
                            <p class="text-muted mb-0">Meter #: {{ $payment->customer->meter_number }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Amount Paid</h6>
                            <p class="h3 text-success">₱{{ number_format($payment->amount, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Bill Details</h6>
                            <p class="mb-1">Bill #: {{ str_pad($payment->bill->bill_number, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1">Billing Date: {{ $payment->bill->billing_date }}</p>
                            <p class="mb-0">Due Date: {{ $payment->bill->due_date }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Payment Method</h6>
                            <p class="h5">{{ ucfirst($payment->payment_method) }}</p>
                            @if($payment->reference_number)
                                <p class="text-muted mb-0">Ref #: {{ $payment->reference_number }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Received By</h6>
                            <p class="mb-0">{{ $payment->receiver->name }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Recorded</h6>
                            <p class="mb-0">{{ $payment->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="mb-4">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0">{{ $payment->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" 
                            onsubmit="return confirm('Are you sure you want to delete this payment? This will mark the bill as unpaid.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 