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

        if (!$campaign || $campaign->status !== 'active') {
            abort(404, 'Kampanye tidak ditemukan atau tidak aktif');
        }

        if ($campaign->end_date && $campaign->end_date->isPast()) {
            abort(404, 'Kampanye telah berakhir');
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
