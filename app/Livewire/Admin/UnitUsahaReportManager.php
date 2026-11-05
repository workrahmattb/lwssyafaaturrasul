<?php

namespace App\Livewire\Admin;

use App\Models\UnitUsahaReport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class UnitUsahaReportManager extends Component
{
    use WithPagination;

    public $showCreateForm = false;

    public $showEditForm = false;

    public $selectedReport = null;

    public string $search = '';

    // Form fields
    public $nama = '';

    public $tanggal = '';

    public $jumlah_uang = '';

    public $catatan = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'jumlah_uang' => 'required|numeric|min:0',
        'catatan' => 'nullable|string',
    ];

    public function updatedJumlahUang($value)
    {
        // Strip thousand separators (e.g. 1.500.000 -> 1500000)
        if (is_string($value)) {
            $this->jumlah_uang = preg_replace('/[^0-9]/', '', $value);
        }
    }

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

    public function create()
    {
        $this->jumlah_uang = preg_replace('/[^0-9]/', '', (string) $this->jumlah_uang);

        $this->validate();

        UnitUsahaReport::create([
            'nama' => $this->nama,
            'tanggal' => $this->tanggal,
            'jumlah_uang' => $this->jumlah_uang,
            'catatan' => $this->catatan,
        ]);

        $this->closeCreateForm();
        $this->resetPage();
        session()->flash('success', 'Laporan Unit Usaha berhasil dibuat!');
    }

    public function edit($id)
    {
        $this->resetForm();

        $report = UnitUsahaReport::findOrFail($id);
        $this->selectedReport = $report;
        $this->nama = $report->nama;
        $this->tanggal = $report->tanggal->format('Y-m-d');
        $this->jumlah_uang = $report->jumlah_uang
            ? number_format((float) $report->jumlah_uang, 0, ',', '.')
            : '';
        $this->catatan = $report->catatan;
        $this->showEditForm = true;
    }

    public function update()
    {
        if (! $this->selectedReport) {
            session()->flash('error', 'Laporan tidak ditemukan!');

            return;
        }

        $this->jumlah_uang = preg_replace('/[^0-9]/', '', (string) $this->jumlah_uang);

        $this->validate();

        $this->selectedReport->update([
            'nama' => $this->nama,
            'tanggal' => $this->tanggal,
            'jumlah_uang' => $this->jumlah_uang,
            'catatan' => $this->catatan,
        ]);

        $this->closeEditForm();
        session()->flash('success', 'Laporan Unit Usaha berhasil diupdate!');
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
        $this->selectedReport = null;
    }

    public function delete($id)
    {
        $report = UnitUsahaReport::findOrFail($id);
        $report->delete();

        $this->resetPage();
        session()->flash('success', 'Laporan Unit Usaha berhasil dihapus!');
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->tanggal = '';
        $this->jumlah_uang = '';
        $this->catatan = '';
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $reports = UnitUsahaReport::query()
            ->when(trim($this->search) !== '', function ($query) {
                $query->where(function ($query) {
                    $query->whereLike('nama', '%'.trim($this->search).'%')
                        ->orWhereLike('catatan', '%'.trim($this->search).'%');
                });
            })
            ->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);
        $totalAmount = UnitUsahaReport::sum('jumlah_uang');
        $totalCount = UnitUsahaReport::count();

        return view('livewire.admin.unit-usaha-report-manager', [
            'reports' => $reports,
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
        ]);
    }
}
