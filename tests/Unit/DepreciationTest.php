<?php

namespace Tests\Unit;

use App\Http\Controllers\AssetController;
use App\Models\Expense;
use PHPUnit\Framework\TestCase;

class DepreciationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_depreciation_values(): void
    {
        $expense = new Expense();
        $expense->payment_date = '2020-05-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $firstYear = 40;
        $middleYear = 60;
        $lastYear = 20;
        $yearsInUse = 3;
        $percUsed = 30;

        print_r($expense);

        AssetController::calcDepreciationValues($expense, 2023);

        $this->assertEquals($firstYear, $expense->firstYear);
        $this->assertEquals($middleYear, $expense->middleYear);
        $this->assertEquals($lastYear, $expense->lastYear);
        $this->assertEquals($yearsInUse, $expense->yearsInUse);
        $this->assertEquals($percUsed, $expense->percUsed);
    }

    public function test_depreciation_values_first_year_full(): void
    {
        $expense = new Expense();
        $expense->payment_date = '2020-01-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $firstYear = 60;
        $middleYear = 60;
        $lastYear = 0;
        $yearsInUse = 4;
        $percUsed = 40;

        print_r($expense);

        AssetController::calcDepreciationValues($expense, 2024);

        $this->assertEquals($firstYear, $expense->firstYear);
        $this->assertEquals($middleYear, $expense->middleYear);
        $this->assertEquals($lastYear, $expense->lastYear);
        $this->assertEquals($yearsInUse, $expense->yearsInUse);
        $this->assertEquals($percUsed, $expense->percUsed);
    }

    //Create a test for AssetController::calcAfaForYear
    public function test_calc_afa_for_year(): void
    {
        $expense = new Expense();
        $expense->payment_date = '2020-07-01';
        $expense->net = 600;
        $expense->depreciation = 10;

        $expense2 = new Expense();
        $expense2->payment_date = '2023-05-01';
        $expense2->net = 360;
        $expense2->depreciation = 3;

        $expensesWithTypeAfa = [$expense, $expense2];

        $year = 2023;

        [$afaSum, $afaThisYear] = AssetController::calcAfaForYear($expensesWithTypeAfa, $year);

        $this->assertEquals(140, $afaSum);
        $this->assertEquals(360, $afaThisYear);
    }
}
