<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get current month's statistics
        $currentMonth = Carbon::now()->startOfMonth();
        
        $monthlyStats = [
            'bills' => Bill::whereMonth('created_at', $currentMonth)->count(),
            'payments' => Payment::whereMonth('created_at', $currentMonth)->sum('amount'),
            'new_customers' => Customer::whereMonth('created_at', $currentMonth)->count(),
            'unpaid_bills' => Bill::where('status', 'unpaid')->count(),
        ];

        // Get recent activities
        $recentBills = Bill::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('bill.customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'monthlyStats',
            'recentBills',
            'recentPayments'
        ));
    }
} 