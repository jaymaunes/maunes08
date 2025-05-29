<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reading',
        'previous_reading',
        'reading_date',
        'status',
        'notes',
        'read_by'
    ];

    protected $casts = [
        'reading_date' => 'date',
        'reading' => 'decimal:2',
        'previous_reading' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public static function getStatuses()
    {
        return [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'disputed' => 'Disputed',
            'billed' => 'Billed'
        ];
    }

    public function getConsumptionAttribute()
    {
        return $this->reading - $this->previous_reading;
    }
} 