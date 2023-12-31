<?php

namespace Database\Seeders;

use App\Models\CostType;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create an expense for each cost type with random values and a unique invoice number
        foreach (CostType::all() as $cost_type) {
            $net = rand(10, 100);
            if ($cost_type->id == 6) {
                $depreciation = rand(3, 20);
                $net = rand(1000, 2000);
            } else {
                $depreciation = null;
            }
            $tax = $net * 0.19;
            $gross = $net + $tax;
            Expense::create([
                'billing_date' => now(),
                'payment_date' => now(),
                'supplier_name' => 'Supplier '.$cost_type->short_name,
                'product_name' => 'Product '.$cost_type->short_name,
                'invoice_number' => 'INV'.now()->year.$cost_type->id,
                'net' => $net,
                'tax' => $tax,
                'gross' => $gross,
                'cost_type_id' => $cost_type->id,
                'depreciation' => $depreciation,
            ]);
        }

        foreach (CostType::all() as $cost_type) {
            $net = rand(10, 100);
            if ($cost_type->id == 6) {
                $depreciation = rand(3, 7);
                $net = rand(1000, 2000);
            } else {
                $depreciation = null;
            }
            $tax = $net * 0.19;
            $gross = $net + $tax;
            Expense::create([
                'billing_date' => now()->subYear(),
                'payment_date' => now()->subYear(),
                'supplier_name' => 'Supplier '.$cost_type->short_name,
                'product_name' => 'Product '.$cost_type->short_name,
                'invoice_number' => 'INV'.now()->subYear()->year.$cost_type->id,
                'net' => $net,
                'tax' => $tax,
                'gross' => $gross,
                'cost_type_id' => $cost_type->id,
                'depreciation' => $depreciation,
            ]);
        }
    }
}
