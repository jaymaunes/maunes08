<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Customer;

class ReportController extends Controller
{
    public function index()
    {
        // Get summary statistics
        $totalCustomers = Customer::count();
        $totalBills = Bill::count();
        $totalPayments = Payment::sum('amount');
        $unpaidBills = Bill::where('status', 'unpaid')->count();
        
        return view('reports.index', compact(
            'totalCustomers',
            'totalBills',
            'totalPayments',
            'unpaidBills'
        ));
    }
} 