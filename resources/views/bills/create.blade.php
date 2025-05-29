@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Generate New Bill</h5>
                        <a href="{{ route('bills.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bills.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->full_name }} ({{ $customer->meter_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meter_reading_id" class="form-label">Meter Reading</label>
                            <select name="meter_reading_id" id="meter_reading_id" class="form-control @error('meter_reading_id') is-invalid @enderror" required>
                                <option value="">Select Meter Reading</option>
                                @foreach($readings as $reading)
                                    <option value="{{ $reading->id }}" 
                                        data-customer-id="{{ $reading->customer_id }}"
                                        {{ old('meter_reading_id') == $reading->id ? 'selected' : '' }}>
                                        Reading: {{ number_format($reading->reading, 2) }} m³ | Previous: {{ number_format($reading->previous_reading, 2) }} m³ | Date: {{ $reading->reading_date->format('M d, Y') }} | Consumption: {{ number_format($reading->consumption, 2) }} m³
                                    </option>
                                @endforeach
                            </select>
                            @error('meter_reading_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="billing_date" class="form-label">Bill Date</label>
                            <input type="date" class="form-control @error('billing_date') is-invalid @enderror" 
                                id="billing_date" name="billing_date" value="{{ old('billing_date', date('Y-m-d')) }}" required>
                            @error('billing_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+15 days'))) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Generate Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const meterReadingSelect = document.getElementById('meter_reading_id');
    const meterReadingOptions = Array.from(meterReadingSelect.options);

    function filterMeterReadings() {
        const selectedCustomerId = customerSelect.value;
        
        // Reset meter reading select
        meterReadingSelect.innerHTML = '';
        meterReadingSelect.appendChild(new Option('Select Meter Reading', ''));
        
        // Filter and add relevant meter readings
        meterReadingOptions.forEach(option => {
            if (!option.value) return; // Skip the placeholder option
            
            const customerId = option.getAttribute('data-customer-id');
            if (!selectedCustomerId || customerId === selectedCustomerId) {
                meterReadingSelect.appendChild(option.cloneNode(true));
            }
        });

        // If there's only one meter reading (excluding placeholder), select it
        if (meterReadingSelect.options.length === 2) {
            meterReadingSelect.selectedIndex = 1;
        }
    }

    // Initial filter
    filterMeterReadings();

    // Add event listener for customer select changes
    customerSelect.addEventListener('change', filterMeterReadings);
});
</script>
@endpush
@endsection 