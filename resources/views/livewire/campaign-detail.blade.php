<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-6 px-4">
    <div class="max-w-3xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-5">
            <a href="{{ route('donation') }}" wire:navigate class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium text-sm transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        {{-- Campaign Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 border border-emerald-100/50">
            {{-- Image --}}
            @if($campaign->image)
            <div class="relative">
                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 sm:h-56 object-cover">
                @if($campaign->is_featured)
                <span class="absolute top-3 left-3 px-3 py-1.5 bg-yellow-400/95 text-yellow-900 text-xs font-bold rounded-full shadow-lg backdrop-blur-sm">
                    ⭐ Unggulan
                </span>
                @endif
            </div>
            @else
            <div class="w-full h-48 sm:h-56 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                <svg class="w-16 h-16 text-emerald-300/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            @endif

            <div class="p-5">
                {{-- Status Badge --}}
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                        @if($campaign->status === 'active') bg-emerald-100 text-emerald-700
                        @else bg-slate-100 text-slate-700
                        @endif
                    ">
                        <span class="w-2 h-2 rounded-full bg-current mr-2 animate-pulse"></span>
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-bold text-slate-800 mb-3 leading-tight">{{ $campaign->title }}</h1>

                {{-- Short Description --}}
                @if($campaign->short_description)
                <p class="text-sm text-slate-600 mb-5 leading-relaxed">{{ $campaign->short_description }}</p>
                @endif

                {{-- Progress --}}
                <div class="mb-5">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-slate-500 font-semibold">Terkumpul</span>
                        <span class="font-bold text-emerald-600">{{ number_format($campaign->progress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-full rounded-full transition-all duration-500" style="width: {{ $campaign->progress }}%"></div>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-4 mb-4 border border-emerald-100">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-emerald-600 mb-1">Rp {{ number_format($campaign->total_approved_amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 font-medium">dari target Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="text-center p-3 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200">
                        <p class="text-2xl font-bold text-emerald-600">{{ $campaign->total_approved_donors }}</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Donatur</p>
                    </div>
                    @if($campaign->end_date)
                    <div class="text-center p-3 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200">
                        <p class="text-2xl font-bold text-slate-700">{{ max(0, (int) now()->diffInDays($campaign->end_date)) }}</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Hari Lagi</p>
                    </div>
                    @endif
                </div>

                {{-- Donate Button --}}
                <a href="{{ route('campaigns.donate', $campaign->slug) }}" wire:navigate class="block w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold text-center shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Donasi Sekarang
                </a>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-emerald-100/50">
            <h3 class="text-lg font-bold text-slate-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tentang Kampanye
            </h3>
            <p class="text-sm text-slate-600 whitespace-pre-line leading-relaxed">{{ $campaign->description }}</p>
        </div>

        {{-- Recent Donations --}}
        @if($recentDonations->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-emerald-100/50">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Donatur Terbaru
            </h3>
            <div class="space-y-2.5">
                @foreach($recentDonations as $donation)
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover:border-emerald-200 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr($donation->donatur_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $donation->donatur_name }}</p>
                            <p class="text-xs text-slate-500">{{ $donation->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p class="font-bold text-emerald-600 text-sm">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
