<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use App\Models\VatNotice;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MassAssignmentTest extends TestCase
{
    public function test_model_fillable_definitions_are_well_formed(): void
    {
        foreach (glob(app_path('Models/*.php')) as $modelPath) {
            $modelClass = 'App\\Models\\'.pathinfo($modelPath, PATHINFO_FILENAME);
            $fillable = (new $modelClass)->getFillable();

            $this->assertSame(
                array_values(array_unique($fillable)),
                $fillable,
                "The {$modelClass} fillable list contains duplicate fields.",
            );

            foreach ($fillable as $field) {
                $this->assertMatchesRegularExpression(
                    '/^[a-z][a-z0-9_]*$/',
                    $field,
                    "The {$modelClass} fillable entry is not a single column name: {$field}",
                );
            }
        }
    }

    #[DataProvider('modelAttributes')]
    public function test_domain_models_accept_expected_mass_assignable_attributes(string $modelClass, array $attributes): void
    {
        $model = new $modelClass;
        $model->fill($attributes);

        $this->assertSame($attributes, $model->getAttributes());
    }

    public static function modelAttributes(): array
    {
        return [
            'expense' => [
                Expense::class,
                [
                    'billing_date' => '2026-01-01',
                    'payment_date' => '2026-01-02',
                    'supplier_name' => 'Supplier',
                    'product_name' => 'Product',
                    'invoice_number' => 'EXP-1',
                    'net' => 100,
                    'tax' => 19,
                    'gross' => 119,
                    'cost_type_id' => 1,
                    'depreciation' => null,
                ],
            ],
            'revenue' => [
                Revenue::class,
                [
                    'billing_date' => '2026-01-01',
                    'payment_date' => '2026-01-02',
                    'company_name' => 'Customer',
                    'invoice_number' => 'REV-1',
                    'net' => 100,
                    'tax' => 19,
                    'gross' => 119,
                ],
            ],
            'travel allowance' => [
                TravelAllowance::class,
                [
                    'travel_date' => '2026-01-01',
                    'start' => '09:00',
                    'end' => '10:00',
                    'destination' => 'Destination',
                    'reason' => 'Meeting',
                    'company' => 'Customer',
                    'distance' => 42,
                    'notes' => 'Notes',
                    'refund' => 12.60,
                ],
            ],
            'VAT notice' => [
                VatNotice::class,
                [
                    'notice_date' => '2026-01-01',
                    'vat_received' => 100,
                    'vat_paid' => 50,
                ],
            ],
        ];
    }
}
