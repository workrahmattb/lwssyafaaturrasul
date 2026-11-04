<?php

namespace App\Livewire;

use App\Models\CampaignDonation;
use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\UnitUsahaReport;
use Livewire\Attributes\Layout;
use Livewire\Component;

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

        // Unit Usaha monthly trend — only "Hasil Keuntungan SR Bakery" (last 12 months)
        $unitUsahaMonthlyLabels = [];
        $unitUsahaMonthlyAmounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $today->clone()->subMonthsNoOverflow($i);
            $unitUsahaMonthlyLabels[] = $month->isoFormat('MMM YYYY');
            $amount = UnitUsahaReport::where('nama', 'Hasil Keuntungan SR Bakery')
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah_uang');
            $unitUsahaMonthlyAmounts[] = (float) $amount;
        }

        // Unit Usaha breakdown per unit (top 8 by total amount)
        $unitUsahaUnits = UnitUsahaReport::select('nama')
            ->selectRaw('SUM(jumlah_uang) as total')
            ->groupBy('nama')
            ->orderByDesc('total')
            ->take(8)
            ->get();
        $unitUsahaUnitLabels = $unitUsahaUnits->pluck('nama')->map(fn ($name) => \Illuminate\Support\Str::limit($name, 20))->all();
        $unitUsahaUnitAmounts = $unitUsahaUnits->pluck('total')->map(fn ($total) => (float) $total)->all();
        $unitUsahaUnitColors = ['#8b5cf6', '#a855f7', '#d946ef', '#c084fc', '#9333ea', '#6d28d9', '#7c3aed', '#e879f9'];

        // Recent Donations
        $recentDonations = Donation::with('paymentMethod')
            ->latest()
            ->take(5)
            ->get();

        // Monthly trend (last 12 months) broken down by donation type
        $monthlyLabels = [];
        $monthlyWakafPembangunanAmounts = [];
        $monthlyWakafProduktifAmounts = [];
        $monthlyDonasiPendidikanAmounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $today->clone()->subMonthsNoOverflow($i);
            $monthName = $month->isoFormat('MMM YYYY');
            $monthlyLabels[] = $monthName;

            $monthlyWakafPembangunanAmounts[] = (float) Donation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->where('type', 'wakaf_pembangunan')
                ->sum('amount');

            $monthlyWakafProduktifAmounts[] = (float) Donation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->where('type', 'wakaf_produktif')
                ->sum('amount');

            $monthlyDonasiPendidikanAmounts[] = (float) Donation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->where('type', 'donasi_pendidikan')
                ->sum('amount');
        }

        // Yearly trend since fundraising started in 2024
        $yearlyLabels = [];
        $yearlyTotalAmounts = [];
        $yearlyDisbursementAmounts = [];
        $unitUsahaYearlyAmounts = [];
        $yearlyDisbursementsByCategory = array_fill_keys(array_keys(Disbursement::CATEGORIES), []);
        $yearlyWakafPembangunanAmounts = [];
        $yearlyWakafProduktifAmounts = [];
        $yearlyDonasiPendidikanAmounts = [];
        for ($year = 2024; $year <= $today->year; $year++) {
            $yearlyLabels[] = (string) $year;
            $unitUsahaYearlyAmounts[] = (float) UnitUsahaReport::whereYear('tanggal', $year)->sum('jumlah_uang');
            $disbursementsForYear = Disbursement::whereYear('disbursed_at', $year)
                ->select('type')->selectRaw('SUM(amount) as total')
                ->groupBy('type')->pluck('total', 'type');
            $yearlyDisbursementAmounts[] = (float) $disbursementsForYear->sum();
            foreach (array_keys(Disbursement::CATEGORIES) as $type) {
                $yearlyDisbursementsByCategory[$type][] = (float) ($disbursementsForYear[$type] ?? 0);
            }
            $yearlyTotalAmounts[] = (float) Donation::whereYear('created_at', $year)
                ->where('status', 'approved')->sum('amount')
                + (float) CampaignDonation::whereYear('created_at', $year)
                    ->where('status', 'approved')->sum('amount');

            $yearlyWakafPembangunanAmounts[] = (float) Donation::whereYear('created_at', $year)
                ->where('status', 'approved')
                ->where('type', 'wakaf_pembangunan')
                ->sum('amount');

            $yearlyWakafProduktifAmounts[] = (float) Donation::whereYear('created_at', $year)
                ->where('status', 'approved')
                ->where('type', 'wakaf_produktif')
                ->sum('amount');

            $yearlyDonasiPendidikanAmounts[] = (float) Donation::whereYear('created_at', $year)
                ->where('status', 'approved')
                ->where('type', 'donasi_pendidikan')
                ->sum('amount');
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

        $disbursementsByCategory = Disbursement::select('type')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');
        $disbursementAmounts = array_map(
            fn (string $type): float => (float) ($disbursementsByCategory[$type] ?? 0),
            array_keys(Disbursement::CATEGORIES),
        );

        $totalCollectedDonations = (float) $totalApprovedAmount
            + (float) CampaignDonation::where('status', 'approved')->sum('amount');
        $totalDisbursedAmount = array_sum($disbursementAmounts);
        $lwsBalance = $totalCollectedDonations + (float) $unitUsahaTotalAmount - $totalDisbursedAmount;

        return view('livewire.admin.dashboard', [
            'totalCollectedDonations' => $totalCollectedDonations,
            'lwsBalance' => $lwsBalance,
            'disbursementLabels' => array_values(Disbursement::CATEGORIES),
            'disbursementAmounts' => $disbursementAmounts,
            'yearlyDisbursementAmounts' => $yearlyDisbursementAmounts,
            'yearlyDisbursementsByCategory' => $yearlyDisbursementsByCategory,
            'undatedDisbursementAmount' => (float) Disbursement::whereNull('disbursed_at')->sum('amount'),
            'totalDisbursedAmount' => $totalDisbursedAmount,
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
            // Unit Usaha chart data
            'unitUsahaMonthlyLabels' => $unitUsahaMonthlyLabels,
            'unitUsahaMonthlyAmounts' => $unitUsahaMonthlyAmounts,
            'unitUsahaYearlyAmounts' => $unitUsahaYearlyAmounts,
            'unitUsahaUnitLabels' => $unitUsahaUnitLabels,
            'unitUsahaUnitAmounts' => $unitUsahaUnitAmounts,
            'unitUsahaUnitColors' => $unitUsahaUnitColors,
            // Chart data
            'monthlyLabels' => $monthlyLabels,
            'monthlyWakafPembangunanAmounts' => $monthlyWakafPembangunanAmounts,
            'monthlyWakafProduktifAmounts' => $monthlyWakafProduktifAmounts,
            'monthlyDonasiPendidikanAmounts' => $monthlyDonasiPendidikanAmounts,
            'yearlyLabels' => $yearlyLabels,
            'yearlyTotalAmounts' => $yearlyTotalAmounts,
            'yearlyWakafPembangunanAmounts' => $yearlyWakafPembangunanAmounts,
            'yearlyWakafProduktifAmounts' => $yearlyWakafProduktifAmounts,
            'yearlyDonasiPendidikanAmounts' => $yearlyDonasiPendidikanAmounts,
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
