<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE expenses ADD CONSTRAINT expenses_depreciation_valid CHECK ((cost_type_id = 6 AND depreciation BETWEEN 1 AND 30) OR (cost_type_id <> 6 AND depreciation IS NULL))'
        );
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE expenses DROP CHECK expenses_depreciation_valid');
    }
};
