<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class CampaignManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $selectedCampaign = null;

    // Form fields
    public $title;
    public $description;
    public $short_description;
    public $target_amount;
    public $start_date;
    public $end_date;
    public $status = 'draft';
    public $image;
    public $is_featured = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'short_description' => 'nullable|string|max:500',
        'target_amount' => 'required|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|in:draft,active,completed,cancelled',
        'image' => 'nullable|image|max:2048',
    ];

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function createCampaign()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('campaigns', 'public');
        }

        Campaign::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'description' => $this->description,
            'short_description' => $this->short_description,
            'target_amount' => $this->target_amount,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'status' => $this->status,
            'image' => $imagePath,
            'is_featured' => $this->is_featured,
        ]);

        $this->closeCreateForm();
        session()->flash('success', 'Kampanye berhasil dibuat!');
    }

    public function editCampaign($id)
    {
        $this->resetForm();

        $campaign = Campaign::findOrFail($id);
        $this->selectedCampaign = $campaign;
        $this->title = $campaign->title;
        $this->description = $campaign->description;
        $this->short_description = $campaign->short_description;
        $this->target_amount = $campaign->target_amount;
        $this->start_date = $campaign->start_date?->format('Y-m-d');
        $this->end_date = $campaign->end_date?->format('Y-m-d');
        $this->status = $campaign->status;
        $this->is_featured = $campaign->is_featured;
        $this->showEditForm = true;

        $this->dispatch('edit-form-opened');
    }

    public function updateCampaign()
    {
        // Ensure campaign exists
        if (!$this->selectedCampaign) {
            session()->flash('error', 'Kampanye tidak ditemukan!');
            return redirect()->route('admin.campaigns');
        }

        $this->validate();

        $campaign = $this->selectedCampaign;

        $imagePath = $campaign->image;
        if ($this->image) {
            if ($imagePath) {
                \Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->image->store('campaigns', 'public');
        }

        $campaign->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'description' => $this->description,
            'short_description' => $this->short_description,
            'target_amount' => $this->target_amount,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'status' => $this->status,
            'image' => $imagePath,
            'is_featured' => $this->is_featured,
        ]);

        $this->closeEditForm();
        session()->flash('success', 'Kampanye berhasil diupdate!');
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
        $this->selectedCampaign = null;
    }

    public function deleteCampaign($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        // Reset pagination and refresh
        $this->resetPage();
        session()->flash('success', 'Kampanye berhasil dihapus!');
    }

    private function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->short_description = '';
        $this->target_amount = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'draft';
        $this->image = null;
        $this->is_featured = false;
    }

    public function render()
    {
        return view('livewire.admin.campaign-manager', [
            'campaigns' => Campaign::latest()->paginate(10),
        ]);
    }
}
