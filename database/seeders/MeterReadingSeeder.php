<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeterReading;
use App\Models\Customer;
use App\Models\User;

class MeterReadingSeeder extends Seeder
{
    public function run()
    {
        $customer = Customer::where('email', 'lowkeyasmark@gmail.com')->first();
        $user = User::first();

        if ($customer && $user) {
            MeterReading::create([
                'customer_id' => $customer->id,
                'reading' => 341231.00,
                'previous_reading' => 0.00,
                'reading_date' => '2025-05-29',
                'status' => 'pending',
                'notes' => null,
                'read_by' => $user->id
            ]);
        }
    }
} 