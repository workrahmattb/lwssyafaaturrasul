<?php

namespace Tests\Feature;

use App\Livewire\Admin\DisbursementManager;
use App\Models\Disbursement;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DisbursementManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (require database_path('migrations/0001_01_01_000000_create_users_table.php'))->up();
        (require database_path('migrations/2026_02_19_013211_add_is_admin_to_users_table.php'))->up();
        (require glob(database_path('migrations/*create_disbursements_table.php'))[0])->up();
        (require glob(database_path('migrations/*add_disbursed_at_to_disbursements_table.php'))[0])->up();
    }

    public function test_admin_can_create_read_update_and_delete_disbursements(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $component = Livewire::test(DisbursementManager::class)
            ->call('openCreateForm')
            ->set('disbursed_at', '2026-11-01')
            ->set('description', 'Pembangunan ruang kelas')
            ->set('amount', '1.500.000')
            ->set('type', 'wakaf_pembangunan')
            ->call('save')->assertHasNoErrors()
            ->assertSet('showForm', false)
            ->assertSee('Pembangunan ruang kelas');

        $disbursement = Disbursement::firstOrFail();
        $this->assertSame('2026-11-01', $disbursement->disbursed_at->format('Y-m-d'));
        $this->assertDatabaseHas('disbursements', ['id' => $disbursement->id, 'amount' => 1500000, 'type' => 'wakaf_pembangunan']);

        $component->call('edit', $disbursement->id)
            ->assertSet('amount', '1.500.000')
            ->assertSet('disbursed_at', '2026-11-01')
            ->set('disbursed_at', '2026-11-04')
            ->assertSet('description', 'Pembangunan ruang kelas')
            ->set('description', 'Modal unit usaha')
            ->set('amount', '2.500.000,50')
            ->set('type', 'wakaf_produktif')
            ->call('save')->assertHasNoErrors()->assertSee('Modal unit usaha');

        $this->assertDatabaseHas('disbursements', ['id' => $disbursement->id, 'amount' => 2500000.50, 'type' => 'wakaf_produktif']);
        $this->assertSame('2026-11-04', $disbursement->fresh()->disbursed_at->format('Y-m-d'));
        $component->call('delete', $disbursement->id)->assertSee('Belum ada data penyaluran');
        $this->assertModelMissing($disbursement);
    }

    public function test_invalid_fields_cannot_be_saved(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(DisbursementManager::class)
            ->set('amount', '-100')
            ->set('type', 'invalid')
            ->call('save')->assertHasErrors(['description', 'amount', 'type', 'disbursed_at'])
            ->set('disbursed_at', '2026-02-30')->call('save')->assertHasErrors(['disbursed_at']);

        $this->assertDatabaseCount('disbursements', 0);
    }

    public function test_education_donations_are_accepted_and_zero_amounts_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(DisbursementManager::class)
            ->set('disbursed_at', '2026-11-04')
            ->set('description', 'Bantuan pendidikan')
            ->set('type', 'donasi_pendidikan')
            ->set('amount', '0')->call('save')->assertHasErrors(['amount'])
            ->set('amount', '100000')->call('save')->assertHasNoErrors();

        $this->assertDatabaseHas('disbursements', ['type' => 'donasi_pendidikan', 'amount' => 100000]);
    }

    public function test_non_admins_cannot_access_the_page_or_component(): void
    {
        $this->get(route('admin.penyaluran'))->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create(['is_admin' => false]));
        $this->get(route('admin.penyaluran'))->assertRedirect(route('admin.login'));
        Livewire::test(DisbursementManager::class)->assertForbidden();
    }

    public function test_admin_page_lists_existing_disbursements(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Disbursement::factory()->create(['description' => 'Penyaluran terdaftar']);

        $this->get(route('admin.penyaluran'))->assertOk()->assertSee('Penyaluran terdaftar');
    }
}
