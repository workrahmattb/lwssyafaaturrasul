<?php

namespace App\Livewire\Admin;

use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class DonationManager extends Component
{
    use WithPagination;

    public $filter = 'pending';
    public $selectedProof = null;

    public function approve($id)
    {
        Donation::findOrFail($id)->update(['status' => 'approved']);

        return redirect()->to('/admin/manage');
    }

    public function reject($id)
    {
        Donation::findOrFail($id)->update(['status' => 'rejected']);

        return redirect()->to('/admin/manage');
    }

    public function delete($id)
    {
        Donation::findOrFail($id)->delete();
        session()->flash('success', 'Donasi berhasil dihapus');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $donations = Donation::when($this->filter, function ($query) {
            return $query->where('status', $this->filter);
        })
        ->with('paymentMethod')
        ->latest()
        ->paginate(10);

        return view('livewire.admin.donation-manager', [
            'donations' => $donations,
        ]);
    }
}
