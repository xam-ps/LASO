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
        Schema::create('vatNotice', function (Blueprint $table) {
            $table->id();
            $table->date('notice_date');
            $table->decimal('vat_collected', 10, 2);
            $table->decimal('vat_payed', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vatNotice');
    }
};
