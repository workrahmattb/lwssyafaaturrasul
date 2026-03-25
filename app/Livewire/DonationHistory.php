<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonationHistory extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.donation-history', [
            'history' => Donation::where('status', 'approved')
                ->select('donatur_name', 'type', 'amount', 'created_at')
                ->latest()
                ->paginate(10),
        ]);
    }
}
