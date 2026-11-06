<?php

namespace Tests\Feature;

use App\Livewire\PublicReportTable;
use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\UnitUsahaReport;
use Livewire\Livewire;
use Tests\TestCase;

class PublicReportsTest extends TestCase
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

    public function test_guests_can_view_dashboard_and_all_charts_without_admin_controls(): void
    {
        $response = $this->get(route('reports.dashboard'));

        $response->assertOk()->assertSee('SALDO LWS')
            ->assertSee('Tren Donasi Perbulan')->assertSee('Tren Donasi Pertahun')
            ->assertSee('Total Donasi Terkumpul per Tahun')->assertSee('Komposisi per Kategori')
            ->assertSee('Tren Pemasukan SR Bakery Perbulan')->assertSee('Komposisi per Unit Usaha')
            ->assertSee('Pemasukan Unit Usaha per Tahun')->assertSee('Penyaluran per Tahun')
            ->assertDontSee('Logout')->assertDontSee('/admin/', false);
        $this->get(route('donation'))->assertOk()->assertSee(route('reports.dashboard'), false);
    }

    public function test_public_donations_show_only_approved_amounts_without_personal_data(): void
    {
        $payment = PaymentMethod::create(['bank_name' => 'Test', 'account_number' => '123', 'account_name' => 'Test']);
        foreach (['approved', 'pending', 'rejected'] as $status) {
            Donation::create([
                'trx_id' => 'private-'.$status, 'type' => 'donasi', 'amount' => $status === 'approved' ? 100000 : 999999,
                'payment_method_id' => $payment->id, 'status' => $status,
                'donatur_name' => 'IdentitasRahasia', 'phone' => '081234567890',
                'proof_of_transfer' => 'private-proof.jpg', 'is_anonymous' => true,
            ]);
        }

        $this->get(route('reports.table', ['section' => 'donasi-umum']))
            ->assertOk()->assertSee('100.000')->assertSee('Hamba Allah')->assertDontSee('999.999')
            ->assertDontSee('IdentitasRahasia')->assertDontSee('081234567890')
            ->assertDontSee('private-proof.jpg')->assertDontSee('private-approved');
        Livewire::test(PublicReportTable::class, ['section' => 'donasi-umum'])
            ->assertViewHas('rows', fn ($rows) => $rows->total() === 1 && ! array_key_exists('phone', $rows->first()->getAttributes()));
    }

    public function test_public_disbursements_support_search_and_pagination_without_write_controls(): void
    {
        Disbursement::factory()->count(16)->create(['description' => 'Renovasi sekolah', 'disbursed_at' => '2026-11-01']);
        Disbursement::factory()->create(['description' => 'Modal toko', 'disbursed_at' => null]);

        $this->get(route('reports.table', ['section' => 'penyaluran']))->assertOk()->assertSee('Renovasi sekolah')
            ->assertDontSee('wire:click="delete', false)->assertDontSee('wire:click="edit', false)->assertDontSee('Export Excel');
        Livewire::test(PublicReportTable::class, ['section' => 'penyaluran'])
            ->call('gotoPage', 2)->set('search', 'SEKOLAH')
            ->assertViewHas('rows', fn ($rows) => $rows->total() === 16 && $rows->currentPage() === 1)
            ->set('search', 'tidak ditemukan')->assertViewHas('rows', fn ($rows) => $rows->total() === 0);
    }

    public function test_guests_can_read_and_search_unit_reports(): void
    {
        UnitUsahaReport::create(['nama' => 'SR Bakery', 'tanggal' => '2026-11-01', 'jumlah_uang' => 250000, 'catatan' => 'Keuntungan']);

        $this->get(route('reports.table', ['section' => 'unit-usaha']))->assertOk()->assertSee('SR Bakery')->assertSee('250.000');
        Livewire::test(PublicReportTable::class, ['section' => 'unit-usaha'])
            ->set('search', 'keuntungan')->assertViewHas('rows', fn ($rows) => $rows->total() === 1);
        $this->get('/laporan/data/invalid')->assertNotFound();
        $this->get(route('admin.penyaluran'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.unit-usaha'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.manage'))->assertRedirect(route('admin.login'));
    }

    public function test_wakaf_table_shows_non_anonymous_donor_names(): void
    {
        $payment = PaymentMethod::create(['bank_name' => 'Test', 'account_number' => '123', 'account_name' => 'Test']);
        Donation::create([
            'trx_id' => 'public-name', 'type' => 'donasi', 'amount' => 100000,
            'payment_method_id' => $payment->id, 'status' => 'approved',
            'donatur_name' => 'Ahmad Wakif', 'is_anonymous' => false,
        ]);

        $this->get(route('reports.table', ['section' => 'donasi-umum']))
            ->assertOk()->assertSee('Nama Donatur')->assertSee('Ahmad Wakif');
    }

    public function test_wakaf_search_uses_displayed_donor_names_and_protects_anonymous_names(): void
    {
        $payment = PaymentMethod::create(['bank_name' => 'Test', 'account_number' => '123', 'account_name' => 'Test']);
        foreach ([['Ahmad Wakif', false, 'approved'], ['Rahasia Wakif', true, 'approved'], ['Ahmad Pending', false, 'pending']] as $index => [$name, $anonymous, $status]) {
            Donation::create([
                'trx_id' => 'search-'.$index, 'type' => 'donasi', 'amount' => 100000,
                'payment_method_id' => $payment->id, 'status' => $status,
                'donatur_name' => $name, 'is_anonymous' => $anonymous,
            ]);
        }

        Livewire::test(PublicReportTable::class, ['section' => 'donasi-umum'])
            ->set('search', ' AHMAD ')
            ->assertViewHas('rows', fn ($rows) => $rows->total() === 1 && $rows->first()->donatur_name === 'Ahmad Wakif')
            ->set('search', 'Rahasia')->assertViewHas('rows', fn ($rows) => $rows->total() === 0)
            ->set('search', 'Hamba Allah')->assertViewHas('rows', fn ($rows) => $rows->total() === 1 && $rows->first()->donatur_name === 'Hamba Allah')
            ->set('search', 'donasi')->assertViewHas('rows', fn ($rows) => $rows->total() === 0)
            ->set('search', '')->assertViewHas('rows', fn ($rows) => $rows->total() === 2);
    }
}
