<?php

namespace Tests\Feature;

use App\Livewire\CampaignDonate;
use App\Livewire\DonationFlow;
use App\Models\Campaign;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class DonationFormNoBlurSyncTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Regression: field `amount` di form donasi umum memakai wire:model.blur,
     * yang berpotensi race dengan request goToStep2 — nilai belum tersinkron
     * saat submit pertama → validasi gagal ("pertama tidak, kedua baru bisa").
     * Sekarang memakai wire:model deferred agar nilai dibaca atomik bersama
     * request goToStep2.
     */
    public function test_donation_flow_amount_uses_deferred_wire_model_not_blur()
    {
        $html = Livewire::test(DonationFlow::class)->html();

        $this->assertStringContainsString('wire:model="amount"', $html);
        $this->assertStringNotContainsString('wire:model.blur="amount"', $html);
    }

    /**
     * Regression yang sama untuk form donasi kampanye — verifikasi via render
     * penuh halaman (mount CampaignDonate butuh route slug + kampanye aktif).
     */
    public function test_campaign_donate_amount_uses_deferred_wire_model_not_blur()
    {
        $campaign = Campaign::create([
            'title' => 'Kampanye Blur Sync Test',
            'description' => 'Deskripsi test',
            'target_amount' => 10000000,
            'status' => 'active',
        ]);

        $this->get(route('campaigns.donate', $campaign->slug))
            ->assertOk()
            ->assertSee('wire:model="amount"', false)
            ->assertDontSee('wire:model.blur="amount"', false);
    }

    /**
     * Alur step 1 → step 2 pada DonationFlow harus lolos di percobaan pertama
     * dengan input nominal berformat ribuan (mis. "1.500.000"), dan nominal
     * tersimpan bersih sebagai angka tanpa separator.
     */
    public function test_donation_flow_go_to_step2_works_on_first_submit_with_formatted_amount()
    {
        $bank = PaymentMethod::create([
            'bank_name' => 'BSI Test',
            'account_number' => '1234567890',
            'account_name' => 'Yayasan Test',
        ]);

        Livewire::test(DonationFlow::class)
            ->set('type', 'wakaf_pembangunan')
            ->set('amount', '1.500.000')
            ->set('donatur_name', 'Donatur Test')
            ->set('phone', '081234567890')
            ->set('payment_method_id', $bank->id)
            ->call('goToStep2')
            ->assertHasNoErrors()
            ->assertSet('step', 2)
            ->assertSet('amount', '1500000');
    }

    /**
     * Flow yang sama untuk CampaignDonate: goToStep2 dari nilai berformat
     * ribuan harus lolos validasi di percobaan pertama dan nominal dibersihkan.
     * Kampanye dibuat aktif agar mount() tidak abort.
     */
    public function test_campaign_donate_go_to_step2_works_on_first_submit_with_formatted_amount()
    {
        $bank = PaymentMethod::create([
            'bank_name' => 'BSI Test',
            'account_number' => '1234567890',
            'account_name' => 'Yayasan Test',
        ]);

        $campaign = Campaign::create([
            'title' => 'Kampanye Blur Sync Flow Test',
            'description' => 'Deskripsi test',
            'target_amount' => 10000000,
            'status' => 'active',
        ]);

        Livewire::test(CampaignDonate::class, ['slug' => $campaign->slug])
            ->set('amount', '2.500.000')
            ->set('donatur_name', 'Donatur Kampanye')
            ->set('phone', '081298765432')
            ->set('payment_method_id', $bank->id)
            ->call('goToStep2')
            ->assertHasNoErrors()
            ->assertSet('step', 2)
            ->assertSet('amount', '2500000');
    }
}
