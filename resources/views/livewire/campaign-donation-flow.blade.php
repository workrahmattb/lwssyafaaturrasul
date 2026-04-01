<div class="text-slate-800">
    @if($step === 1)
    {{-- Step 1: Campaign Selection & Donation Form --}}
    <div class="p-6">
        {{-- Campaign Selection --}}
        @if(!$campaignId)
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Kampanye</label>
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-3 text-left font-semibold text-slate-600">Nama Kampanye</th>
                            <th class="p-3 text-right font-semibold text-slate-600">Progress</th>
                            <th class="p-3 text-center font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($campaigns as $camp)
                        <tr class="hover:bg-emerald-50 transition">
                            <td class="p-3">
                                <p class="font-semibold text-slate-800 text-sm line-clamp-1">{{ $camp->title }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Rp {{ number_format($camp->total_approved_amount, 0, ',', '.') }} / 
                                    Rp {{ number_format($camp->target_amount, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="p-3 text-right">
                                <div class="text-xs text-emerald-600 font-bold mb-1">{{ number_format($camp->progress, 1) }}%</div>
                                <div class="w-24 ml-auto bg-slate-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $camp->progress }}%"></div>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                <button
                                    type="button"
                                    wire:click="selectCampaign({{ $camp->id }})"
                                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition"
                                >
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-500">Belum ada kampanye aktif</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- Form Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                    <tr>
                        <th colspan="2" class="p-4 text-center font-bold text-lg">
                            Form Donasi Kampanye
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    {{-- Selected Campaign --}}
                    <tr class="bg-emerald-50">
                        <td class="p-4 font-semibold text-slate-700 w-1/3">Kampanye Terpilih</td>
                        <td class="p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-800">{{ $campaign->title }}</span>
                                <button
                                    type="button"
                                    wire:click="$set('campaignId', null); $set('campaign', null)"
                                    class="text-red-500 hover:text-red-700 transition"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Amount --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nominal Donasi</td>
                        <td class="p-4">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                                <input
                                    type="text"
                                    wire:model.blur="amount"
                                    @input="let value = $event.target.value.replace(/[^0-9]/g, ''); $event.target.value = value ? new Intl.NumberFormat('id-ID').format(value) : ''"
                                    @focus="$event.target.value = $event.target.value.replace(/\./g, '')"
                                    @blur="$event.target.value = $event.target.value ? new Intl.NumberFormat('id-ID').format($event.target.value.replace(/\./g, '')) : ''"
                                    placeholder="10.000"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm font-semibold placeholder-slate-400 transition"
                                >
                            </div>
                            @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </td>
                    </tr>

                    {{-- Donor Name --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nama Donatur</td>
                        <td class="p-4">
                            <input
                                type="text"
                                wire:model="donatur_name"
                                placeholder="Nama Lengkap"
                                :disabled="{{ $is_anonymous ? 'true' : 'false' }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm placeholder-slate-400 transition disabled:opacity-50"
                            >
                            @error('donatur_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </td>
                    </tr>

                    {{-- WhatsApp --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nomor WhatsApp</td>
                        <td class="p-4">
                            <input
                                type="text"
                                inputmode="numeric"
                                wire:model.live="phone"
                                @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm placeholder-slate-400 transition"
                            >
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </td>
                    </tr>

                    {{-- Anonymous Toggle --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Donasi Anonim</td>
                        <td class="p-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="is_anonymous"
                                    class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500"
                                >
                                <span class="text-sm text-slate-600">Sembunyikan identitas saya</span>
                            </label>
                        </td>
                    </tr>

                    {{-- Validation Errors --}}
                    @if($errors->any())
                    <tr>
                        <td colspan="2" class="p-4">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <ul class="text-xs text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- Submit Button --}}
                    <tr>
                        <td colspan="2" class="p-4">
                            <button
                                type="button"
                                wire:click="goToStep2"
                                wire:loading.attr="disabled"
                                wire:target="goToStep2"
                                class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-bold text-sm shadow-lg hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span wire:loading.remove wire:target="goToStep2">Lanjutkan Pembayaran</span>
                                <span wire:loading wire:target="goToStep2" class="flex items-center justify-center space-x-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Memproses...</span>
                                </span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @elseif($step === 2)
    {{-- Step 2: Confirmation --}}
    <div class="p-6">
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                    <tr>
                        <th colspan="2" class="p-4 text-center font-bold text-lg">
                            Konfirmasi & Pembayaran
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    {{-- Transaction ID --}}
                    <tr class="bg-emerald-50">
                        <td class="p-4 font-semibold text-slate-700">Kode Transaksi</td>
                        <td class="p-4">
                            <span class="font-mono font-bold text-emerald-600">{{ $trx_id }}</span>
                        </td>
                    </tr>

                    {{-- Campaign --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Kampanye</td>
                        <td class="p-4 text-slate-800">{{ $campaign->title }}</td>
                    </tr>

                    {{-- Amount --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nominal Donasi</td>
                        <td class="p-4 font-bold text-emerald-600">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Donor Name --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nama Donatur</td>
                        <td class="p-4 text-slate-800">{{ $is_anonymous ? 'Hamba Allah' : $donatur_name }}</td>
                    </tr>

                    {{-- WhatsApp --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Nomor WhatsApp</td>
                        <td class="p-4 text-slate-800">{{ $phone }}</td>
                    </tr>

                    {{-- Payment Method --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Pilih Bank Tujuan</td>
                        <td class="p-4">
                            <div class="space-y-2">
                                @foreach($banks as $bank)
                                <label class="flex items-center justify-between p-3 rounded-lg border-2 {{ $payment_method_id == $bank->id ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-emerald-300' }} cursor-pointer transition">
                                    <div class="flex items-center space-x-3">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            wire:model="payment_method_id"
                                            value="{{ $bank->id }}"
                                            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                        >
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $bank->bank_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $bank->account_number }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600">{{ $bank->account_name }}</p>
                                </label>
                                @endforeach
                            </div>
                            @error('payment_method_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </td>
                    </tr>

                    {{-- Proof of Transfer --}}
                    <tr>
                        <td class="p-4 font-semibold text-slate-700">Bukti Transfer</td>
                        <td class="p-4">
                            <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-emerald-500 transition">
                                <input
                                    type="file"
                                    wire:model="proof_of_transfer"
                                    accept="image/*,.pdf"
                                    id="proof-upload"
                                    class="hidden"
                                >
                                <label for="proof-upload" class="cursor-pointer">
                                    <svg class="w-10 h-10 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-slate-600">
                                        @if($proof_of_transfer)
                                            <span class="text-emerald-600 font-semibold">{{ $proof_of_transfer->getClientOriginalName() }}</span>
                                        @else
                                            Klik untuk upload gambar atau PDF
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">Max. 2MB (JPG, PNG, PDF)</p>
                                </label>
                            </div>
                            @error('proof_of_transfer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </td>
                    </tr>

                    {{-- Action Buttons --}}
                    <tr>
                        <td colspan="2" class="p-4">
                            <div class="space-y-3">
                                <button
                                    type="button"
                                    wire:click="submitDonation"
                                    wire:disabled="!proof_of_transfer"
                                    class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-bold text-sm shadow-lg hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Konfirmasi Pembayaran
                                </button>
                                <button
                                    type="button"
                                    wire:click="backToStep1"
                                    class="w-full py-3 bg-white text-slate-700 rounded-lg font-bold text-sm border-2 border-slate-200 hover:bg-slate-50 transition"
                                >
                                    Kembali
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @elseif($step === 3)
    {{-- Step 3: Success --}}
    <div class="p-6">
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                    <tr>
                        <th colspan="2" class="p-4 text-center font-bold text-lg">
                            Donasi Berhasil!
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    {{-- Success Icon --}}
                    <tr>
                        <td colspan="2" class="p-6 text-center">
                            <div class="w-16 h-16 mx-auto bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-1">Alhamdulillah!</h3>
                            <p class="text-slate-600 text-sm">Donasi kampanye Anda berhasil diajukan</p>
                        </td>
                    </tr>

                    {{-- Transaction Code --}}
                    <tr class="bg-emerald-50">
                        <td class="p-4 font-semibold text-slate-700">Kode Transaksi</td>
                        <td class="p-4">
                            <span class="font-mono font-bold text-emerald-600">{{ $trx_id }}</span>
                        </td>
                    </tr>

                    {{-- Next Steps --}}
                    <tr>
                        <td colspan="2" class="p-4">
                            <p class="font-semibold text-slate-700 mb-2">Langkah Selanjutnya:</p>
                            <ol class="text-sm text-slate-600 space-y-1 list-decimal list-inside">
                                <li>Admin akan memverifikasi donasi Anda</li>
                                <li>Notifikasi akan dikirim via WhatsApp</li>
                                <li>Donasi akan ditampilkan di halaman kampanye</li>
                            </ol>
                        </td>
                    </tr>

                    {{-- Reset Button --}}
                    <tr>
                        <td colspan="2" class="p-4">
                            <button
                                type="button"
                                wire:click="resetForm"
                                class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-bold text-sm shadow-lg hover:shadow-xl transition"
                            >
                                Donasi Lagi
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
