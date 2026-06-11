<?php

namespace App\Livewire;

use App\Models\Donation;
use App\Models\CampaignDonation;
use App\Models\UnitUsahaReport;
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
        
        // Unit Usaha Report Stats
        $unitUsahaTotalReports = UnitUsahaReport::count();
        $unitUsahaTotalAmount = UnitUsahaReport::sum('jumlah_uang');
        $unitUsahaRecent = UnitUsahaReport::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->take(5)->get();

        // Recent Donations
        $recentDonations = Donation::with('paymentMethod')
            ->latest()
            ->take(5)
            ->get();
        
        // Monthly trend (full 12 months of current year)
        $monthlyLabels = [];
        $monthlyAmounts = [];
        $monthlyCampaignAmounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $today->clone()->subMonthsNoOverflow($i);
            $monthName = $month->isoFormat('MMM YYYY');
            $monthlyLabels[] = $monthName;
            
            $amount = Donation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->sum('amount');
            $monthlyAmounts[] = (float) $amount;
            
            $campaignAmount = CampaignDonation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->sum('amount');
            $monthlyCampaignAmounts[] = (float) $campaignAmount;
        }
        
        // Yearly trend (last 5 years)
        $yearlyLabels = [];
        $yearlyAmounts = [];
        $yearlyCampaignAmounts = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = $today->clone()->subYearsNoOverflow($i)->year;
            $yearlyLabels[] = (string) $year;
            
            $amount = Donation::whereYear('created_at', $year)
                ->where('status', 'approved')
                ->sum('amount');
            $yearlyAmounts[] = (float) $amount;
            
            $campaignAmount = CampaignDonation::whereYear('created_at', $year)
                ->where('status', 'approved')
                ->sum('amount');
            $yearlyCampaignAmounts[] = (float) $campaignAmount;
        }

        // Category breakdown for pie chart
        $categoryLabels = ['Wakaf Pembangunan', 'Wakaf Produktif', 'Donasi Pendidikan'];
        $categoryAmounts = [
            (float) $wakafPembangunanAmount,
            (float) $wakafProduktifAmount,
            (float) $donasiPendidikanAmount,
        ];
        $categoryColors = ['#10b981', '#14b8a6', '#06b6d4'];
        
        // Stats per status
        $rejectedCount = Donation::where('status', 'rejected')->count();
        $totalApprovedAmount = Donation::where('status', 'approved')->sum('amount');
        $totalCampaignDonations = CampaignDonation::count();
        $totalCampaignAmount = CampaignDonation::sum('amount');
        
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
            'unitUsahaTotalReports' => $unitUsahaTotalReports,
            'unitUsahaTotalAmount' => $unitUsahaTotalAmount,
            'unitUsahaRecent' => $unitUsahaRecent,
            // Chart data
            'monthlyLabels' => $monthlyLabels,
            'monthlyAmounts' => $monthlyAmounts,
            'monthlyCampaignAmounts' => $monthlyCampaignAmounts,
            'yearlyLabels' => $yearlyLabels,
            'yearlyAmounts' => $yearlyAmounts,
            'yearlyCampaignAmounts' => $yearlyCampaignAmounts,
            'categoryLabels' => $categoryLabels,
            'categoryAmounts' => $categoryAmounts,
            'categoryColors' => $categoryColors,
            'rejectedCount' => $rejectedCount,
            'totalApprovedAmount' => $totalApprovedAmount,
            'totalCampaignDonations' => $totalCampaignDonations,
            'totalCampaignAmount' => $totalCampaignAmount,
        ]);
    }
}
