<?php

namespace App\Livewire;

use App\Mail\NewDonationMail;
use App\Models\Donation;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('donation')]
class DonationFlow extends Component
{
    use WithFileUploads;

    public $step = 1; // 1: Form, 2: Confirmation, 3: Success
    public $type = 'donasi_pendidikan';
    public $amount;
    public $donatur_name;
    public $phone;
    public $payment_method_id;
    public $is_anonymous = false;
    public $proof_of_transfer;
    public $trx_id;
    public $selectedBank;

    protected $rules = [
        'type' => 'required|in:wakaf_pembangunan,wakaf_produktif,donasi_pendidikan',
        'amount' => 'required|min:1000',
        'phone' => 'required|min:10|max:15',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
    ];

    public function mount()
    {
        // Initialize amount as empty string or 0
        if (!$this->amount) {
            $this->amount = '';
        }
    }

    public function updatedAmount($value)
    {
        // Remove any non-numeric characters and leading zeros
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
        // Ensure amount is numeric (remove any formatting dots)
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        $rules = [
            'type' => 'required',
            'amount' => ['required', 'numeric', 'min:1000'],
            'phone' => ['required', 'min:10', 'max:15'],
            'payment_method_id' => 'required',
        ];

        // Only require donatur_name if not anonymous
        if (!$this->is_anonymous) {
            $rules['donatur_name'] = 'required|min:3';
        }

        $this->validate($rules, [
            'type.required' => 'Pilih jenis donasi',
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'donatur_name.required' => 'Nama donatur harus diisi',
            'donatur_name.min' => 'Nama minimal 3 karakter',
        ]);

        $this->selectedBank = PaymentMethod::find($this->payment_method_id);
        $this->step = 2;
        $this->trx_id = 'TRX-' . strtoupper(Str::random(8));
    }

    public function backToStep1()
    {
        $this->step = 1;
    }

    public function submitDonation()
    {
        // Ensure amount is numeric (remove any formatting dots)
        $this->amount = preg_replace('/[^0-9]/', '', $this->amount);
        $this->amount = ltrim($this->amount, '0');

        // Validate all fields including proof of transfer
        $this->validate([
            'type' => 'required|in:wakaf_pembangunan,wakaf_produktif,donasi_pendidikan',
            'amount' => 'required|numeric|min:1000',
            'phone' => 'required|min:10|max:15',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'proof_of_transfer' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ], [
            'type.required' => 'Pilih jenis donasi',
            'amount.required' => 'Nominal donasi harus diisi',
            'amount.min' => 'Nominal minimal Rp 1.000',
            'phone.required' => 'Nomor WhatsApp harus diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
            'payment_method_id.required' => 'Pilih bank tujuan',
            'proof_of_transfer.required' => 'Upload bukti transfer',
            'proof_of_transfer.mimes' => 'File harus berupa gambar (JPG, PNG) atau PDF',
            'proof_of_transfer.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Upload bukti transfer
        $proofPath = $this->proof_of_transfer->store('proof-of-transfer', 'public');

        $donation = Donation::create([
            'trx_id' => $this->trx_id,
            'type' => $this->type,
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
            Mail::to('workrahmattb@gmail.com')->send(new NewDonationMail($donation));
        } catch (\Exception $e) {
            // Log error but don't fail the donation
            \Log::error('Failed to send donation notification email: ' . $e->getMessage());
        }

        // Set selected bank for step 3 display
        $this->selectedBank = PaymentMethod::find($this->payment_method_id);

        $this->step = 3;
    }

    public function render()
    {
        return view('livewire.donation-flow', [
            'banks' => PaymentMethod::all(),
            'history' => \App\Models\Donation::where('status', 'approved')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
