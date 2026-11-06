<?php

namespace App\Livewire;

use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\UnitUsahaReport;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class PublicReportTable extends Component
{
    use WithPagination;

    #[Locked]
    public string $section = '';

    public string $search = '';

    public function mount(string $section): void
    {
        abort_unless(in_array($section, ['penyaluran', 'unit-usaha', 'donasi-umum'], true), 404);
        $this->section = $section;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $search = trim($this->search);
        $query = match ($this->section) {
            'penyaluran' => Disbursement::select('id', 'description', 'amount', 'type', 'disbursed_at')
                ->when($search !== '', fn ($query) => $query->whereLike('description', '%'.$search.'%'))
                ->orderByRaw('disbursed_at IS NULL')->orderByDesc('disbursed_at')->orderByDesc('id'),
            'unit-usaha' => UnitUsahaReport::select('id', 'nama', 'tanggal', 'jumlah_uang', 'catatan')
                ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                    $query->whereLike('nama', '%'.$search.'%')->orWhereLike('catatan', '%'.$search.'%');
                }))->orderByDesc('tanggal')->orderByDesc('id'),
            'donasi-umum' => Donation::query()->where('status', 'approved')
                ->select('id', 'type', 'amount', 'created_at')
                ->selectRaw('CASE WHEN is_anonymous = 1 THEN ? ELSE donatur_name END AS donatur_name', ['Hamba Allah'])
                ->when($search !== '', fn ($query) => $query->whereRaw(
                    'LOWER(CASE WHEN is_anonymous = 1 THEN ? ELSE donatur_name END) LIKE ?',
                    ['Hamba Allah', '%'.mb_strtolower($search).'%'],
                ))
                ->latest()->orderByDesc('id'),
            default => abort(404),
        };

        return view('livewire.public-report-table', [
            'rows' => $query->paginate(15),
            'categories' => Disbursement::CATEGORIES,
            'title' => match ($this->section) {
                'penyaluran' => 'Laporan Penyaluran',
                'unit-usaha' => 'Laporan Unit Usaha',
                'donasi-umum' => 'Laporan Wakaf',
            },
        ]);
    }
}
