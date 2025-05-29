<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meter_number' => 'required|unique:customers',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'required',
            'address' => 'required',
            'connection_type' => 'required|in:residential,commercial,industrial',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['meterReadings' => function($query) {
            $query->latest()->take(12);
        }, 'bills' => function($query) {
            $query->latest()->take(12);
        }]);
        
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'meter_number' => 'required|unique:customers,meter_number,' . $customer->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required',
            'address' => 'required',
            'connection_type' => 'required|in:residential,commercial,industrial',
            'status' => 'required|in:active,inactive,disconnected',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
} 