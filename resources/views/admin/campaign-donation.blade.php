<x-layouts.admin>
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow-xl border border-emerald-50 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.donations') }}" class="p-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 transition" title="Kembali ke Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold">Donasi Kampanye</h2>
                    <p class="text-emerald-100 text-sm">Form donasi untuk kampanye aktif</p>
                </div>
            </div>
        </div>

        {{-- Campaign Donation Flow Component --}}
        <livewire:campaign-donation-flow />
    </div>
</div>
</x-layouts.admin>
