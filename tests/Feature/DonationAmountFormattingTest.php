<?php

namespace Tests\Feature;

use App\Livewire\DonationFlow;
use App\Models\PaymentMethod;
use Livewire\Livewire;
use Tests\TestCase;

class DonationAmountFormattingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (glob(database_path('migrations/*.php')) as $migration) {
            if (! str_contains($migration, 'change_donation_types_enum')) {
                (require $migration)->up();
            }
        }
    }

    public function test_formatted_amount_survives_other_field_updates_and_advances_on_first_submit(): void
    {
        $bank = PaymentMethod::create(['bank_name' => 'BSI Test', 'account_number' => '1234567890', 'account_name' => 'Test']);

        Livewire::test(DonationFlow::class)
            ->set('amount', '1.500.000')
            ->set('donatur_name', 'Donatur Test')
            ->set('phone', '081234567890')
            ->set('payment_method_id', $bank->id)
            ->assertSet('amount', '1500000')
            ->assertSeeHtml('x-effect="window.formatRupiahInput($el, $wire.amount)"')
            ->call('goToStep2')->assertHasNoErrors()->assertSet('step', 2)
            ->call('backToStep1')->assertSet('step', 1)->assertSet('amount', '1500000');
    }
}
