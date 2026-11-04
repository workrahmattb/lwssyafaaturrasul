<?php

namespace App\Livewire;

use App\Mail\CampaignDonationMail;
use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CampaignDonate extends Component
{
    use WithFileUploads;

    public $slug = '';

    public $campaignId = null;

    public $step = 1;

    // Form fields
    public $amount = '';

    public $donatur_name = '';

    public $phone = '';

    public $payment_method_id = '';

    public $is_anonymous = false;

    public $proof_of_transfer = null;

    public $trx_id = '';

    protected $rules = [
        'amount' => 'required|min:1000',
        'phone' => 'required|min:10|max:15',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
    ];

    public function mount($slug = null)
    {
        // Livewire meneruskan route params ({slug}) ke mount secara otomatis.
        // Fallback ke request()->route() menjaga kompatibilitas saat dipanggil
        // tanpa route param (mis. di Livewire component test).
        $slug = $slug ?? request()->route('slug');
        $campaign = Campaign::where('slug', $slug)->first();

        if (! $campaign || $campaign->status !== 'active') {
            abort(404, 'Donasi tidak ditemukan atau tidak aktif');
        }

        if ($campaign->end_date && $campaign->end_date->isPast()) {
            abort(404, 'Donasi telah berakhir');
        }

        $this->campaignId = $campaign->id;
        $this->slug = $slug;
    }

    public function updatedAmount($value)
    {
        if (is_string($value)) {
            $this->amount = preg_replace('/[^0-9]/', '', $value);
            $this->amount = ltrim($this->amount, '0');
        }
    }

    public function updatedIsAnonymous($value)
    {
        // Clear donatur_name when anonymous is checked
        if ($value) {
            $this->donatur_name = '';
        }
    }

    public function updatedPaymentMethodId($value)
    {
        // No action needed
    }

    public function updatedProofOfTransfer($value)
    {
        if (! $value) {
            return;
        }

        $this->validate([
            'proof_of_transfer' => 'mimes:jpeg,jpg,png,pdf|max:2048',
        ], [
            'proof_of_transfer.mimes' => 'File harus berupa gambar (JPG, PNG) atau PDF',
            'proof_of_transfer.max' => 'Ukuran file maksimal 2MB',
        ]);
    }

    public function goToStep2()
    {
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        $rules = [
            'amount' => ['required', 'numeric', 'min:1000'],
            'phone' => ['required', 'min:10', 'max:15'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ];

        // Only require donatur_name if not anonymous
        if (! $this->is_anonymous) {
            $rules['donatur_name'] = 'required|min:3';
        }

        $this->validate($rules, [
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'payment_method_id.exists' => 'Bank tujuan tidak valid',
            'donatur_name.required' => 'Nama donatur harus diisi',
            'donatur_name.min' => 'Nama minimal 3 karakter',
        ]);

        $this->step = 2;
        $this->trx_id = 'TRX-'.strtoupper(Str::random(8));
    }

    public function backToStep1()
    {
        $this->step = 1;
    }

    public function submitDonation()
    {
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        $rules = [
            'amount' => 'required|numeric|min:1000',
            'phone' => 'required|min:10|max:15',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ];

        // Only require donatur_name if not anonymous
        if (! $this->is_anonymous) {
            $rules['donatur_name'] = 'required|min:3';
        }

        $this->validate($rules, [
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'payment_method_id.exists' => 'Bank tujuan tidak valid',
            'proof_of_transfer.required' => 'Upload bukti transfer',
            'proof_of_transfer.mimes' => 'File harus berupa gambar (JPG, PNG) atau PDF',
            'proof_of_transfer.max' => 'Ukuran file maksimal 2MB',
            'donatur_name.required' => 'Nama donatur harus diisi',
            'donatur_name.min' => 'Nama minimal 3 karakter',
        ]);

        $proofPath = $this->proof_of_transfer->store('campaign-proof', 'public');

        $donation = CampaignDonation::create([
            'trx_id' => $this->trx_id,
            'campaign_id' => $this->campaignId,
            'donatur_name' => $this->is_anonymous ? 'Hamba Allah' : $this->donatur_name,
            'amount' => $this->amount,
            'payment_method_id' => $this->payment_method_id,
            'proof_of_transfer' => $proofPath,
            'status' => 'pending',
            'is_anonymous' => $this->is_anonymous,
            'phone' => $this->phone,
        ]);

        // Send email notification to admin
        try {
            Mail::to(config('mail.admin_address'))->send(new CampaignDonationMail($donation));
        } catch (\Exception $e) {
            // Log error but don't fail the donation
            \Log::error('Failed to send campaign donation notification email: '.$e->getMessage());
        }

        $this->step = 3;
    }

    public function render()
    {
        return view('livewire.campaign-donate', [
            'banks' => PaymentMethod::all(),
            'campaign' => Campaign::find($this->campaignId),
        ])->layout('components.layouts.campaign');
    }
}
