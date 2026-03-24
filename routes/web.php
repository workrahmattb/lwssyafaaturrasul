<?php

use App\Livewire\DonationFlow;
use App\Livewire\Admin\CampaignManager;
use App\Livewire\Admin\CampaignDonationManager;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', DonationFlow::class)->name('donation');

// Campaigns
Route::get('/kampanye', [App\Livewire\CampaignList::class, 'render'])->name('campaigns.index');
Route::get('/kampanye/{slug}', App\Livewire\CampaignDetail::class)->name('campaigns.detail');
Route::get('/kampanye/{slug}/donasi', App\Livewire\CampaignDonate::class)->name('campaigns.donate');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
// Admin login routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin dashboard
Route::get('/admin/donations', [DashboardController::class, 'dashboard'])->name('admin.donations')->middleware('admin');
Route::get('/admin/manage', [DashboardController::class, 'manage'])->name('admin.manage')->middleware('admin');
Route::get('/admin/campaign-donation', CampaignDonationManager::class)->name('admin.campaign-donation')->middleware('admin');

// Admin campaigns
Route::get('/admin/campaigns', CampaignManager::class)->name('admin.campaigns')->middleware('admin');
