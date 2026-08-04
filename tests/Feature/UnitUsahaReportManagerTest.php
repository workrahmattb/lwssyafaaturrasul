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
}
