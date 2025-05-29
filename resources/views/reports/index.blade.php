@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Reports Dashboard</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Total Customers Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Total Customers</h5>
                                    <h2 class="display-4">{{ $totalCustomers }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Total Bills Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Total Bills</h5>
                                    <h2 class="display-4">{{ $totalBills }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Total Payments Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Total Payments</h5>
                                    <h2 class="display-4">₱{{ number_format($totalPayments, 2) }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Unpaid Bills Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Unpaid Bills</h5>
                                    <h2 class="display-4">{{ $unpaidBills }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional reports sections can be added here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 