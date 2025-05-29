<?php

namespace App\Http\Controllers;

use App\Models\WaterRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterRateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $rates = WaterRate::orderBy('category')
            ->orderBy('effective_date', 'desc')
            ->paginate(10);

        $stats = [
            'active_rates' => WaterRate::active()->count(),
            'categories' => WaterRate::select('category')
                ->distinct()
                ->pluck('category')
                ->map(function($category) {
                    return [
                        'name' => ucfirst($category),
                        'count' => WaterRate::where('category', $category)->count(),
                        'active' => WaterRate::where('category', $category)->active()->first()
                    ];
                })
        ];

        return view('water-rates.index', compact('rates', 'stats'));
    }

    public function create()
    {
        $categories = ['residential', 'commercial', 'industrial'];
        return view('water-rates.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:residential,commercial,industrial',
            'minimum_rate' => 'required|numeric|min:0',
            'cubic_meter_rate' => 'required|numeric|min:0',
            'minimum_cubic_meters' => 'required|integer|min:0',
            'effective_date' => 'required|date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        // If this rate is being set as active, deactivate other rates in the same category
        if ($request->boolean('is_active')) {
            WaterRate::where('category', $validated['category'])
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        WaterRate::create($validated);

        return redirect()
            ->route('water-rates.index')
            ->with('success', 'Water rate created successfully.');
    }

    public function show(WaterRate $waterRate)
    {
        return view('water-rates.show', compact('waterRate'));
    }

    public function edit(WaterRate $waterRate)
    {
        $categories = ['residential', 'commercial', 'industrial'];
        return view('water-rates.edit', compact('waterRate', 'categories'));
    }

    public function update(Request $request, WaterRate $waterRate)
    {
        $validated = $request->validate([
            'category' => 'required|in:residential,commercial,industrial',
            'minimum_rate' => 'required|numeric|min:0',
            'cubic_meter_rate' => 'required|numeric|min:0',
            'minimum_cubic_meters' => 'required|integer|min:0',
            'effective_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle the is_active status separately since it's a checkbox
        $newActiveStatus = $request->has('is_active');
        
        // If this rate is being set as active and it wasn't active before
        if ($newActiveStatus && !$waterRate->is_active) {
            // Deactivate other rates in the same category
            WaterRate::where('category', $validated['category'])
                ->where('is_active', true)
                ->where('id', '!=', $waterRate->id)
                ->update(['is_active' => false]);
        }

        // Update the water rate with validated data and the new active status
        $waterRate->update(array_merge($validated, ['is_active' => $newActiveStatus]));

        return redirect()
            ->route('water-rates.show', $waterRate)
            ->with('success', 'Water rate updated successfully.');
    }

    public function destroy(WaterRate $waterRate)
    {
        // Don't allow deletion of active rates
        if ($waterRate->is_active) {
            return redirect()
                ->route('water-rates.show', $waterRate)
                ->with('error', 'Cannot delete an active water rate. Please set another rate as active first.');
        }

        $waterRate->delete();

        return redirect()
            ->route('water-rates.index')
            ->with('success', 'Water rate deleted successfully.');
    }
} 