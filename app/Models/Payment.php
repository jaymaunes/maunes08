<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'customer_id',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'received_by',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer'
        ];
    }
} 