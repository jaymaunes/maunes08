<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::create([
            'meter_number' => '1',
            'first_name' => 'Mark',
            'last_name' => 'Loquias',
            'email' => 'lowkeyasmark@gmail.com',
            'phone' => '09887766',
            'address' => 'Sample Address',
            'connection_type' => 'residential',
            'status' => 'active'
        ]);
    }
} 