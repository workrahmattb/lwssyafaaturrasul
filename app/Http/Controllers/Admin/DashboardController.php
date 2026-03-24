<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\AdminDashboard;
use App\Livewire\Admin\DonationManager;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('livewire.admin.dashboard-wrapper');
    }

    public function manage()
    {
        return view('livewire.admin.manage-wrapper');
    }
}
