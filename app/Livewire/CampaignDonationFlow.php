<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class CampaignDonationFlow extends Component
{
    use WithFileUploads;

    public $campaignId;
    public $campaign;
    public $step = 1;

    // Form fields
    public $amount;
    public $donatur_name;
    public $phone;
    public $payment_method_id;
    public $is_anonymous = false;
    public $proof_of_transfer;
    public $trx_id;

    protected $rules = [
        'amount' => 'required|min:1000',
        'phone' => 'required|min:10|max:15',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
    ];

    public function mount($campaignId = null)
    {
        if ($campaignId) {
            $this->campaignId = $campaignId;
            $this->campaign = Campaign::find($campaignId);
        }
    }

    public function selectCampaign($campaignId)
    {
        $this->campaignId = $campaignId;
        $this->campaign = Campaign::find($campaignId);
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->donatur_name = '';
        $this->phone = '';
        $this->payment_method_id = '';
        $this->is_anonymous = false;
        $this->proof_of_transfer = null;
        $this->trx_id = '';
        $this->step = 1;
    }

    public function updatedAmount($value)
    {
        if (is_string($value)) {
            $this->amount = preg_replace('/[^0-9]/', '', $value);
            $this->amount = ltrim($this->amount, '0');
        }
    }

    public function updatedProofOfTransfer()
    {
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
            'payment_method_id' => ['required'],
        ];

        // Only require donatur_name if not anonymous
        if (!$this->is_anonymous) {
            $rules['donatur_name'] = 'required|min:3';
        }

        $this->validate($rules, [
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'donatur_name.required' => 'Nama donatur harus diisi',
            'donatur_name.min' => 'Nama minimal 3 karakter',
        ]);

        $this->step = 2;
        $this->trx_id = 'TRX-' . strtoupper(Str::random(8));
    }

    public function backToStep1()
    {
        $this->step = 1;
    }

    public function submitDonation()
    {
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        $this->validate([
            'amount' => 'required|numeric|min:1000',
            'phone' => 'required|min:10|max:15',
            'payment_method_id' => 'required|exists:payment_methods,id',
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

        $proofPath = $this->proof_of_transfer->store('campaign-proof', 'public');

        CampaignDonation::create([
            'trx_id' => $this->trx_id,
            'campaign_id' => $this->campaign->id,
            'donatur_name' => $this->is_anonymous ? 'Hamba Allah' : $this->donatur_name,
            'amount' => $this->amount,
            'payment_method_id' => $this->payment_method_id,
            'proof_of_transfer' => $proofPath,
            'status' => 'pending',
            'is_anonymous' => $this->is_anonymous,
            'phone' => $this->phone,
        ]);

        // Update campaign raised amount and donor count
        $this->campaign->increment('raised_amount', $this->amount);
        $this->campaign->increment('donor_count');

        $this->step = 3;
        $this->dispatch('donationCompleted');
    }

    public function render()
    {
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.campaign-donation-flow', [
            'campaigns' => $campaigns,
            'banks' => PaymentMethod::all(),
        ]);
    }
}
