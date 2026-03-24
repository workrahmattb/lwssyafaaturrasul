<div class="max-w-6xl mx-auto px-6 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-emerald-800 mb-4">Kampanye Donasi</h1>
        <p class="text-xl text-slate-600">Pilih kampanye yang ingin Anda dukung</p>
    </div>

    {{-- Campaigns Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($campaigns as $campaign)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group hover:scale-[1.02]">
            {{-- Image --}}
            @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
            @else
            <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                <svg class="w-14 h-14 text-emerald-300/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            @endif

            <div class="p-5">
                {{-- Title --}}
                <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2">{{ $campaign->title }}</h3>
                
                {{-- Short Description --}}
                <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $campaign->short_description ?? Str::limit($campaign->description, 100) }}</p>

                {{-- Progress --}}
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-slate-500 font-medium">Terkumpul</span>
                        <span class="font-bold text-emerald-600">{{ number_format($campaign->progress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-full rounded-full transition-all duration-500" style="width: {{ $campaign->progress }}%"></div>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Terkumpul</p>
                        <p class="font-bold text-emerald-600">Rp {{ number_format($campaign->total_approved_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Target</p>
                        <p class="font-semibold text-slate-600">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Donors & Time --}}
                <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                    <span class="flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $campaign->total_approved_donors }} Donatur
                    </span>
                    @if($campaign->end_date)
                    <span class="flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $campaign->end_date->diffForHumans() }}
                    </span>
                    @endif
                </div>

                {{-- CTA Button --}}
                <a href="{{ route('campaigns.detail', $campaign->slug) }}" wire:navigate class="block w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold text-center hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                    Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-slate-500 text-lg font-semibold">Belum ada kampanye aktif</p>
            <p class="text-slate-400 text-sm mt-2">Kampanye baru akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($campaigns->hasPages())
    <div class="mt-12">
        {{ $campaigns->links() }}
    </div>
    @endif
</div>
