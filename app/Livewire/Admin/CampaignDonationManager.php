<?php

namespace App\Livewire\Admin;

use App\Models\CampaignDonation;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class CampaignDonationManager extends Component
{
    use WithPagination, WithFileUploads;

    public $filter = 'pending';
    public $selectedProof = null;

    protected $rules = [
        'status' => 'required|in:pending,approved,rejected',
    ];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function approve($id)
    {
        $donation = CampaignDonation::findOrFail($id);
        $donation->update(['status' => 'approved']);

        // Update campaign raised amount
        $campaign = $donation->campaign;
        if ($campaign) {
            $campaign->increment('raised_amount', $donation->amount);
            $campaign->increment('donor_count');
        }

        session()->flash('success', 'Donasi berhasil di-approve!');
    }

    public function reject($id)
    {
        $donation = CampaignDonation::findOrFail($id);
        
        // If was approved, subtract from campaign
        if ($donation->status === 'approved') {
            $campaign = $donation->campaign;
            if ($campaign) {
                $campaign->decrement('raised_amount', $donation->amount);
                $campaign->decrement('donor_count');
            }
        }
        
        $donation->update(['status' => 'rejected']);

        session()->flash('success', 'Donasi berhasil di-reject!');
    }

    public function delete($id)
    {
        $donation = CampaignDonation::findOrFail($id);

        // If was approved, subtract from campaign
        if ($donation->status === 'approved') {
            $campaign = $donation->campaign;
            if ($campaign) {
                $campaign->decrement('raised_amount', $donation->amount);
                $campaign->decrement('donor_count');
            }
        }

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
        ->with(['campaign', 'paymentMethod'])
        ->latest()
        ->paginate(15);

        return view('livewire.admin.campaign-donation-manager', [
            'donations' => $donations,
            'paymentMethods' => \App\Models\PaymentMethod::all(),
        ]);
    }
}
