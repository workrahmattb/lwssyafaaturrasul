<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignDetail extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $campaign = Campaign::where('slug', $this->slug)->first();

        if (!$campaign) {
            abort(404, 'Kampanye tidak ditemukan');
        }

        $recentDonations = $campaign->activeDonations()
            ->with('paymentMethod')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.campaign-detail', [
            'campaign' => $campaign,
            'recentDonations' => $recentDonations,
        ])->layout('components.layouts.campaign');
    }
}
