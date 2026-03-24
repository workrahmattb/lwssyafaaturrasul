<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.campaign')]
class CampaignList extends Component
{
    public function render()
    {
        return view('livewire.campaign-list', [
            'campaigns' => Campaign::where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12),
        ]);
    }
}
