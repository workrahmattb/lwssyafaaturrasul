<?php

namespace App\Livewire;

use App\DashboardReportData;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class AdminDashboard extends Component
{
    public function __invoke(): View
    {
        return $this->render();
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard', app(DashboardReportData::class)->collect());
    }
}
