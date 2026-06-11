<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('layouts', resource_path('views/components/layouts'));

        // Share donation stats with the donation layout
        View::composer('donation', function ($view) {
            $approvedDonations = Donation::where('status', 'approved');

            $wakafPembangunanTotal = (clone $approvedDonations)->where('type', 'wakaf_pembangunan')->sum('amount');
            $wakafPembangunanCount = (clone $approvedDonations)->where('type', 'wakaf_pembangunan')->count();
            $wakafProduktifTotal = (clone $approvedDonations)->where('type', 'wakaf_produktif')->sum('amount');
            $wakafProduktifCount = (clone $approvedDonations)->where('type', 'wakaf_produktif')->count();
            $donasiPendidikanTotal = (clone $approvedDonations)->where('type', 'donasi_pendidikan')->sum('amount');
            $donasiPendidikanCount = (clone $approvedDonations)->where('type', 'donasi_pendidikan')->count();

            $featuredCampaigns = Campaign::where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $view->with([
                'wakafPembangunanTotal' => $wakafPembangunanTotal,
                'wakafPembangunanCount' => $wakafPembangunanCount,
                'wakafProduktifTotal' => $wakafProduktifTotal,
                'wakafProduktifCount' => $wakafProduktifCount,
                'donasiPendidikanTotal' => $donasiPendidikanTotal,
                'donasiPendidikanCount' => $donasiPendidikanCount,
                'featuredCampaigns' => $featuredCampaigns,
            ]);
        });
    }
}
