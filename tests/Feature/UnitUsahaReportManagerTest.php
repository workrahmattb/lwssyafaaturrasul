<?php

namespace Tests\Feature;

use App\Livewire\Admin\UnitUsahaReportManager;
use App\Models\UnitUsahaReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class UnitUsahaReportManagerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_then_edit_and_update_flow()
    {
        // Simulate: user clicks "Tambah Laporan" and submits
        Livewire::test(UnitUsahaReportManager::class)
            ->set('nama', 'Koperasi Sejahtera')
            ->set('tanggal', '2026-08-04')
            ->set('jumlah_uang', 500000)
            ->set('catatan', 'Laporan bulanan')
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('showCreateForm', false);

        $report = UnitUsahaReport::where('nama', 'Koperasi Sejahtera')->first();
        $this->assertNotNull($report);

        // Simulate: user clicks Edit on the new report - modal must open with the data
        $component = Livewire::test(UnitUsahaReportManager::class)
            ->call('edit', $report->id);

        $component
            ->assertSet('showEditForm', true)
            ->assertSet('nama', 'Koperasi Sejahtera')
            ->assertSet('tanggal', '2026-08-04')
            ->assertSet('jumlah_uang', '500.000')
            ->assertSeeHtml('Edit Laporan Unit Usaha');

        // Simulate: user changes the name and clicks Update
        $component
            ->set('nama', 'Koperasi Sejahtera Baru')
            ->call('update')
            ->assertHasNoErrors()
            ->assertSet('showEditForm', false);

        $this->assertSame('Koperasi Sejahtera Baru', $report->fresh()->nama);
    }

    public function test_formatted_thousand_input_is_cleaned_before_save()
    {
        // User types "1.500.000" (with thousand separators) in the Jumlah Uang field
        Livewire::test(UnitUsahaReportManager::class)
            ->set('nama', 'Toko Maju')
            ->set('tanggal', '2026-08-04')
            ->set('jumlah_uang', '1.500.000')
            ->set('catatan', 'tes')
            ->call('create')
            ->assertHasNoErrors();

        $report = UnitUsahaReport::where('nama', 'Toko Maju')->first();
        $this->assertNotNull($report);
        $this->assertSame('1500000.00', (string) $report->jumlah_uang);
    }

    public function test_create_saves_report_on_first_submit_without_blur_sync()
    {
        // Regression: the Jumlah Uang field used wire:model.blur, which raced with
        // wire:submit - on the first click the value wasn't synced yet, so the report
        // was only saved on the second submit. Now the value is read atomically with
        // the create() request (deferred wire:model), so a first submit must succeed.
        //
        // Note: the original race is client-side (blur sync request vs submit request),
        // so this test guards the fix from both sides:
        // 1. Markup must use deferred wire:model, NOT wire:model.blur (re-adding .blur fails).
        // 2. The full open -> fill -> create flow must persist the report in one shot.
        $html = Livewire::test(UnitUsahaReportManager::class)
            ->call('openCreateForm')
            ->assertSet('showCreateForm', true)
            ->html();

        $this->assertStringContainsString('wire:model="jumlah_uang"', $html);
        $this->assertStringNotContainsString('wire:model.blur="jumlah_uang"', $html);

        Livewire::test(UnitUsahaReportManager::class)
            ->call('openCreateForm')
            ->set('nama', 'Unit Baru')
            ->set('tanggal', '2026-08-05')
            ->set('jumlah_uang', '2.750.000')
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('showCreateForm', false);

        $report = UnitUsahaReport::where('nama', 'Unit Baru')->first();
        $this->assertNotNull($report);
        $this->assertSame('2750000.00', (string) $report->jumlah_uang);
    }
}
