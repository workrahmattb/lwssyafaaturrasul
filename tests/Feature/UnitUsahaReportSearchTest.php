<?php

namespace Tests\Feature;

use App\Livewire\Admin\UnitUsahaReportManager;
use App\Models\UnitUsahaReport;
use Livewire\Livewire;
use Tests\TestCase;

class UnitUsahaReportSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (require database_path('migrations/2026_06_05_000001_create_unit_usaha_reports_table.php'))->up();
    }

    public function test_search_matches_names_and_notes_and_keeps_date_order(): void
    {
        $nameMatch = UnitUsahaReport::create(['nama' => 'SR Bakery', 'tanggal' => '2025-01-01', 'jumlah_uang' => 100000]);
        $noteMatch = UnitUsahaReport::create(['nama' => 'Unit Lain', 'catatan' => 'Kerja sama bakery', 'tanggal' => '2026-01-01', 'jumlah_uang' => 50000]);
        UnitUsahaReport::create(['nama' => 'Toko', 'tanggal' => '2026-02-01', 'jumlah_uang' => 25000]);

        Livewire::test(UnitUsahaReportManager::class)
            ->set('search', ' BAKERY ')
            ->assertViewHas('reports', fn ($rows) => $rows->pluck('id')->all() === [$noteMatch->id, $nameMatch->id])
            ->assertViewHas('totalAmount', fn ($amount) => (float) $amount === 175000.0)
            ->set('search', 'tidak ditemukan')->assertSee('Tidak ada laporan yang sesuai dengan pencarian.')
            ->set('search', '')->assertViewHas('reports', fn ($rows) => $rows->total() === 3);
    }

    public function test_search_returns_to_first_page(): void
    {
        foreach (range(1, 16) as $index) {
            UnitUsahaReport::create(['nama' => 'Bakery '.$index, 'tanggal' => '2026-01-01', 'jumlah_uang' => 1000]);
        }

        Livewire::test(UnitUsahaReportManager::class)
            ->call('gotoPage', 2)
            ->set('search', 'bakery')
            ->assertViewHas('reports', fn ($rows) => $rows->currentPage() === 1 && $rows->total() === 16);
    }
}
