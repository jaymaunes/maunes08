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
} 