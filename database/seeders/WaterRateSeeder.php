<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaterRate;

class WaterRateSeeder extends Seeder
{
    public function run()
    {
        WaterRate::create([
            'category' => 'residential',
            'minimum_rate' => 150.00,
            'cubic_meter_rate' => 15.00,
            'minimum_cubic_meters' => 10,
            'effective_date' => now(),
            'is_active' => true,
            'description' => 'Standard residential rate'
        ]);

        WaterRate::create([
            'category' => 'commercial',
            'minimum_rate' => 300.00,
            'cubic_meter_rate' => 25.00,
            'minimum_cubic_meters' => 15,
            'effective_date' => now(),
            'is_active' => true,
            'description' => 'Standard commercial rate'
        ]);

        WaterRate::create([
            'category' => 'industrial',
            'minimum_rate' => 500.00,
            'cubic_meter_rate' => 35.00,
            'minimum_cubic_meters' => 20,
            'effective_date' => now(),
            'is_active' => true,
            'description' => 'Standard industrial rate'
        ]);
    }
} 