<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonationHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function previous()
    {
        $this->previousPage();
        $this->dispatch('scrollToRiwayat');
    }

    public function next()
    {
        $this->nextPage();
        $this->dispatch('scrollToRiwayat');
    }

    public function goToPage($page)
    {
        $this->gotoPage($page);
        $this->dispatch('scrollToRiwayat');
    }

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
