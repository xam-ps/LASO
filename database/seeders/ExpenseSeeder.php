<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expense::create([
            'billing_date' => now(),
            'payment_date' => now(),
            'supplier_name' => 'ABC Inc.',
            'product_name' => 'Business Trip',
            'invoice_number' => 'INV123',
            'net' => 1000,
            'tax' => 200,
            'gross' => 1200,
            'cost_type_id' => 1,
        ]);

        Expense::create([
            'billing_date' => now(),
            'payment_date' => now(),
            'supplier_name' => 'XYZ Ltd.',
            'product_name' => 'Office Supplies Purchase',
            'invoice_number' => 'INV456',
            'net' => 500,
            'tax' => 100,
            'gross' => 600,
            'cost_type_id' => 2,
        ]);
    }
}
