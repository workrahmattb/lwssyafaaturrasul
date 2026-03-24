<div class="max-w-3xl mx-auto px-6 py-12">
    @if($step === 1)
    {{-- Step 1: Donation Form --}}
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
            <a href="{{ route('campaigns.detail', $campaign->slug) }}" class="inline-flex items-center text-white/80 hover:text-white mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-2xl font-bold text-white">Donasi untuk {{ $campaign->title }}</h2>
            <p class="text-emerald-100 text-sm mt-1">Lengkapi form di bawah ini</p>
        </div>

        <div class="p-8 space-y-6">
            {{-- Amount Input --}}
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-3">Nominal Donasi (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                    <input
                        type="text"
                        id="amount-input"
                        wire:model.blur="amount"
                        @input="
                            let value = $event.target.value.replace(/[^0-9]/g, '');
                            if (value) {
                                $event.target.value = new Intl.NumberFormat('id-ID').format(value);
                            } else {
                                $event.target.value = '';
                            }
                        "
                        @focus="$event.target.value = $event.target.value.replace(/\./g, '')"
                        @blur="$event.target.value = $event.target.value ? new Intl.NumberFormat('id-ID').format($event.target.value.replace(/\./g, '')) : ''"
                        placeholder="10.000"
                        class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 text-lg font-bold placeholder-slate-400 transition"
                    >
                </div>
                @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Donor Name --}}
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-3">Nama Donatur</label>
                <input
                    type="text"
                    wire:model="donatur_name"
                    placeholder="Nama Lengkap"
                    class="w-full p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 placeholder-slate-400 transition"
                    :disabled="{{ $is_anonymous ? 'true' : 'false' }}"
                >
                @error('donatur_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- WhatsApp --}}
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-3">Nomor WhatsApp</label>
                <input
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    wire:model.live="phone"
                    @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                    placeholder="Silahkan masukkan nomor WA"
                    class="w-full p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 placeholder-slate-400 transition"
                >
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Anonymous Toggle --}}
            <label class="flex items-center space-x-3 cursor-pointer p-4 bg-slate-50 rounded-xl border-2 border-slate-200 hover:border-emerald-300 transition">
                <input type="checkbox" wire:model="is_anonymous" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-slate-600">Donasi sebagai Hamba Allah (Anonim)</span>
            </label>

            {{-- Bank Selection --}}
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-3">Pilih Rekening</label>
                <select wire:model="payment_method_id" class="w-full p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 transition">
                    <option value="">-- Pilih Bank --</option>
                    @foreach($banks as $b)
                        <option value="{{$b->id}}">{{$b->bank_name}} - {{$b->account_number}}</option>
                    @endforeach
                </select>
                @error('payment_method_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Next Button --}}
            <button wire:click="goToStep2" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                Lanjutkan Pembayaran
            </button>

            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    @elseif($step === 2)
    {{-- Step 2: Confirmation --}}
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Konfirmasi Pembayaran</h2>
            <p class="text-emerald-100 text-sm mt-1">Pastikan data sudah benar</p>
        </div>

        <div class="p-8 space-y-6">
            {{-- Summary --}}
            <div class="bg-slate-50 rounded-2xl p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-slate-500">Kampanye</span>
                    <span class="font-bold text-slate-800 text-right">{{ $campaign->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Nominal</span>
                    <span class="font-bold text-emerald-600">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Nama Donatur</span>
                    <span class="font-bold text-slate-800">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">WhatsApp</span>
                    <span class="font-bold text-slate-800">{{ $phone }}</span>
                </div>
            </div>

            {{-- Upload Bukti --}}
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-3">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center">
                    <input type="file" wire:model.live="proof_of_transfer" accept="image/*" class="hidden" id="proof-upload">
                    <label for="proof-upload" class="cursor-pointer">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-slate-600 font-semibold">Klik untuk upload gambar</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG max 2MB</p>
                    </label>
                    @error('proof_of_transfer') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if($proof_of_transfer)
                    <div class="mt-4 bg-emerald-50 rounded-lg p-3">
                        <p class="text-xs text-emerald-800 font-semibold">✓ File terpilih: {{ $proof_of_transfer->getClientOriginalName() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="space-y-3">
                <button wire:click="submitDonation" wire:loading.attr="disabled" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg disabled:opacity-50">
                    <span wire:loading.remove>Konfirmasi Pembayaran</span>
                    <span wire:loading>Memproses...</span>
                </button>
                <button wire:click="backToStep1" class="w-full py-4 bg-white text-slate-600 rounded-2xl font-bold border-2 border-slate-200 hover:border-emerald-300 transition">
                    ← Kembali
                </button>
            </div>
        </div>
    </div>

    @elseif($step === 3)
    {{-- Step 3: Success --}}
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-emerald-800 mb-2">Jazakumullah Khairan!</h2>
        <p class="text-slate-600 mb-6">Donasi kampanye Anda telah berhasil dikirim</p>

        <div class="bg-emerald-50 rounded-2xl p-6 mb-6">
            <p class="text-sm text-slate-500 mb-1">Kode Transaksi</p>
            <p class="text-2xl font-mono font-bold text-emerald-700">{{ $trx_id }}</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('campaigns.detail', $campaign->slug) }}" class="block w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold">
                Lihat Kampanye
            </a>
            <a href="{{ route('campaigns.index') }}" class="block w-full py-4 bg-white text-slate-600 rounded-2xl font-bold border-2 border-slate-200">
                Lihat Kampanye Lain
            </a>
        </div>
    </div>
    @endif
</div>
