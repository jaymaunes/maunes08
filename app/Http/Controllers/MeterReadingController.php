<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeterReadingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $readings = MeterReading::with(['customer', 'reader'])
            ->orderBy('reading_date', 'desc')
            ->paginate(10);

        return view('meter-readings.index', compact('readings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')
            ->orderBy('last_name')
            ->where('status', 'active')
            ->get();
        return view('meter-readings.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'reading' => 'required|numeric|min:0',
            'reading_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Get the last reading for this customer
            $lastReading = MeterReading::where('customer_id', $validated['customer_id'])
                ->where('status', 'verified')
                ->orderBy('reading_date', 'desc')
                ->first();

            // Validate that the new reading is greater than the last reading
            if ($lastReading && $validated['reading'] <= $lastReading->reading) {
                throw new \Exception('New reading must be greater than the last reading (' . $lastReading->reading . ')');
            }

            $reading = new MeterReading($validated);
            $reading->read_by = Auth::id();
            $reading->status = 'pending';
            $reading->previous_reading = $lastReading ? $lastReading->reading : 0;
            $reading->save();

            DB::commit();

            return redirect()->route('meter-readings.index')
                ->with('success', 'Meter reading recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error recording meter reading: ' . $e->getMessage());
        }
    }

    public function show(MeterReading $meterReading)
    {
        $meterReading->load(['customer', 'reader']);
        return view('meter-readings.show', compact('meterReading'));
    }

    public function edit(MeterReading $meterReading)
    {
        $customers = Customer::orderBy('name')->get();
        return view('meter-readings.edit', compact('meterReading', 'customers'));
    }

    public function update(Request $request, MeterReading $meterReading)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'reading' => 'required|numeric|min:0',
            'reading_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:pending,verified,disputed',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $meterReading->update($validated);
            DB::commit();

            return redirect()->route('meter-readings.index')
                ->with('success', 'Meter reading updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating meter reading: ' . $e->getMessage());
        }
    }

    public function destroy(MeterReading $meterReading)
    {
        try {
            if ($meterReading->status === 'verified') {
                return back()->with('error', 'Cannot delete a verified meter reading.');
            }

            DB::beginTransaction();
            $meterReading->delete();
            DB::commit();

            return redirect()->route('meter-readings.index')
                ->with('success', 'Meter reading deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting meter reading: ' . $e->getMessage());
        }
    }
} 