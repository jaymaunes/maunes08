@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Monthly Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Monthly Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <h6 class="card-title">New Bills</h6>
                                    <h3>{{ $monthlyStats['bills'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Monthly Payments</h6>
                                    <h3>₱{{ number_format($monthlyStats['payments'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <h6 class="card-title">New Customers</h6>
                                    <h3>{{ $monthlyStats['new_customers'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Unpaid Bills</h6>
                                    <h3>{{ $monthlyStats['unpaid_bills'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Bills -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Bills</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($recentBills as $bill)
                                    <a href="{{ route('bills.show', $bill) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $bill->customer->name }}</h6>
                                            <small>₱{{ number_format($bill->total_amount, 2) }}</small>
                                        </div>
                                        <p class="mb-1">Bill #{{ $bill->bill_number }}</p>
                                        <small>{{ $bill->created_at->diffForHumans() }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Payments</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($recentPayments as $payment)
                                    <a href="{{ route('payments.show', $payment) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $payment->bill->customer->name }}</h6>
                                            <small>₱{{ number_format($payment->amount, 2) }}</small>
                                        </div>
                                        <p class="mb-1">Payment for Bill #{{ $payment->bill->bill_number }}</p>
                                        <small>{{ $payment->created_at->diffForHumans() }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 