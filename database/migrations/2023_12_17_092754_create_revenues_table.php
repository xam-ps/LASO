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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('billing_date');
            $table->date('payment_date')->nullable();
            $table->string('company_name');
            $table->string('invoice_number')->unique();
            $table->decimal('net', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('gross', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
