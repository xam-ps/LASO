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
        $expenseAssert->residualValue = 440;

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
        $expenseAssert->residualValue = 360;

        AssetController::calcDepreciationValues($expense, 2024);

        $this->assertEquals($expenseAssert, $expense);
    }

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

    public function test_residual_value_after_depreciation_period(): void
    {
        $expense = new Expense;
        $expense->payment_date = '2020-07-01';
        $expense->net = 800;
        $expense->depreciation = 4;

        $expenseAssert = new Expense;
        $expenseAssert->payment_date = '2020-07-01';
        $expenseAssert->net = 800;
        $expenseAssert->depreciation = 4;
        $expenseAssert->firstYear = 100;
        $expenseAssert->middleYear = 200;
        $expenseAssert->lastYear = 100;

        $expenseAssert->yearsInUse = 0;
        $expenseAssert->percUsed = 0;
        $expenseAssert->residualValue = 800;
        AssetController::calcDepreciationValues($expense, 2020);
        $this->assertEquals($expenseAssert, $expense);

        $expenseAssert->yearsInUse = 1;
        $expenseAssert->percUsed = 25;
        $expenseAssert->residualValue = 700;
        AssetController::calcDepreciationValues($expense, 2021);
        $this->assertEquals($expenseAssert, $expense);

        $expenseAssert->yearsInUse = 2;
        $expenseAssert->percUsed = 50;
        $expenseAssert->residualValue = 500;
        AssetController::calcDepreciationValues($expense, 2022);
        $this->assertEquals($expenseAssert, $expense);

        $expenseAssert->yearsInUse = 3;
        $expenseAssert->percUsed = 75;
        $expenseAssert->residualValue = 300;
        AssetController::calcDepreciationValues($expense, 2023);
        $this->assertEquals($expenseAssert, $expense);

        $expenseAssert->yearsInUse = 4;
        $expenseAssert->percUsed = 100;
        $expenseAssert->residualValue = 100;
        AssetController::calcDepreciationValues($expense, 2024);
        $this->assertEquals($expenseAssert, $expense);

        $expenseAssert->yearsInUse = 5;
        $expenseAssert->percUsed = 125;
        $expenseAssert->residualValue = 0;
        AssetController::calcDepreciationValues($expense, 2025);
        $this->assertEquals($expenseAssert, $expense);

        $this->assertEquals($expenseAssert, $expense);
    }
}
