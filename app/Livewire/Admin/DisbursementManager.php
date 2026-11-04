<?php

namespace App\Livewire\Admin;

use App\Models\Disbursement;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class DisbursementManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    #[Locked]
    public ?int $selectedId = null;

    public string $description = '';

    public string $amount = '';

    public string $type = '';

    public string $disbursed_at = '';

    public function boot(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function openCreateForm(): void
    {
        $this->closeForm();
        $this->disbursed_at = now()->toDateString();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->reset('showForm', 'selectedId', 'description', 'amount', 'type', 'disbursed_at');
        $this->resetValidation();
    }

    public function edit(int $id): void
    {
        $this->closeForm();
        $disbursement = Disbursement::findOrFail($id);
        $this->selectedId = $disbursement->id;
        $this->description = $disbursement->description;
        $this->amount = rtrim(rtrim(number_format((float) $disbursement->amount, 2, ',', '.'), '0'), ',');
        $this->type = $disbursement->type;
        $this->disbursed_at = $disbursement->disbursed_at?->format('Y-m-d') ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        if (preg_match('/^\d{1,3}(?:\.\d{3})+(?:,\d{1,2})?$|^\d+(?:,\d{1,2})?$/', $this->amount)) {
            $this->amount = str_replace(',', '.', str_replace('.', '', $this->amount));
        }

        $validated = $this->validate([
            'description' => ['required', 'string', 'max:5000'],
            'disbursed_at' => ['required', 'date_format:Y-m-d'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99', 'decimal:0,2'],
            'type' => ['required', Rule::in(array_keys(Disbursement::CATEGORIES))],
        ], [], [
            'description' => 'deskripsi penyaluran',
            'disbursed_at' => 'tanggal penyaluran',
            'amount' => 'jumlah yang disalurkan',
            'type' => 'jenis donasi',
        ]);

        if ($this->selectedId !== null) {
            Disbursement::findOrFail($this->selectedId)->update($validated);
        } else {
            Disbursement::create($validated);
        }

        $this->closeForm();
        $this->resetPage();
        session()->flash('success', 'Data penyaluran berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        Disbursement::findOrFail($id)->delete();
        $this->closeForm();
        $this->resetPage();
        session()->flash('success', 'Data penyaluran berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.admin.disbursement-manager', [
            'disbursements' => Disbursement::orderByRaw('disbursed_at IS NULL')->orderByDesc('disbursed_at')->orderByDesc('id')->paginate(15),
            'totalAmount' => Disbursement::sum('amount'),
            'categories' => Disbursement::CATEGORIES,
        ]);
    }
}
