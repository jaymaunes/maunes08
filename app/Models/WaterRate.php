<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'minimum_rate',
        'cubic_meter_rate',
        'minimum_cubic_meters',
        'effective_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'minimum_rate' => 'decimal:2',
        'cubic_meter_rate' => 'decimal:2',
        'minimum_cubic_meters' => 'integer',
        'effective_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public static function getCategories()
    {
        return [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial'
        ];
    }

    public function calculateCharge($consumption)
    {
        if ($consumption <= $this->minimum_cubic_meters) {
            return $this->minimum_rate;
        }

        $excess = $consumption - $this->minimum_cubic_meters;
        $excessCharge = $excess * $this->cubic_meter_rate;
        
        return $this->minimum_rate + $excessCharge;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public static function getCurrentRate($category)
    {
        return static::active()
            ->forCategory($category)
            ->orderBy('effective_date', 'desc')
            ->first();
    }
} 