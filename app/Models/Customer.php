<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'connection_type',
        'status'
    ];

    protected $appends = ['full_name'];

    public function meterReadings()
    {
        return $this->hasMany(MeterReading::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestMeterReading()
    {
        return $this->hasOne(MeterReading::class)->latestOfMany();
    }

    public function unpaidBills()
    {
        return $this->bills()->where('status', 'unpaid')->orWhere('status', 'overdue');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function getConnectionTypes()
    {
        return [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial'
        ];
    }

    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'disconnected' => 'Disconnected'
        ];
    }

    public function getTotalUnpaidAmountAttribute()
    {
        return $this->unpaidBills()->sum('amount');
    }
} 