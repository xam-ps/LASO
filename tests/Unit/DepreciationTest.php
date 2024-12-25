<?php

namespace Tests\Unit;

use App\Http\Controllers\AssetController;
use App\Models\Expense;
use PHPUnit\Framework\TestCase;

class DepreciationTest extends TestCase
{
    public function test_depreciation_values(): void
    {
        $expense = new Expense;
        $expense->payment_date = '2020-05-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $expenseAssert = new Expense;
        $expenseAssert->payment_date = '2020-05-01';
        $expenseAssert->net = 600;
        $expenseAssert->depreciation = 10;
        $expenseAssert->firstYear = 40;
        $expenseAssert->middleYear = 60;
        $expenseAssert->lastYear = 20;
        $expenseAssert->yearsInUse = 3;
        $expenseAssert->percUsed = 30;

        AssetController::calcDepreciationValues($expense, 2023);

        $this->assertEquals($expenseAssert, $expense);
    }

    public function test_depreciation_values_first_year_full_last_year_none(): void
    {
        $expense = new Expense;
        $expense->payment_date = '2020-01-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $expenseAssert = new Expense;
        $expenseAssert->payment_date = '2020-01-01';
        $expenseAssert->net = 600;
        $expenseAssert->depreciation = 10;
        $expenseAssert->firstYear = 60;
        $expenseAssert->middleYear = 60;
        $expenseAssert->lastYear = 0;
        $expenseAssert->yearsInUse = 4;
        $expenseAssert->percUsed = 40;

        AssetController::calcDepreciationValues($expense, 2024);

        $this->assertEquals($expenseAssert, $expense);
    }

    //Create a test for AssetController::calcAfaForYear
    public function test_calc_afa_for_year(): void
    {
        $expense = new Expense;
        $expense->payment_date = '2020-07-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $expense2 = new Expense;
        $expense2->payment_date = '2023-05-01';
        $expense2->net = 360;
        $expense2->depreciation = 3;

        $expensesWithTypeAfa = [$expense, $expense2];

        $year = 2023;

        $assert = AssetController::calcAfaForYear($expensesWithTypeAfa, $year);

        $this->assertEquals(140, $assert);
    }
}
