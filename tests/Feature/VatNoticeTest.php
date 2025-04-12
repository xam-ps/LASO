<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\User;
use App\Models\VatNotice;
use Illuminate\Support\Number;
use Tests\TestCase;

class VatNoticeTest extends TestCase
{
    public function test_vat_notice_page_is_loaded(): void
    {
        $user = User::factory()->create();

        $vatNoticePage = $this->actingAs($user)
            ->get('/vat-notice');

        $vatNoticePage->assertSeeInOrder([
            'Zu meldende steuerpflichtige Umsätze',
            'Gezahlte Steuern Gesamt',
            'Zu meldende Steuereinnahmen',
            'Zu meldende Steuerzahlungen',
        ]);
        $vatNoticePage->assertStatus(200);
    }

    public function test_vat_notice_page_calcs_total_tax(): void
    {
        $user = User::factory()->createOne();
        $rev1 = Revenue::factory()->create();
        $rev2 = Revenue::factory()->create();
        $exp1 = Expense::factory()->create();
        $exp2 = Expense::factory()->create();
        $totalReceivedTax = $rev1->tax + $rev2->tax;
        $totalPaidTax = $exp1->tax + $exp2->tax;

        $vatNoticePage = $this->actingAs($user)
            ->get('/vat-notice');

        $vatNoticePage->assertSee(Number::currency($totalReceivedTax, in: 'EUR', locale: 'de'));
        $vatNoticePage->assertSee(Number::currency($totalPaidTax, in: 'EUR', locale: 'de'));
        $vatNoticePage->assertStatus(200);

        $rev1->delete();
        $rev2->delete();
        $exp1->delete();
        $exp2->delete();
    }

    public function test_vat_notice_page_calcs_remaining_tax(): void
    {
        $user = User::factory()->createOne();
        $rev1 = Revenue::factory()->create();
        $rev2 = Revenue::factory()->create();
        $exp1 = Expense::factory()->create();
        $exp2 = Expense::factory()->create();

        $totalReceivedTax = $rev1->tax + $rev2->tax; // Steuereinnahmen Gesamt
        $totalPaidTax = $exp1->tax + $exp2->tax; // Gezahlte Steuern Gesamt

        $vatNotice1 = VatNotice::factory()->create();
        $vatNotice2 = VatNotice::factory()->create();

        $remainingReceivedVat = $totalReceivedTax - ($vatNotice1->vat_received + $vatNotice2->vat_received);
        $remainingReceivedVat = round($remainingReceivedVat * 100 / 19, 0) * 19 / 100; // Elster only let you use non decimal numbers for the net revenue
        $remainingPaidVat = $totalPaidTax - ($vatNotice1->vat_paid + $vatNotice2->vat_paid);

        $vatNoticePage = $this->actingAs($user)
            ->get('/vat-notice');

        $vatNoticePage->assertSee(Number::currency($remainingReceivedVat, in: 'EUR', locale: 'de'));
        $vatNoticePage->assertSee(Number::currency($remainingPaidVat, in: 'EUR', locale: 'de'));
        $vatNoticePage->assertStatus(200);

        $exp1->delete();
        $exp2->delete();
        $vatNotice1->delete();
        $vatNotice2->delete();
    }

    public function test_vat_notice_page_shows_notices(): void
    {
        $user = User::factory()->createOne();
        $vatNotice = VatNotice::factory()->create();

        $vatNoticePage = $this->actingAs($user)
            ->get('/vat-notice');

        $vatNoticePage->assertSee(Number::currency($vatNotice->vat_paid, in: 'EUR', locale: 'de'));
        $vatNoticePage->assertStatus(200);

        $vatNotice->delete();
    }

    public function test_create_vat_notice_page_is_loaded(): void
    {
        $user = User::factory()->create();

        $vatNoticePage = $this->actingAs($user)
            ->get('/vat-notice/create');

        $vatNoticePage->assertSeeInOrder([
            'Meldedatum',
        ]);
        $vatNoticePage->assertStatus(200);
    }

    public function test_deleting_vat_notice_is_working(): void
    {
        $user = User::factory()->createOne();
        $vatNotice = VatNotice::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/vat-notice/'.$vatNotice->id);

        $this->assertDatabaseMissing('vat_notices', [
            'id' => $vatNotice->id,
        ]);
        $response->assertRedirect('/vat-notice');

        $user->delete();
        $vatNotice->delete();
    }
}
