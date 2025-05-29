@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Bill</h5>
                        <div>
                            <a href="{{ route('bills.show', $bill) }}" class="btn btn-info">View Bill</a>
                            <a href="{{ route('bills.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
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

                    <form action="{{ route('bills.update', $bill) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $bill->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
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
                                    <option value="{{ $reading->id }}" {{ old('meter_reading_id', $bill->meter_reading_id) == $reading->id ? 'selected' : '' }}>
                                        Reading: {{ number_format($reading->reading, 2) }} m³ ({{ $reading->reading_date->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('meter_reading_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bill_date" class="form-label">Bill Date</label>
                            <input type="date" class="form-control @error('bill_date') is-invalid @enderror" 
                                id="bill_date" name="bill_date" value="{{ old('bill_date', $bill->bill_date) }}" required>
                            @error('bill_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                id="due_date" name="due_date" value="{{ old('due_date', $bill->due_date) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes', $bill->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('customer_id').addEventListener('change', function() {
        const customerId = this.value;
        const readingSelect = document.getElementById('meter_reading_id');
        const options = readingSelect.options;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.dataset.customerId === customerId || option.value === '') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
    });
</script>
@endpush

@endsection 