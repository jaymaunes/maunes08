@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Record New Payment</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Payments
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
                        @csrf

                        <!-- Customer Selection -->
                        <div class="mb-4">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->meter_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bill Selection -->
                        <div class="mb-4">
                            <label for="bill_id" class="form-label">Bill to Pay</label>
                            <select name="bill_id" id="bill_id" class="form-select @error('bill_id') is-invalid @enderror" required>
                                <option value="">Select Bill</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}" 
                                            data-customer="{{ $bill->customer_id }}"
                                            data-amount="{{ $bill->total_amount }}"
                                            data-consumption="{{ $bill->consumption }}"
                                            data-billing-date="{{ date('M d, Y', strtotime($bill->billing_date)) }}"
                                            {{ old('bill_id') == $bill->id ? 'selected' : '' }}
                                            class="bill-option customer-{{ $bill->customer_id }}"
                                            style="display: none;">
                                        Bill #{{ $bill->bill_number }} ({{ date('M d, Y', strtotime($bill->billing_date)) }})
                                        - Consumption: {{ $bill->consumption }} m³
                                        - Amount Due: ₱{{ number_format($bill->total_amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bill_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- Bill Details Box -->
                            <div id="billDetails" class="card mt-2" style="display: none;">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Bill Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Billing Date:</strong> <span id="billDate">-</span></p>
                                            <p class="mb-1"><strong>Consumption:</strong> <span id="billConsumption">-</span> m³</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Due Date:</strong> <span id="billDueDate">-</span></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-danger">Unpaid</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="form-label">Amount to Pay</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="amount" id="amount" 
                                    class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}" required readonly>
                            </div>
                            <small class="text-muted" id="amountInWords"></small>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" 
                                class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                @foreach($paymentMethods as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_method') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reference Number -->
                        <div class="mb-4" id="referenceNumberGroup" style="display: none;">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" name="reference_number" id="reference_number" 
                                class="form-control @error('reference_number') is-invalid @enderror"
                                value="{{ old('reference_number') }}">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="mb-4">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" id="payment_date" 
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date', date('Y-m-d')) }}" 
                                max="{{ date('Y-m-d') }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Record Payment
                            </button>
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
    const billSelect = document.getElementById('bill_id');
    const amountInput = document.getElementById('amount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const referenceNumberGroup = document.getElementById('referenceNumberGroup');
    const referenceNumberInput = document.getElementById('reference_number');
    const billDetails = document.getElementById('billDetails');
    const billDate = document.getElementById('billDate');
    const billDueDate = document.getElementById('billDueDate');
    const billConsumption = document.getElementById('billConsumption');
    const amountInWords = document.getElementById('amountInWords');

    // Function to convert number to words
    function numberToWords(number) {
        const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];
        const teens = ['Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if (number === 0) return 'Zero';
        
        function convertLessThanThousand(n) {
            if (n === 0) return '';
            
            if (n < 11) return units[n];
            if (n < 20) return teens[n - 11];
            
            const unit = n % 10;
            const ten = Math.floor(n / 10) % 10;
            const hundred = Math.floor(n / 100);
            
            let result = '';
            if (hundred > 0) {
                result += units[hundred] + ' Hundred';
                if (ten > 0 || unit > 0) result += ' and ';
            }
            
            if (ten > 1) {
                result += tens[ten];
                if (unit > 0) result += '-' + units[unit];
            } else if (ten === 1) {
                result += teens[unit];
            } else if (unit > 0) {
                result += units[unit];
            }
            
            return result;
        }
        
        const num = parseFloat(number);
        const wholePart = Math.floor(num);
        const decimalPart = Math.round((num - wholePart) * 100);
        
        let result = convertLessThanThousand(wholePart);
        if (decimalPart > 0) {
            result += ' Pesos and ' + convertLessThanThousand(decimalPart) + ' Centavos';
        } else {
            result += ' Pesos Only';
        }
        
        return result;
    }

    // Handle customer selection
    customerSelect.addEventListener('change', function() {
        const selectedCustomerId = this.value;
        
        // Hide all bill options first
        document.querySelectorAll('.bill-option').forEach(option => {
            option.style.display = 'none';
        });

        // Show only bills for selected customer
        if (selectedCustomerId) {
            document.querySelectorAll(`.customer-${selectedCustomerId}`).forEach(option => {
                option.style.display = '';
            });
        }

        // Reset bill selection and amount
        billSelect.value = '';
        amountInput.value = '';
        billDetails.style.display = 'none';
        amountInWords.textContent = '';
    });

    // Handle bill selection
    billSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const amount = selectedOption.dataset.amount;
            amountInput.value = amount;
            amountInWords.textContent = numberToWords(amount);
            
            // Update bill details
            billDate.textContent = selectedOption.dataset.billingDate;
            billConsumption.textContent = selectedOption.dataset.consumption;
            billDetails.style.display = 'block';
        } else {
            amountInput.value = '';
            amountInWords.textContent = '';
            billDetails.style.display = 'none';
        }
    });

    // Handle payment method selection
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value && this.value !== 'cash') {
            referenceNumberGroup.style.display = 'block';
            referenceNumberInput.required = true;
        } else {
            referenceNumberGroup.style.display = 'none';
            referenceNumberInput.required = false;
        }
    });

    // Initialize payment method display
    if (paymentMethodSelect.value && paymentMethodSelect.value !== 'cash') {
        referenceNumberGroup.style.display = 'block';
        referenceNumberInput.required = true;
    }

    // Initialize bill options if customer is pre-selected
    if (customerSelect.value) {
        document.querySelectorAll(`.customer-${customerSelect.value}`).forEach(option => {
            option.style.display = '';
        });
    }
});
</script>
@endpush 