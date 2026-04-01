<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.campaign-list', compact('campaigns'));
    }

    public function detail($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        if (!$campaign->isActive()) {
            abort(404, 'Kampanye tidak aktif');
        }

        $recentDonations = $campaign->activeDonations()
            ->with('paymentMethod')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.campaign-detail', compact('campaign', 'recentDonations'));
    }

    public function donate($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        if (!$campaign->isActive()) {
            abort(404, 'Kampanye tidak aktif');
        }

        return view('livewire.campaign-donate', compact('campaign'));
    }

    public function submit(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        if (!$campaign->isActive()) {
            abort(404, 'Kampanye tidak aktif');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'phone' => 'required|min:10|max:15',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'donatur_name' => 'required_if:is_anonymous,false',
            'is_anonymous' => 'boolean',
            'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ], [
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'proof_of_transfer.required' => 'Upload bukti transfer',
            'proof_of_transfer.mimes' => 'File harus berupa gambar (JPG, PNG) atau PDF',
            'proof_of_transfer.max' => 'Ukuran file maksimal 2MB',
        ]);

        $proofPath = $request->file('proof_of_transfer')->store('campaign-proof', 'public');

        $trx_id = 'TRX-' . strtoupper(Str::random(8));

        CampaignDonation::create([
            'trx_id' => $trx_id,
            'campaign_id' => $campaign->id,
            'donatur_name' => $request->is_anonymous ? 'Hamba Allah' : $request->donatur_name,
            'amount' => str_replace('.', '', $validated['amount']),
            'payment_method_id' => $validated['payment_method_id'],
            'proof_of_transfer' => $proofPath,
            'status' => 'pending',
            'is_anonymous' => $request->boolean('is_anonymous'),
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('campaigns.success', ['slug' => $slug, 'trx' => $trx_id]);
    }

    public function success($slug, $trx)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        return view('livewire.campaign-success', compact('campaign', 'trx'));
    }
}
