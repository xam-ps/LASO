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
        // create an expense for each cost type with random values and a unique invoice number
        foreach (CostType::all() as $cost_type) {
            Expense::factory()->specificTypeYearsBack($cost_type->id, 0)->create();
            Expense::factory()->specificTypeYearsBack($cost_type->id, 1)->create();
        }
    }
}
