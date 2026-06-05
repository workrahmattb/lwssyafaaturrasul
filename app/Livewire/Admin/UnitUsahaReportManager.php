<?php

namespace App\Livewire\Admin;

use App\Models\UnitUsahaReport;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class UnitUsahaReportManager extends Component
{
    use WithPagination;

    #[Url(as: 'page', history: true)]
    public $page = 1;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $selectedReport = null;

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
        $this->jumlah_uang = $report->jumlah_uang;
        $this->catatan = $report->catatan;
        $this->showEditForm = true;
    }

    public function update()
    {
        if (!$this->selectedReport) {
            session()->flash('error', 'Laporan tidak ditemukan!');
            return;
        }

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

    public function render()
    {
        $reports = UnitUsahaReport::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);
        $totalAmount = UnitUsahaReport::sum('jumlah_uang');
        $totalCount = UnitUsahaReport::count();

        return view('livewire.admin.unit-usaha-report-manager', [
            'reports' => $reports,
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
        ]);
    }
}
