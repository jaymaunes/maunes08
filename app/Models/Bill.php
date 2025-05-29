<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'meter_reading_id',
        'amount',
        'consumption',
        'rate',
        'bill_date',
        'due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'consumption' => 'decimal:2',
        'rate' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function meterReading()
    {
        return $this->belongsTo(MeterReading::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function waterRate()
    {
        return $this->belongsTo(WaterRate::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && now()->greaterThan($this->due_date);
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->payments()->sum('amount');
    }

    public static function getStatuses()
    {
        return [
            'unpaid' => 'Unpaid',
            'partially_paid' => 'Partially Paid',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled'
        ];
    }
} 