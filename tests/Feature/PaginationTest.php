<?php

namespace Tests\Feature;

use App\Livewire\Admin\UnitUsahaReportManager;
use App\Livewire\DonationHistory;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\UnitUsahaReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The published Livewire pagination view must use the standard trait
     * method names (nextPage/previousPage) so EVERY component using
     * WithPagination works — not the old custom "next"/"previous" that
     * only existed on DonationHistory (caused MethodNotFoundException).
     */
    public function test_pagination_view_uses_standard_trait_method_names()
    {
        $view = file_get_contents(resource_path('views/vendor/livewire/tailwind.blade.php'));

        $this->assertStringContainsString('wire:click="nextPage"', $view);
        $this->assertStringContainsString('wire:click="previousPage"', $view);
        $this->assertStringNotContainsString('wire:click="next"', $view);
        $this->assertStringNotContainsString('wire:click="previous"', $view);
    }

    /**
     * Regression: clicking "next page" on the unit usaha admin page must
     * work (previously: "Public method [next] not found" → 500 error).
     */
    public function test_unit_usaha_report_pagination_next_page_works()
    {
        // Use a future date so these records always sort FIRST (order: tanggal desc, id desc)
        // → page 1 = LapTest-20..06, page 2 = LapTest-05..01
        for ($i = 1; $i <= 20; $i++) {
            UnitUsahaReport::create([
                'nama' => 'LapTest-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'tanggal' => '2030-01-01',
                'jumlah_uang' => 100000,
            ]);
        }

        Livewire::test(UnitUsahaReportManager::class)
            ->assertOk()
            ->assertSee('LapTest-20')
            ->assertDontSee('LapTest-01')
            ->call('nextPage')
            ->assertOk()
            ->assertSet('paginators.page', 2)
            ->assertSee('LapTest-01')
            ->assertDontSee('LapTest-20')
            ->call('gotoPage', 1)
            ->assertSet('paginators.page', 1)
            ->assertSee('LapTest-20');
    }

    /**
     * DonationHistory (homepage riwayat) must keep working with the standard
     * method names AND still dispatch the scrollToRiwayat event.
     */
    public function test_donation_history_pagination_dispatches_scroll_event()
    {
        $bank = PaymentMethod::create([
            'bank_name' => 'Test Bank',
            'account_number' => '123456',
            'account_name' => 'Test',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            Donation::create([
                'trx_id' => 'TRX-PAG-' . $i,
                'type' => 'wakaf_pembangunan',
                'donatur_name' => 'Donatur ' . $i,
                'amount' => 100000,
                'payment_method_id' => $bank->id,
                'status' => 'approved',
                'is_anonymous' => false,
            ]);
        }

        Livewire::test(DonationHistory::class)
            ->assertOk()
            ->call('nextPage')
            ->assertDispatched('scrollToRiwayat')
            ->assertSet('paginators.page', 2);

        Livewire::test(DonationHistory::class)
            ->call('gotoPage', 2)
            ->assertDispatched('scrollToRiwayat')
            ->assertSet('paginators.page', 2);

        Livewire::test(DonationHistory::class)
            ->call('previousPage')
            ->assertDispatched('scrollToRiwayat')
            ->assertSet('paginators.page', 1);
    }
}
