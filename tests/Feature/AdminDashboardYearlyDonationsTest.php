<?php

namespace Tests\Feature;

use App\Livewire\AdminDashboard;
use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardYearlyDonationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The historical enum conversion uses MySQL-only SQL; these tests use SQLite.
        foreach (glob(database_path('migrations/*.php')) as $migration) {
            if (! str_contains($migration, 'change_donation_types_enum')) {
                (require $migration)->up();
            }
        }

        $this->travelTo(Carbon::parse('2026-11-04'));
        $this->actingAs(User::factory()->create(['is_admin' => true]));
    }

    public function test_yearly_totals_combine_only_approved_donations_and_campaign_donations(): void
    {
        $paymentMethod = PaymentMethod::create([
            'bank_name' => 'Bank Test', 'account_number' => '123', 'account_name' => 'Test',
        ]);
        $campaign = Campaign::create(['title' => 'Test', 'description' => 'Test']);

        foreach (['approved' => 150000, 'pending' => 700000, 'rejected' => 900000] as $status => $amount) {
            Donation::create([
                'trx_id' => 'general-'.$status, 'type' => 'donasi',
                'amount' => $amount, 'status' => $status, 'payment_method_id' => $paymentMethod->id,
            ])->forceFill(['created_at' => '2025-12-31 12:00:00'])->save();
            CampaignDonation::create([
                'trx_id' => 'campaign-'.$status, 'campaign_id' => $campaign->id,
                'donatur_name' => 'Test', 'phone' => '081234567890',
                'amount' => $amount, 'status' => $status, 'payment_method_id' => $paymentMethod->id,
            ])->forceFill(['created_at' => '2026-01-01 12:00:00'])->save();
        }

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('totalCollectedDonations', 300000.0)
            ->assertViewHas('lwsBalance', 300000.0)
            ->assertViewHas('yearlyLabels', ['2024', '2025', '2026'])
            ->assertViewHas('yearlyTotalAmounts', [0.0, 150000.0, 150000.0])
            ->assertSee('Total Donasi Terkumpul per Tahun');
    }

    public function test_yearly_chart_shows_zero_totals_when_there_are_no_donations(): void
    {
        Livewire::test(AdminDashboard::class)
            ->assertViewHas('yearlyTotalAmounts', [0.0, 0.0, 0.0]);
    }

    public function test_dashboard_sums_disbursements_by_category_across_all_periods(): void
    {
        Disbursement::factory()->create(['type' => 'wakaf_pembangunan', 'amount' => 150000, 'disbursed_at' => '2025-01-01']);
        Disbursement::factory()->create(['type' => 'wakaf_pembangunan', 'amount' => 50000, 'disbursed_at' => null]);
        Disbursement::factory()->create(['type' => 'donasi_pendidikan', 'amount' => 75000]);

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('disbursementLabels', ['Wakaf Pembangunan', 'Wakaf Produktif', 'Donasi Pendidikan'])
            ->assertViewHas('disbursementAmounts', [200000.0, 0.0, 75000.0])
            ->assertViewHas('totalDisbursedAmount', 275000.0)
            ->assertSee('Grafik Penyaluran Donasi');
    }

    public function test_dashboard_handles_empty_disbursement_data(): void
    {
        Livewire::test(AdminDashboard::class)
            ->assertViewHas('disbursementAmounts', [0.0, 0.0, 0.0])
            ->assertViewHas('totalDisbursedAmount', 0.0)
            ->assertViewHas('yearlyDisbursementAmounts', [0.0, 0.0, 0.0])
            ->assertSee('Belum ada data penyaluran.');
    }

    public function test_yearly_disbursements_use_disbursement_dates_and_exclude_undated_records(): void
    {
        Disbursement::factory()->create(['amount' => 150000, 'disbursed_at' => '2024-12-31']);
        Disbursement::factory()->create(['amount' => 50000, 'disbursed_at' => '2024-01-01']);
        Disbursement::factory()->create(['amount' => 75000, 'disbursed_at' => '2026-01-01']);
        Disbursement::factory()->create(['amount' => 25000, 'disbursed_at' => null]);

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('yearlyDisbursementAmounts', [200000.0, 0.0, 75000.0])
            ->assertViewHas('undatedDisbursementAmount', 25000.0)
            ->assertSee('Penyaluran per Tahun')
            ->assertSee('belum memiliki tanggal');
    }

    public function test_yearly_disbursements_automatically_include_next_year(): void
    {
        $this->travelTo(Carbon::parse('2027-01-02'));
        Disbursement::factory()->create(['amount' => 100000, 'disbursed_at' => '2027-01-01']);

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('yearlyLabels', ['2024', '2025', '2026', '2027'])
            ->assertViewHas('yearlyDisbursementAmounts', [0.0, 0.0, 0.0, 100000.0]);
    }

    public function test_yearly_disbursements_separate_each_donation_category(): void
    {
        Disbursement::factory()->create(['type' => 'wakaf_pembangunan', 'amount' => 100000, 'disbursed_at' => '2024-01-01']);
        Disbursement::factory()->create(['type' => 'wakaf_pembangunan', 'amount' => 50000, 'disbursed_at' => '2024-02-01']);
        Disbursement::factory()->create(['type' => 'wakaf_produktif', 'amount' => 75000, 'disbursed_at' => '2025-01-01']);
        Disbursement::factory()->create(['type' => 'donasi_pendidikan', 'amount' => 25000, 'disbursed_at' => '2026-01-01']);
        Disbursement::factory()->create(['type' => 'wakaf_produktif', 'amount' => 900000, 'disbursed_at' => null]);

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('yearlyDisbursementsByCategory', [
                'wakaf_pembangunan' => [150000.0, 0.0, 0.0],
                'wakaf_produktif' => [0.0, 75000.0, 0.0],
                'donasi_pendidikan' => [0.0, 0.0, 25000.0],
            ]);
    }

    public function test_lws_balance_adds_unit_income_and_subtracts_all_disbursements(): void
    {
        \App\Models\UnitUsahaReport::create([
            'nama' => 'Unit Test', 'tanggal' => '2026-01-01', 'jumlah_uang' => 500000,
        ]);
        Disbursement::factory()->create(['amount' => 150000, 'disbursed_at' => null]);

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('lwsBalance', 350000.0)
            ->assertViewHas('totalDisbursedAmount', 150000.0)
            ->assertSee('SALDO LWS')->assertSee('Total Unit Usaha')->assertSee('Penyaluran Total');
    }

    public function test_lws_balance_can_be_negative(): void
    {
        Disbursement::factory()->create(['amount' => 100000]);

        Livewire::test(AdminDashboard::class)->assertViewHas('lwsBalance', -100000.0);
    }

    public function test_yearly_unit_income_combines_all_units_by_report_date(): void
    {
        foreach ([
            ['Hasil Keuntungan SR Bakery', '2024-12-31', 100000],
            ['Unit Lain', '2024-01-01', 50000],
            ['Unit Lain', '2026-01-01', 75000],
        ] as [$name, $date, $amount]) {
            \App\Models\UnitUsahaReport::create(['nama' => $name, 'tanggal' => $date, 'jumlah_uang' => $amount]);
        }

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('unitUsahaYearlyAmounts', [150000.0, 0.0, 75000.0])
            ->assertSee('Pemasukan Unit Usaha per Tahun');
    }

    public function test_yearly_unit_income_includes_new_year_with_zero_when_empty(): void
    {
        $this->travelTo(Carbon::parse('2027-01-02'));

        Livewire::test(AdminDashboard::class)
            ->assertViewHas('yearlyLabels', ['2024', '2025', '2026', '2027'])
            ->assertViewHas('unitUsahaYearlyAmounts', [0.0, 0.0, 0.0, 0.0]);
    }
}
