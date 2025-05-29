<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('meter_reading_id')->constrained()->onDelete('cascade');
            $table->string('bill_number')->unique();
            $table->decimal('consumption', 10, 2);
            $table->decimal('rate_amount', 10, 2);
            $table->decimal('amount', 10, 2);  // Base amount before additional charges
            $table->decimal('minimum_charge', 10, 2);
            $table->decimal('additional_charges', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->date('billing_date');
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'paid', 'overdue', 'cancelled'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
}; 