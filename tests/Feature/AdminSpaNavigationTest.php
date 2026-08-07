<?php

namespace Tests\Feature;

use App\Livewire\Admin\CampaignDonationManager;
use App\Livewire\Admin\CampaignManager;
use App\Livewire\Admin\DonationManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSpaNavigationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_back_to_dashboard_links_use_wire_navigate()
    {
        // Regression: semua tombol "Kembali ke Dashboard" harus memakai wire:navigate
        // agar navigasi admin bersifat SPA (tanpa full page reload).
        $components = [
            CampaignManager::class,
            DonationManager::class,
            CampaignDonationManager::class,
        ];

        foreach ($components as $component) {
            $html = Livewire::test($component)->html();

            preg_match('/<a[^>]*title="Kembali ke Dashboard"[^>]*>/', $html, $match);

            $this->assertNotEmpty($match, "Back-to-dashboard link tidak ditemukan di {$component}");
            $this->assertStringContainsString(
                'wire:navigate',
                $match[0],
                "Back-to-dashboard link di {$component} harus memakai wire:navigate (SPA)"
            );
        }
    }
}
