<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\MeterReading;
use App\Models\WaterRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bills = Bill::with(['customer', 'meterReading'])
            ->select('bills.*', DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"))
            ->leftJoin('customers', 'bills.customer_id', '=', 'customers.id')
            ->orderBy('customer_name', 'asc')
            ->orderBy('bills.billing_date', 'desc')
            ->paginate(10);

        $stats = [
            'total_unpaid' => Bill::where('status', 'unpaid')->sum('total_amount'),
            'total_paid' => Bill::where('status', 'paid')->sum('total_amount'),
            'overdue_count' => Bill::where('status', 'unpaid')
                ->where('due_date', '<', now())
                ->count()
        ];

        return view('bills.index', compact('bills', 'stats'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
            
        $readings = MeterReading::whereIn('status', ['pending', 'verified'])
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('bills')
                    ->whereColumn('bills.meter_reading_id', 'meter_readings.id');
            })
            ->with(['customer', 'reader'])
            ->orderBy('reading_date', 'desc')
            ->get();
        
        return view('bills.create', compact('customers', 'readings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'meter_reading_id' => 'required|exists:meter_readings,id',
            'billing_date' => 'required|date',
            'due_date' => 'required|date|after:billing_date',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Get the meter reading and verify it hasn't been billed
            $reading = MeterReading::where('id', $validated['meter_reading_id'])
                ->whereIn('status', ['pending', 'verified'])
                ->whereNotExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('bills')
                        ->whereColumn('bills.meter_reading_id', 'meter_readings.id');
                })
                ->firstOrFail();

            $customer = Customer::findOrFail($validated['customer_id']);

            // Verify that the meter reading belongs to the selected customer
            if ($reading->customer_id !== $customer->id) {
                throw new \Exception('The selected meter reading does not belong to this customer.');
            }

            // Get active water rate for customer's category
            $rate = WaterRate::where('category', $customer->connection_type)
                ->where('is_active', true)
                ->first();

            if (!$rate) {
                throw new \Exception('No active water rate found for customer category: ' . $customer->connection_type);
            }

            // Calculate consumption using the previous reading stored in meter_readings
            $consumption = $reading->reading - $reading->previous_reading;
            
            if ($consumption < 0) {
                throw new \Exception('Invalid consumption: Current reading is less than previous reading.');
            }

            // Calculate the bill amount using the water rate
            $amount = $rate->calculateCharge($consumption);

            // Generate unique bill number
            $yearMonth = date('Ym', strtotime($validated['billing_date']));
            $latestBill = Bill::where('bill_number', 'like', "BILL-{$yearMonth}-%")
                ->orderBy('bill_number', 'desc')
                ->first();
            
            $sequence = $latestBill 
                ? (intval(substr($latestBill->bill_number, -4)) + 1) 
                : 1;
            
            $billNumber = sprintf("BILL-%s-%04d", $yearMonth, $sequence);

            // Create the bill
            $bill = new Bill();
            $bill->bill_number = $billNumber;
            $bill->customer_id = $customer->id;
            $bill->meter_reading_id = $reading->id;
            $bill->consumption = $consumption;
            $bill->rate_amount = $rate->cubic_meter_rate;
            $bill->amount = $amount;
            $bill->minimum_charge = $rate->minimum_rate;
            $bill->additional_charges = 0;
            $bill->total_amount = $amount;
            $bill->billing_date = $validated['billing_date'];
            $bill->due_date = $validated['due_date'];
            $bill->notes = $validated['notes'];
            $bill->status = 'unpaid';
            $bill->save();

            // Mark the meter reading as billed and verified
            $reading->update([
                'status' => 'billed'
            ]);

            DB::commit();

            return redirect()->route('bills.index')
                ->with('success', 'Bill generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error generating bill: ' . $e->getMessage());
        }
    }

    public function show(Bill $bill)
    {
        $bill->load(['customer', 'meterReading']);
        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        if ($bill->status === 'paid') {
            return redirect()->route('bills.show', $bill)
                ->with('error', 'Paid bills cannot be edited.');
        }

        $customers = Customer::orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        $readings = MeterReading::where('status', 'verified')
            ->where(function($query) use ($bill) {
                $query->whereNotExists(function($q) {
                    $q->select(DB::raw(1))
                        ->from('bills')
                        ->whereColumn('bills.meter_reading_id', 'meter_readings.id');
                })->orWhere('id', $bill->meter_reading_id);
            })
            ->get();

        return view('bills.edit', compact('bill', 'customers', 'readings'));
    }

    public function update(Request $request, Bill $bill)
    {
        if ($bill->status === 'paid') {
            return redirect()->route('bills.show', $bill)
                ->with('error', 'Paid bills cannot be edited.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'meter_reading_id' => 'required|exists:meter_readings,id',
            'billing_date' => 'required|date',
            'due_date' => 'required|date|after:billing_date',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $reading = MeterReading::findOrFail($validated['meter_reading_id']);
            $customer = Customer::findOrFail($validated['customer_id']);

            // Get active water rate for customer's category
            $rate = WaterRate::where('category', $customer->category)
                ->where('is_active', true)
                ->firstOrFail();

            // Calculate consumption and amounts
            $consumption = $reading->reading;
            $rate_amount = $rate->cubic_meter_rate;
            $amount = $consumption * $rate_amount;
            $minimum_charge = $rate->minimum_charge;
            $additional_charges = $bill->additional_charges; // Preserve existing additional charges
            $total_amount = $amount + $minimum_charge + $additional_charges;

            $bill->fill($validated);
            $bill->consumption = $consumption;
            $bill->rate_amount = $rate_amount;
            $bill->amount = $amount;
            $bill->minimum_charge = $minimum_charge;
            $bill->total_amount = $total_amount;
            $bill->save();

            DB::commit();

            return redirect()->route('bills.show', $bill)
                ->with('success', 'Bill updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating bill: ' . $e->getMessage());
        }
    }

    public function destroy(Bill $bill)
    {
        if ($bill->status === 'paid') {
            return back()->with('error', 'Paid bills cannot be deleted.');
        }

        try {
            DB::beginTransaction();
            $bill->delete();
            DB::commit();

            return redirect()->route('bills.index')
                ->with('success', 'Bill deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting bill: ' . $e->getMessage());
        }
    }
} 