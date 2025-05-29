@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Bill Details</h5>
                        <div>
                            @if($bill->status !== 'paid')
                                <a href="{{ route('bills.edit', $bill) }}" class="btn btn-primary">Edit</a>
                            @endif
                            <a href="{{ route('bills.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $bill->customer->name }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $bill->customer->address }}</p>
                            <p class="mb-1"><strong>Contact:</strong> {{ $bill->customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Bill Information</h6>
                            <p class="mb-1"><strong>Bill #:</strong> {{ str_pad($bill->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1"><strong>Bill Date:</strong> {{ $bill->bill_date->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Due Date:</strong> {{ $bill->due_date->format('M d, Y') }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->is_overdue ? 'danger' : 'warning') }}">
                                    {{ ucfirst($bill->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Consumption Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Reading:</strong></p>
                                            <h3 class="text-primary">{{ number_format($bill->meterReading->reading, 2) }} m³</h3>
                                            <p class="text-muted">Taken on {{ $bill->meterReading->reading_date->format('M d, Y') }}</p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <p class="mb-1"><strong>Amount Due:</strong></p>
                                            <h3 class="text-primary">₱{{ number_format($bill->amount, 2) }}</h3>
                                            <p class="text-muted">Rate: ₱{{ number_format($bill->rate, 2) }}/m³</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bill->notes)
                        <div class="mt-4">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0">{{ $bill->notes }}</p>
                        </div>
                    @endif

                    @if($bill->status !== 'paid')
                        <div class="mt-4">
                            <form action="{{ route('bills.destroy', $bill) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Bill</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 