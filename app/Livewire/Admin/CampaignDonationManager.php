<?php

namespace App\Livewire\Admin;

use App\Models\CampaignDonation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class CampaignDonationManager extends Component
{
    use WithPagination, WithFileUploads;

    public $filter = 'pending';
    public $search = '';
    public $selectedProof = null;

    protected $rules = [
        'status' => 'required|in:pending,approved,rejected',
    ];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function approve($id)
    {
        $donation = CampaignDonation::findOrFail($id);
        $donation->update(['status' => 'approved']);

        session()->flash('success', 'Donasi berhasil di-approve!');
    }

    public function reject($id)
    {
        $donation = CampaignDonation::findOrFail($id);
        $donation->update(['status' => 'rejected']);

        session()->flash('success', 'Donasi berhasil di-reject!');
    }

    public function delete($id)
    {
        $donation = CampaignDonation::findOrFail($id);
        $donation->delete();

        session()->flash('success', 'Donasi berhasil dihapus!');
    }

    public function render()
    {
        $donations = CampaignDonation::when($this->filter, function ($query) {
            if ($this->filter !== '') {
                return $query->where('status', $this->filter);
            }
        })
        ->when($this->search, function ($query) {
            return $query->where('donatur_name', 'like', '%' . $this->search . '%');
        })
        ->with(['campaign', 'paymentMethod'])
        ->latest()
        ->paginate(15);

        return view('livewire.admin.campaign-donation-manager', [
            'donations' => $donations,
            'paymentMethods' => \App\Models\PaymentMethod::all(),
        ]);
    }
}
