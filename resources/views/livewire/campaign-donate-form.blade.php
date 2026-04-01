<div>
    {{-- ==================== STEP 1: ISI DATA ==================== --}}
    @if($step === 1)
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">

        {{-- Section Header --}}
        <div class="pb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-bold text-white" style="background: linear-gradient(135deg, #10b981, #0d9488);">1</span>
                Informasi Donasi
            </h3>
            <p class="text-slate-500 text-sm mt-1">Lengkapi data di bawah untuk melanjutkan</p>
        </div>

        {{-- Nominal Donasi --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-3">
                Nominal Donasi
            </label>

            {{-- Custom Amount Input --}}
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-600 font-bold text-sm pointer-events-none">Rp</span>
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
                    placeholder="Masukkan nominal lain..."
                    class="w-full pl-10 pr-4 py-3.5 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 border-2 transition-all duration-200 outline-none focus:ring-0 focus:border-emerald-500"
                    style="background: #ffffff; border-color: #e2e8f0;"
                >
            </div>
            @error('amount')
            <p class="mt-2 text-xs text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Nama Donatur --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-2">
                Nama Donatur
                @if($is_anonymous)
                <span class="ml-2 text-xs text-slate-400 font-normal">(disembunyikan)</span>
                @endif
            </label>
            <div class="relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <input
                    type="text"
                    wire:model="donatur_name"
                    placeholder="{{ $is_anonymous ? 'Hamba Allah' : 'Masukkan nama lengkap Anda' }}"
                    :disabled="{{ $is_anonymous ? 'true' : 'false' }}"
                    class="w-full pl-10 pr-4 py-3.5 rounded-xl text-sm text-slate-900 placeholder-slate-400 border-2 transition-all duration-200 outline-none focus:ring-0 focus:border-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background: #ffffff; border-color: #e2e8f0;"
                >
            </div>
            @error('donatur_name')
            <p class="mt-2 text-xs text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- WhatsApp --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-2">Nomor WhatsApp</label>
            <div class="relative">
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center gap-1.5 pointer-events-none">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#25D366">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span class="text-slate-400 text-xs">|</span>
                </div>
                <input
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    wire:model.live="phone"
                    @keydown="if (!/[0-9]/.test($event.key) && !['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', 'Delete'].includes($event.key)) { $event.preventDefault(); }"
                    @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                    placeholder="08xxxxxxxxxx"
                    class="w-full pl-14 pr-4 py-3.5 rounded-xl text-sm text-slate-900 placeholder-slate-400 border-2 transition-all duration-200 outline-none focus:ring-0 focus:border-emerald-500"
                    style="background: #ffffff; border-color: #e2e8f0;"
                >
            </div>
            @error('phone')
            <p class="mt-2 text-xs text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Anonymous Toggle --}}
        <div wire:click="$toggle('is_anonymous')"
             class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer group transition-all duration-200
                {{ $is_anonymous ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-emerald-300 bg-slate-50' }}">
            <div class="relative flex-shrink-0">
                <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all duration-200
                    {{ $is_anonymous
                        ? 'bg-emerald-500 border-emerald-500'
                        : 'bg-white border-slate-300 group-hover:border-emerald-400' }}">
                    <svg class="w-4 h-4 text-white {{ $is_anonymous ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold {{ $is_anonymous ? 'text-emerald-700' : 'text-slate-700' }} transition-colors">
                    Donasi sebagai Hamba Allah
                </p>
                <p class="text-xs text-slate-500 mt-0.5">Identitas Anda akan disembunyikan dari publik</p>
            </div>
        </div>

        {{-- Bank Selection --}}
        <div>
            <label class="block text-sm font-semibold text-slate-200 mb-3">Pilih Rekening Tujuan</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach($banks as $bank)
                <div wire:click="$set('payment_method_id', {{ $bank->id }})"
                     class="relative flex flex-col items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                        {{ $payment_method_id == $bank->id
                            ? 'border-emerald-500 bg-emerald-50'
                            : 'border-slate-200 hover:border-emerald-300 bg-white' }}"
                       >
                    @if($payment_method_id == $bank->id)
                    <div class="absolute top-2 right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center z-10 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    @endif

                    <div class="w-full flex items-center justify-center mb-3">
                        @if($bank->logo)
                        <img src="{{ asset('storage/' . $bank->logo) }}" alt="{{ $bank->bank_name }}" class="h-12 w-auto object-contain">
                        @else
                        <div class="h-12 w-20 bg-slate-100 rounded-lg flex items-center justify-center">
                            <span class="text-xs text-slate-400 font-medium">Logo</span>
                        </div>
                        @endif
                    </div>

                    <p class="font-bold text-slate-800 text-xs text-center truncate w-full">{{ $bank->bank_name }}</p>
                </div>
                @endforeach
            </div>
            @error('payment_method_id')
            <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Errors summary --}}
        @if($errors->any())
        <div class="rounded-xl p-4 border border-red-200 bg-red-50">
            <ul class="text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                <li class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- CTA Button --}}
        <button
            type="button"
            wire:click="goToStep2"
            wire:loading.attr="disabled"
            wire:target="goToStep2"
            class="w-full py-4 rounded-xl font-bold text-white text-sm tracking-wide flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100"
            style="background: linear-gradient(135deg, #10b981, #0d9488);"
        >
            <span wire:loading.remove wire:target="goToStep2">Lanjutkan Pembayaran</span>
            <svg wire:loading.remove wire:target="goToStep2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
            <svg wire:loading wire:target="goToStep2" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading wire:target="goToStep2">Memproses...</span>
        </button>
    </div>


    {{-- ==================== STEP 2: KONFIRMASI ==================== --}}
    @elseif($step === 2)
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">

        {{-- Section Header --}}
        <div class="pb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sm font-bold text-white" style="background: linear-gradient(135deg, #10b981, #0d9488);">2</span>
                Konfirmasi & Pembayaran
            </h3>
            <p class="text-slate-500 text-sm mt-1">Transfer sesuai nominal dan upload bukti transfer</p>
        </div>

        {{-- Transaction ID --}}
        <div class="rounded-xl p-4 flex items-center justify-between border border-emerald-200 bg-emerald-50">
            <div>
                <p class="text-xs text-slate-500 mb-0.5">Kode Transaksi</p>
                <p class="font-mono font-bold text-emerald-700 text-lg tracking-widest">{{ $trx_id }}</p>
            </div>
        </div>

        {{-- Donation Summary Card --}}
        <div class="rounded-xl overflow-hidden border border-slate-200 bg-white">
            <div class="px-5 py-3 border-b border-slate-100 bg-emerald-50">
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Ringkasan Donasi</p>
            </div>
            <div class="p-5 space-y-4 bg-white">
                {{-- Kampanye --}}
                <div class="flex justify-between items-start text-sm">
                    <span class="text-slate-500">Kampanye</span>
                    <span class="font-semibold text-slate-800 text-right max-w-[60%] leading-snug truncate">{{ $campaign->title }}</span>
                </div>

                {{-- Donatur --}}
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Donatur</span>
                    <span class="font-semibold text-slate-800">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</span>
                </div>

                {{-- WhatsApp --}}
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">WhatsApp</span>
                    <span class="font-semibold text-slate-800 font-mono">{{ $phone }}</span>
                </div>

                {{-- Nominal Donasi dengan Copy --}}
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Nominal Donasi</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-emerald-600">{{ formatRupiah($amount) }}</span>
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $amount }}').then(() => { this.innerHTML = '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'; setTimeout(() => this.innerHTML = '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg>', 2000) })"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-emerald-600 border border-slate-200 hover:border-emerald-500 transition-all duration-200 bg-slate-50"
                                title="Salin nominal">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Bank Tujuan --}}
                @php
                    $selectedBank = $banks->find($payment_method_id);
                @endphp
                @if($selectedBank)
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-slate-500">Bank Tujuan</span>
                        <span class="font-semibold text-slate-800">{{ $selectedBank->bank_name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Nomor Rekening</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-semibold text-slate-800">{{ $selectedBank->account_number }}</span>
                            <button type="button"
                                    onclick="navigator.clipboard.writeText('{{ $selectedBank->account_number }}').then(() => { this.innerHTML = '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'; setTimeout(() => this.innerHTML = '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg>', 2000) })"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-emerald-600 border border-slate-200 hover:border-emerald-500 transition-all duration-200 bg-slate-50"
                                    title="Salin nomor rekening">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @if($selectedBank->account_name)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Atas Nama</span>
                        <span class="font-semibold text-slate-800">{{ $selectedBank->account_name }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Proof of Transfer Upload --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-3">
                Upload Bukti Transfer
                <span class="text-xs text-slate-500 font-normal ml-1">(JPG/PNG/PDF, max 2MB)</span>
            </label>

            {{-- Upload Area --}}
            <div class="relative">
                <input
                    type="file"
                    wire:model.live="proof_of_transfer"
                    accept="image/*,.pdf"
                    id="proof-upload-step2"
                    class="hidden"
                >
                <label for="proof-upload-step2"
                       class="flex flex-col items-center justify-center gap-3 p-6 rounded-xl border-2 border-dashed cursor-pointer transition-all duration-200 group
                           {{ $proof_of_transfer ? 'border-emerald-400 bg-emerald-50' : 'border-slate-300 hover:border-emerald-400 bg-slate-50' }}">

                    @if($proof_of_transfer)
                        {{-- File uploaded successfully --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-100">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-emerald-700">File berhasil diupload</p>
                            <p class="text-xs text-slate-500 mt-0.5 max-w-[200px] truncate">
                                @if(is_object($proof_of_transfer) && method_exists($proof_of_transfer, 'getClientOriginalName'))
                                    {{ $proof_of_transfer->getClientOriginalName() }}
                                @else
                                    {{ basename($proof_of_transfer) }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-slate-600 hover:text-slate-800 border border-slate-300 px-3 py-1 rounded-full transition-colors bg-white">
                            Ganti File
                        </span>
                    @else
                        {{-- No file selected --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center border-2 border-slate-200 bg-white group-hover:border-emerald-400">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Klik untuk upload bukti</p>
                            <p class="text-xs text-slate-500 mt-0.5">JPG/PNG/PDF, max 2MB</p>
                        </div>
                    @endif
                </label>
            </div>

            {{-- Upload Progress --}}
            <div wire:loading wire:target="proof_of_transfer" class="mt-3">
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-500 h-2 rounded-full animate-pulse" style="width: 50%;"></div>
                </div>
            </div>

            @error('proof_of_transfer')
            <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-3 pt-2">
            <button
                type="button"
                wire:click="submitDonation"
                wire:loading.attr="disabled"
                wire:target="submitDonation"
                wire:disabled="!proof_of_transfer"
                class="w-full py-4 rounded-xl font-bold text-white text-sm tracking-wide flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100 disabled:shadow-none"
                style="background: linear-gradient(135deg, #10b981, #0d9488);"
            >
                <svg wire:loading.remove wire:target="submitDonation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span wire:loading.remove wire:target="submitDonation">Konfirmasi Pembayaran</span>
                <svg wire:loading wire:target="submitDonation" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span wire:loading wire:target="submitDonation">Memproses...</span>
            </button>
            <button
                type="button"
                wire:click="backToStep1"
                class="w-full py-3.5 rounded-xl font-semibold text-slate-600 text-sm flex items-center justify-center gap-2 border border-slate-300 hover:border-slate-400 hover:text-slate-700 hover:bg-slate-50 transition-all duration-200 bg-white"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali & Edit Data
            </button>
        </div>
    </div>


    {{-- ==================== STEP 3: SUKSES ==================== --}}
    @elseif($step === 3)
    <div class="p-4 sm:p-8 space-y-4 sm:space-y-6">

        {{-- Success Header --}}
        <div class="text-center">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg"
                 style="background: linear-gradient(135deg, #10b981, #0d9488);">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">Alhamdulillah! 🎉</h3>
            <p class="text-sm sm:text-base text-slate-600">Donasi kampanye Anda berhasil diajukan</p>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Terima kasih atas kepedulian Anda</p>
        </div>

        {{-- Thank You Card --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-emerald-200 bg-emerald-50">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0"
                     style="background: linear-gradient(135deg, #10b981, #0d9488);">
                    {{ substr($is_anonymous ? 'Hamba Allah' : $donatur_name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm text-slate-600 font-medium">Donatur</p>
                    <p class="text-base sm:text-lg font-bold text-slate-800 truncate">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</p>
                </div>
            </div>

            <hr class="border-emerald-200 my-4">

            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-xs sm:text-sm text-slate-600">Kampanye</span>
                    <span class="font-medium text-slate-800 text-xs sm:text-sm text-right truncate max-w-[60%]">{{ $campaign->title }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-600">Nominal</span>
                    <span class="text-lg sm:text-xl font-black text-emerald-600">{{ formatRupiah($amount) }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-600">Kode Transaksi</span>
                    <span class="font-mono font-bold text-slate-800 text-xs sm:text-sm truncate max-w-[120px] sm:max-w-none">{{ $trx_id }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-slate-600">Tanggal</span>
                    <span class="font-medium text-slate-700 text-xs sm:text-sm">{{ now()->format('d F Y, H:i') }} WIB</span>
                </div>
            </div>
        </div>

        {{-- Bank Transfer Info --}}
        @if($selectedBank = \App\Models\PaymentMethod::find($payment_method_id))
        <div class="rounded-2xl p-4 sm:p-6 bg-slate-800 text-white">
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
        <div class="bg-amber-100 border border-amber-300 rounded-xl p-4">
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
            <a href="{{ route('campaigns.detail', $campaign->slug) }}"
               class="block w-full py-4 rounded-xl font-bold text-white text-sm tracking-wide text-center shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200"
               style="background: linear-gradient(135deg, #10b981, #0d9488);">
                🕌 Kembali ke Halaman Kampanye
            </a>
            <button
                type="button"
                wire:click="backToStep1"
                class="w-full py-3.5 rounded-xl font-semibold text-slate-700 text-sm border border-slate-300 hover:border-slate-400 hover:text-slate-800 hover:bg-slate-50 transition-all duration-200 bg-white"
            >
                ＋ Donasi Lagi
            </button>
        </div>
    </div>
    @endif
</div>
