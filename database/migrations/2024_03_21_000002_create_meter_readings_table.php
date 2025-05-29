<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('reading', 10, 2);
            $table->date('reading_date');
            $table->string('status')->default('pending'); // pending, verified, disputed
            $table->text('notes')->nullable();
            $table->foreignId('read_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meter_readings');
    }
}; 