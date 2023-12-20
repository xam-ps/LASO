<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travel_allowances', function (Blueprint $table) {
            $table->id();
            $table->date('travel_date');
            $table->time('start');
            $table->time('end');
            $table->string('destination');
            $table->string('reason');
            $table->string('company')->nullable();
            $table->integer('distance');
            $table->text('notes')->nullable();
            $table->decimal('refund', 6, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_allowances');
    }
};
