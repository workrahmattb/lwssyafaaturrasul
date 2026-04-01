<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.campaign')]
class CampaignDonationForm extends Component
{
    use WithFileUploads;

    public $slug;
    public $campaign;
    public $step = 1;
    
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

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->campaign = Campaign::where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        if (!$this->campaign->isActive()) {
            abort(404, 'Kampanye tidak aktif');
        }
    }

    public function goToStep2()
    {
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        $this->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'phone' => ['required', 'min:10', 'max:15'],
            'payment_method_id' => ['required'],
        ], [
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
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

        // Update campaign raised amount later when approved
        // For now, just show success

        $this->step = 3;
    }

    public function render()
    {
        return view('livewire.campaign-donation-form', [
            'banks' => PaymentMethod::all(),
        ]);
    }
}
