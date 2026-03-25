<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonationHistory extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.tailwind.simple-pagination';
    }

    public function gotoPage($page)
    {
        parent::gotoPage($page);
        $this->scrollToSection();
    }

    public function previousPage()
    {
        parent::previousPage();
        $this->scrollToSection();
    }

    public function nextPage()
    {
        parent::nextPage();
        $this->scrollToSection();
    }

    public function scrollToSection()
    {
        $this->dispatch('scroll-to-riwayat');
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
