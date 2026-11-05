<?php

namespace App\Livewire;

use App\DashboardReportData;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class PublicDashboard extends Component
{
    public function render(): View
    {
        return view('livewire.public-dashboard', app(DashboardReportData::class)->collect(publicOnly: true));
    }
}
