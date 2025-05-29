<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $payments = Payment::with(['customer', 'bill', 'receiver'])
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        $stats = [
            'total_today' => Payment::whereDate('payment_date', today())
                ->sum('amount'),
            'total_month' => Payment::whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
            'payment_methods' => Payment::select('payment_method', DB::raw('count(*) as count'))
                ->groupBy('payment_method')
                ->pluck('count', 'payment_method')
                ->toArray()
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')
            ->orderBy('last_name')
            ->where('status', 'active')
            ->get();

        $bills = Bill::where('status', 'unpaid')
            ->with('customer')  // Eager load customer data
            ->orderBy('due_date', 'asc')  // Show oldest due bills first
            ->get();

        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment',
            'gcash' => 'GCash'
        ];

        return view('payments.create', compact('customers', 'bills', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,online_payment,gcash',
            'reference_number' => 'required_unless:payment_method,cash|nullable|string|max:255',
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Get the bill and verify it's unpaid
            $bill = Bill::where('id', $validated['bill_id'])
                ->where('status', 'unpaid')
                ->firstOrFail();
            
            // Verify the bill belongs to the selected customer
            if ($bill->customer_id !== $validated['customer_id']) {
                throw new \Exception('The selected bill does not belong to this customer.');
            }

            // Validate payment amount
            if ($validated['amount'] > $bill->total_amount) {
                throw new \Exception('Payment amount cannot exceed the bill amount (₱' . number_format($bill->total_amount, 2) . ')');
            }

            if ($validated['amount'] < $bill->total_amount) {
                throw new \Exception('Partial payments are not allowed. Please pay the full amount (₱' . number_format($bill->total_amount, 2) . ')');
            }

            // Create payment record
            $payment = new Payment($validated);
            $payment->received_by = Auth::id();
            $payment->status = 'completed';
            $payment->save();

            // Update bill status
            $bill->status = 'paid';
            $bill->paid_at = now();
            $bill->save();

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment of ₱' . number_format($payment->amount, 2) . ' recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['customer', 'bill', 'receiver']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $customers = Customer::orderBy('name')->get();
        $bills = Bill::where(function($query) use ($payment) {
                $query->where('status', 'unpaid')
                    ->orWhere('id', $payment->bill_id);
            })
            ->orderBy('bill_date', 'desc')
            ->get();
        $paymentMethods = Payment::getPaymentMethods();

        return view('payments.edit', compact('payment', 'customers', 'bills', 'paymentMethods'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'reference_number' => 'nullable|string|max:255',
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // If bill is changed, update old and new bill statuses
            if ($payment->bill_id !== $validated['bill_id']) {
                $oldBill = Bill::findOrFail($payment->bill_id);
                $oldBill->status = 'unpaid';
                $oldBill->save();

                $newBill = Bill::findOrFail($validated['bill_id']);
                if ($newBill->status === 'paid') {
                    throw new \Exception('The selected bill has already been paid.');
                }
                $newBill->status = 'paid';
                $newBill->save();
            }

            $payment->update($validated);

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating payment: ' . $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        try {
            DB::beginTransaction();

            // Update bill status back to unpaid
            $bill = $payment->bill;
            $bill->status = 'unpaid';
            $bill->save();

            $payment->delete();

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }
} 