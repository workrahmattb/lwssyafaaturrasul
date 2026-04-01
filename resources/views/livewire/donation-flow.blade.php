<div class="text-slate-800">
    @if($step === 1)
    {{-- Step 1: Donation Form --}}
    <div class="p-4 sm:p-8 space-y-4 sm:space-y-6">
        {{-- Type Selection --}}
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-3">Jenis Wakaf</label>
            <div class="grid grid-cols-3 gap-2">
                <button
                    type="button"
                    wire:click="$set('type', 'wakaf_pembangunan')"
                    class="py-3 px-2 rounded-xl border-2 {{ $type == 'wakaf_pembangunan' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-500 hover:border-emerald-300' }} font-semibold transition-all duration-200 text-xs leading-tight flex flex-col items-center justify-center gap-1"
                >
                    <span class="text-center">Wakaf<br>Pembangunan</span>
                </button>
                <button
                    type="button"
                    wire:click="$set('type', 'wakaf_produktif')"
                    class="py-3 px-2 rounded-xl border-2 {{ $type == 'wakaf_produktif' ? 'border-teal-500 bg-teal-50 text-teal-700' : 'border-slate-200 text-slate-500 hover:border-emerald-300' }} font-semibold transition-all duration-200 text-xs leading-tight flex flex-col items-center justify-center gap-1"
                >
                    <span class="text-center">Wakaf<br>Produktif</span>
                </button>
                <button
                    type="button"
                    wire:click="$set('type', 'donasi_pendidikan')"
                    class="py-3 px-2 rounded-xl border-2 {{ $type == 'donasi_pendidikan' ? 'border-cyan-500 bg-cyan-50 text-cyan-700' : 'border-slate-200 text-slate-500 hover:border-emerald-300' }} font-semibold transition-all duration-200 text-xs leading-tight flex flex-col items-center justify-center gap-1"
                >
                    <span class="text-center">Donasi<br>Pendidikan</span>
                </button>
            </div>
        </div>

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

        {{-- Donor Name Input --}}
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-3">Nama Donatur</label>
            <input
                type="text"
                wire:model.live="donatur_name"
                placeholder="Nama Lengkap"
                class="w-full p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 placeholder-slate-400 transition"
                :disabled="{{ $is_anonymous ? 'true' : 'false' }}"
            >
            @error('donatur_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- WhatsApp Input --}}
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-3">Nomor WhatsApp</label>
            <input
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                wire:model.live="phone"
                @keydown="if (!/[0-9]/.test($event.key) && !['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab'].includes($event.key)) { $event.preventDefault(); }"
                @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                placeholder="Silahkan masukkan nomor WA"
                class="w-full p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-0 placeholder-slate-400 transition"
            >
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Anonymous Toggle --}}
        <label class="flex items-center space-x-3 cursor-pointer p-4 bg-slate-50 rounded-xl border-2 border-slate-200 hover:border-emerald-300 transition">
            <input
                type="checkbox"
                wire:model="is_anonymous"
                class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
            >
            <span class="text-slate-600">Donasi sebagai Hamba Allah (Anonim)</span>
        </label>

        {{-- Bank Selection --}}
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-3">Pilih Rekening</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach($banks as $bank)
                <label
                    wire:click="$set('payment_method_id', {{ $bank->id }})"
                    class="relative flex flex-col items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                        {{ $payment_method_id == $bank->id
                            ? 'border-emerald-500 bg-emerald-50 shadow-lg shadow-emerald-500/10'
                            : 'border-slate-200 bg-white hover:border-emerald-300' }}"
                >
                    {{-- Radio Button (Hidden) --}}
                    <input
                        type="radio"
                        name="payment_method"
                        value="{{ $bank->id }}"
                        class="sr-only"
                        {{ $payment_method_id == $bank->id ? 'checked' : '' }}
                    >

                    {{-- Checkmark Badge --}}
                    @if($payment_method_id == $bank->id)
                    <div class="absolute top-2 right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    @endif

                    {{-- Bank Logo --}}
                    <div class="w-full flex items-center justify-center mb-3">
                        @if($bank->logo)
                        <img src="{{ asset('storage/' . $bank->logo) }}" alt="{{ $bank->bank_name }}" class="h-12 w-auto object-contain">
                        @else
                        <div class="h-12 w-20 bg-slate-100 rounded-lg flex items-center justify-center">
                            <span class="text-xs text-slate-400 font-medium">Logo</span>
                        </div>
                        @endif
                    </div>

                    {{-- Bank Name --}}
                    <p class="font-bold text-slate-800 text-xs text-center truncate w-full">{{ $bank->bank_name }}</p>
                </label>
                @endforeach
            </div>
            @error('payment_method_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Next Button --}}
        <button
            wire:click="goToStep2"
            wire:loading.attr="disabled"
            wire:target="goToStep2"
            class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="goToStep2">Lanjutkan Pembayaran</span>
            <span wire:loading wire:target="goToStep2" class="flex items-center justify-center space-x-2">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            </span>
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

    @elseif($step === 2)
    {{-- Step 2: Confirmation & Upload --}}
    <div class="p-4 sm:p-8 space-y-4 sm:space-y-6" x-data="{ copied: '' }">
        <div class="text-center mb-4 sm:mb-6">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-emerald-800">Konfirmasi Pembayaran</h3>
            <p class="text-sm text-slate-500">Pastikan data sudah benar sebelum konfirmasi</p>
        </div>

        {{-- Summary Card --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 space-y-4 sm:space-y-5 border border-slate-200 shadow-sm">
            {{-- Jenis Donasi --}}
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500 font-medium">Jenis Donasi:</span>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold
                    @if($type === 'wakaf_pembangunan') bg-emerald-100 text-emerald-700
                    @elseif($type === 'wakaf_produktif') bg-teal-100 text-teal-700
                    @else bg-cyan-100 text-cyan-700
                    @endif
                ">
                    @if($type === 'wakaf_pembangunan') Wakaf Pembangunan
                    @elseif($type === 'wakaf_produktif') Wakaf Produktif
                    @else Donasi Pendidikan
                    @endif
                </span>
            </div>
            <hr class="border-slate-100">

            {{-- Nominal --}}
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500 font-medium">Nominal:</span>
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <span class="font-bold text-emerald-600 text-base">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    <button
                        @click="
                            navigator.clipboard.writeText('{{ $amount }}');
                            copied = 'amount';
                            setTimeout(() => copied = '', 2000);
                        "
                        class="text-xs px-2 sm:px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg font-semibold hover:bg-emerald-100 transition flex items-center space-x-1"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="copied !== 'amount'">Salin</span>
                        <span x-show="copied === 'amount'" x-cloak>Tersalin!</span>
                    </button>
                </div>
            </div>
            <hr class="border-slate-100">

            {{-- Nama Donatur --}}
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500 font-medium">Nama Donatur:</span>
                <span class="font-bold text-slate-800 text-sm sm:text-base text-right max-w-[120px] sm:max-w-none truncate">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</span>
            </div>
            <hr class="border-slate-100">

            {{-- Nomor WhatsApp --}}
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500 font-medium">Nomor WhatsApp:</span>
                <span class="font-bold text-slate-800 text-sm sm:text-base">{{ $phone }}</span>
            </div>
            <hr class="border-slate-100">

            {{-- Bank Tujuan --}}
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500 font-medium">Bank Tujuan:</span>
                <div class="flex items-center space-x-2">
                    @if($selectedBank)
                        @if($selectedBank->logo)
                        <img src="{{ asset('storage/' . $selectedBank->logo) }}" alt="{{ $selectedBank->bank_name }}" class="h-6 w-auto object-contain">
                        @endif
                        <span class="font-bold text-slate-800 text-sm sm:text-base">{{ $selectedBank->bank_name }}</span>
                    @else
                        <span class="font-bold text-slate-800 text-sm sm:text-base">-</span>
                    @endif
                </div>
            </div>
            <hr class="border-slate-100">

            {{-- Transfer ke --}}
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-slate-500 font-medium">Transfer ke:</span>
                    <button
                        @click="
                            navigator.clipboard.writeText('{{$selectedBank->account_number}}');
                            copied = 'bank';
                            setTimeout(() => copied = '', 2000);
                        "
                        class="text-xs px-2 sm:px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg font-semibold hover:bg-slate-200 transition flex items-center space-x-1"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="copied !== 'bank'">Salin</span>
                        <span x-show="copied === 'bank'" x-cloak>Tersalin!</span>
                    </button>
                </div>
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-xl p-3 sm:p-4 text-white">
                    <p class="text-xs text-emerald-400 uppercase font-bold mb-1">{{$selectedBank->bank_name}}</p>
                    <p class="text-lg sm:text-xl font-mono font-bold tracking-wide">{{$selectedBank->account_number}}</p>
                    <p class="text-xs text-slate-300 mt-1">a.n {{$selectedBank->account_name}}</p>
                </div>
            </div>
        </div>

        {{-- Upload Bukti Transfer --}}
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-3">Upload Bukti Transfer <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 sm:p-6 text-center hover:border-emerald-500 transition">
                <input
                    type="file"
                    wire:model.live="proof_of_transfer"
                    accept="image/*,.pdf"
                    class="hidden"
                    id="proof-upload"
                >
                <label for="proof-upload" class="cursor-pointer">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-slate-600 font-semibold">Klik untuk upload gambar atau PDF</p>
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG, PDF max 2MB</p>
                </label>
                @error('proof_of_transfer') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                @if($proof_of_transfer)
                <div class="mt-4 bg-emerald-50 rounded-lg p-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 text-left min-w-0">
                            <p class="text-xs text-emerald-800 font-semibold">✓ File berhasil dipilih</p>
                            <p class="text-xs text-emerald-600 truncate">{{ $proof_of_transfer->getClientOriginalName() }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Loading indicator for file upload --}}
                <div wire:loading wire:target="proof_of_transfer" class="mt-4">
                    <div class="flex items-center justify-center space-x-2 text-emerald-600">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-medium">Uploading...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-3">
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

            <button
                wire:click="submitDonation"
                wire:loading.attr="disabled"
                wire:target="submitDonation"
                class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="submitDonation">Konfirmasi Pembayaran</span>
                <span wire:loading wire:target="submitDonation" class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                </span>
            </button>
            <button
                wire:click="backToStep1"
                class="w-full py-4 bg-white text-slate-600 rounded-2xl font-bold border-2 border-slate-200 hover:border-emerald-300 transition"
            >
                ← Kembali
            </button>
        </div>
    </div>

    @elseif($step === 3)
    {{-- Step 3: Success --}}
    <div class="p-4 sm:p-8 space-y-4 sm:space-y-6">
        {{-- Success Header --}}
        <div class="text-center">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-2xl sm:text-3xl font-black text-emerald-800 mb-2">Jazakumullah Khairan!</h3>
            <p class="text-sm sm:text-base text-slate-600">Donasi Anda telah berhasil dikirim</p>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Semoga Allah SWT membalas kebaikan Anda</p>
        </div>

        {{-- Thank You Card --}}
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-4 sm:p-6 border-2 border-emerald-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0">
                    {{ substr($is_anonymous ? 'Hamba Allah' : $donatur_name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">Donatur</p>
                    <p class="text-base sm:text-lg font-bold text-slate-800 truncate">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</p>
                </div>
            </div>

            <hr class="border-emerald-200 my-4">

            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-xs sm:text-sm text-slate-500">Jenis Donasi:</span>
                    <span class="px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold
                        @if($type === 'wakaf_pembangunan') bg-emerald-100 text-emerald-700
                        @elseif($type === 'wakaf_produktif') bg-teal-100 text-teal-700
                        @else bg-cyan-100 text-cyan-700
                        @endif
                    ">
                        @if($type === 'wakaf_pembangunan') Wakaf Pembangunan
                        @elseif($type === 'wakaf_produktif') Wakaf Produktif
                        @else Donasi Pendidikan
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-500">Nominal:</span>
                    <span class="text-lg sm:text-xl font-black text-emerald-600">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-500">Kode Transaksi:</span>
                    <span class="font-mono font-bold text-slate-700 text-xs sm:text-sm truncate max-w-[120px] sm:max-w-none">{{ $trx_id }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-500">Tanggal:</span>
                    <span class="font-medium text-slate-700 text-xs sm:text-sm">{{ now()->format('d F Y, H:i') }} WIB</span>
                </div>
            </div>
        </div>

        {{-- Bank Transfer Info --}}
        @if($selectedBank)
        <div class="bg-slate-800 rounded-2xl p-4 sm:p-6 text-white">
            <p class="text-xs text-emerald-400 uppercase font-bold mb-1">Transfer ke</p>
            <div class="mb-4">
                <p class="text-xl sm:text-2xl font-mono font-bold tracking-wide">{{ $selectedBank->account_number }}</p>
                <p class="text-xs sm:text-sm text-slate-300 mt-1">a.n {{ $selectedBank->account_name }}</p>
            </div>
            <p class="text-xs text-emerald-400 font-bold mb-1">{{ $selectedBank->bank_name }}</p>

            <button
                @click="
                    navigator.clipboard.writeText('{{ $selectedBank->account_number }}');
                    $el.textContent = 'Tersalin!';
                    setTimeout(() => $el.textContent = 'Salin No. Rekening', 2000);
                "
                class="mt-4 w-full py-3 bg-white/10 hover:bg-white/20 rounded-xl font-semibold text-sm transition flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>Salin No. Rekening</span>
            </button>
        </div>
        @endif

        {{-- Status Info --}}
        <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-amber-800">
                    <p class="font-semibold mb-1">Status Donasi: Pending</p>
                    <p class="text-xs text-amber-700">Donasi Anda akan segera diverifikasi oleh admin. Terima kasih atas kesabaran dan kebaikan Anda.</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-3 pt-4">
            <a href="{{ route('donation') }}" class="block w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 text-center">
                Donasi Lagi
            </a>
            <a href="/" class="block w-full py-4 bg-white text-slate-600 rounded-2xl font-bold border-2 border-slate-200 hover:border-emerald-300 transition text-center">
                ← Kembali ke Beranda
            </a>
        </div>

        {{-- Riwayat Donasi Table --}}
        <div class="mt-8">
            <h4 class="text-lg font-bold text-slate-800 mb-4 text-center">Riwayat Donasi Terbaru</h4>
            <div class="overflow-x-auto overflow-y-auto max-h-80 rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-xs font-bold uppercase sticky top-0">
                        <tr>
                            <th class="p-3 text-left whitespace-nowrap">No</th>
                            <th class="p-3 text-left whitespace-nowrap">Donatur</th>
                            <th class="p-3 text-left whitespace-nowrap">Tipe</th>
                            <th class="p-3 text-right whitespace-nowrap">Jumlah</th>
                            <th class="p-3 text-right whitespace-nowrap">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($history as $index => $h)
                        <tr class="hover:bg-emerald-50 transition">
                            <td class="p-3 text-center font-bold text-slate-600">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-slate-700">{{ $h->donatur_name }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                    @if($h->type === 'wakaf_pembangunan') bg-emerald-100 text-emerald-700
                                    @elseif($h->type === 'wakaf_produktif') bg-teal-100 text-teal-700
                                    @else bg-cyan-100 text-cyan-700
                                    @endif
                                ">
                                    @if($h->type === 'wakaf_pembangunan') Wakaf Pembangunan
                                    @elseif($h->type === 'wakaf_produktif') Wakaf Produktif
                                    @else Donasi Pendidikan
                                    @endif
                                </span>
                            </td>
                            <td class="p-3 text-right font-bold text-emerald-600">Rp {{ number_format($h->amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-right text-slate-500 text-xs">{{ $h->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">Belum ada donasi yang disetujui.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
