<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class AdminDashboard extends Component
{
    public function __invoke()
    {
        return $this->render();
    }

    public function render()
    {
        $today = now();
        
        // Statistics
        $totalDonations = Donation::count();
        $totalAmount = Donation::sum('amount');
        $pendingCount = Donation::where('status', 'pending')->count();
        $approvedCount = Donation::where('status', 'approved')->count();
        
        // By Type
        $wakafPembangunanAmount = Donation::where('type', 'wakaf_pembangunan')->sum('amount');
        $wakafPembangunanCount = Donation::where('type', 'wakaf_pembangunan')->count();
        $wakafProduktifAmount = Donation::where('type', 'wakaf_produktif')->sum('amount');
        $wakafProduktifCount = Donation::where('type', 'wakaf_produktif')->count();
        $donasiPendidikanAmount = Donation::where('type', 'donasi_pendidikan')->sum('amount');
        $donasiPendidikanCount = Donation::where('type', 'donasi_pendidikan')->count();
        
        // Recent Donations
        $recentDonations = Donation::with('paymentMethod')
            ->latest()
            ->take(5)
            ->get();
        
        // Monthly trend (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $today->clone()->subMonths($i);
            $monthName = $month->format('M');
            $amount = Donation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            $monthlyData[] = [
                'month' => $monthName,
                'amount' => $amount
            ];
        }
        
        return view('livewire.admin.dashboard', [
            'totalDonations' => $totalDonations,
            'totalAmount' => $totalAmount,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'wakafPembangunanAmount' => $wakafPembangunanAmount,
            'wakafPembangunanCount' => $wakafPembangunanCount,
            'wakafProduktifAmount' => $wakafProduktifAmount,
            'wakafProduktifCount' => $wakafProduktifCount,
            'donasiPendidikanAmount' => $donasiPendidikanAmount,
            'donasiPendidikanCount' => $donasiPendidikanCount,
            'recentDonations' => $recentDonations,
            'monthlyData' => $monthlyData,
        ]);
    }
}
