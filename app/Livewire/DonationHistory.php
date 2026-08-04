<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonationHistory extends Component
{
    use WithPagination {
        nextPage as protected parentNextPage;
        previousPage as protected parentPreviousPage;
        gotoPage as protected parentGotoPage;
    }

    protected $paginationTheme = 'tailwind';

    public function previousPage($pageName = 'page')
    {
        $this->parentPreviousPage($pageName);
        $this->dispatch('scrollToRiwayat');
    }

    public function nextPage($pageName = 'page')
    {
        $this->parentNextPage($pageName);
        $this->dispatch('scrollToRiwayat');
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->parentGotoPage($page, $pageName);
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
