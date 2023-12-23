<?php

use App\Models\CostType;
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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('billing_date');
            $table->date('payment_date')->nullable();
            $table->string('supplier_name');
            $table->string('product_name');
            $table->string('invoice_number');
            $table->decimal('net', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('gross', 11, 2);
            $table->foreignIdFor(CostType::class)->constrained();
            $table->integer('depreciation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
