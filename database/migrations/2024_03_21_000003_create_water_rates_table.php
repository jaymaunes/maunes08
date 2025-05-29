<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_rates', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // residential, commercial, industrial
            $table->decimal('minimum_rate', 10, 2);
            $table->decimal('cubic_meter_rate', 10, 2);
            $table->integer('minimum_cubic_meters');
            $table->date('effective_date');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_rates');
    }
}; 