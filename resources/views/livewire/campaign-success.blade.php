<x-layouts.campaign>
<div class="max-w-lg mx-auto px-6 py-12 text-center">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8">
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-emerald-800 mb-2">Jazakumullah Khairan!</h2>
        <p class="text-slate-600 mb-6">Donasi kampanye Anda telah berhasil dikirim</p>

        <div class="bg-emerald-50 rounded-2xl p-6 mb-6">
            <p class="text-sm text-slate-500 mb-1">Kode Transaksi</p>
            <p class="text-2xl font-mono font-bold text-emerald-700">{{ $trx }}</p>
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
</div>
</x-layouts.campaign>
