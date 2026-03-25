<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lembaga Wakaf Syafa'aturrasul - Salurkan Kebaikan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-emerald-50 via-white to-teal-50 scroll-smooth">
    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/lws.png') }}" alt="LWS Logo" class="w-12 h-12 object-contain">
                    <span class="text-xl font-black text-emerald-700">Lembaga Wakaf Syafa'aturrasul</span>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="py-16 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-black text-emerald-800 mb-6 leading-tight">
                Salurkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Zakat & Wakaf</span> Terbaik Anda
            </h1>
            <p class="text-xl text-slate-600 mb-8 max-w-2xl mx-auto">
                Bantu sesama melalui donasi zakat, wakaf, dan sedekah. Setiap kontribusi Anda membawa harapan bagi mereka yang membutuhkan.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#donasi" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    Wakaf Sekarang
                </a>
                <a href="#riwayat" class="px-8 py-4 bg-white text-emerald-700 font-bold rounded-2xl shadow-md border-2 border-emerald-200 hover:border-emerald-400 transition-all duration-200">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Cards --}}
    <section class="py-8 px-6">
        <div class="max-w-5xl mx-auto">
            @php
                $approvedDonations = \App\Models\Donation::where('status', 'approved');
                $wakafPembangunanTotal = $approvedDonations->clone()->where('type', 'wakaf_pembangunan')->sum('amount');
                $wakafPembangunanCount = $approvedDonations->clone()->where('type', 'wakaf_pembangunan')->count();
                $wakafProduktifTotal = $approvedDonations->clone()->where('type', 'wakaf_produktif')->sum('amount');
                $wakafProduktifCount = $approvedDonations->clone()->where('type', 'wakaf_produktif')->count();
                $donasiPendidikanTotal = $approvedDonations->clone()->where('type', 'donasi_pendidikan')->sum('amount');
                $donasiPendidikanCount = $approvedDonations->clone()->where('type', 'donasi_pendidikan')->count();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Wakaf Pembangunan Card --}}
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl p-6 text-white shadow-xl">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-emerald-100 text-sm font-semibold">Wakaf Pembangunan</p>
                            <p class="text-3xl font-black">Rp {{ number_format($wakafPembangunanTotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-emerald-100 text-xs">{{ $wakafPembangunanCount }} donasi terkumpul</p>
                </div>

                {{-- Wakaf Produktif Card --}}
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-3xl p-6 text-white shadow-xl">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-teal-100 text-sm font-semibold">Wakaf Produktif</p>
                            <p class="text-3xl font-black">Rp {{ number_format($wakafProduktifTotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-teal-100 text-xs">{{ $wakafProduktifCount }} donasi terkumpul</p>
                </div>

                {{-- Donasi Pendidikan Card --}}
                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-3xl p-6 text-white shadow-xl">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-cyan-100 text-sm font-semibold">Donasi Pendidikan</p>
                            <p class="text-3xl font-black">Rp {{ number_format($donasiPendidikanTotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-cyan-100 text-xs">{{ $donasiPendidikanCount }} donasi terkumpul</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Campaigns Section --}}
    <section class="py-12 px-6 bg-gradient-to-br from-emerald-50 via-white to-teal-50">
        <div class="max-w-6xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-black text-emerald-800 mb-3">Kampanye Donasi</h2>
                <p class="text-lg text-slate-600">Dukung kampanye kebaikan yang sedang berlangsung</p>
                <a href="{{ route('campaigns.index') }}" wire:navigate class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-semibold mt-4">
                    Lihat Semua Kampanye
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Campaigns Grid --}}
            @php
                $featuredCampaigns = \App\Models\Campaign::where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($featuredCampaigns as $campaign)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                    {{-- Image --}}
                    @if($campaign->image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition">
                        @if($campaign->is_featured)
                        <span class="absolute top-3 left-3 px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full">
                            ⭐ Unggulan
                        </span>
                        @endif
                    </div>
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                        <svg class="w-20 h-20 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    @endif

                    <div class="p-5">
                        {{-- Title & Status --}}
                        <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2">{{ $campaign->title }}</h3>

                        {{-- Short Description --}}
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $campaign->short_description ?? Str::limit($campaign->description, 80) }}</p>

                        {{-- Progress --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-xs mb-2">
                                <span class="text-slate-500">Terkumpul</span>
                                <span class="font-bold text-emerald-600">{{ number_format($campaign->progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-3 rounded-full transition-all" style="width: {{ $campaign->progress }}%"></div>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-xs text-slate-500">Terkumpul</p>
                                <p class="font-bold text-emerald-600">Rp {{ number_format($campaign->total_approved_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500">Target</p>
                                <p class="font-semibold text-slate-600">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Donors & Time --}}
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ $campaign->total_approved_donors }} Donatur
                            </span>
                            @if($campaign->end_date)
                            <span>{{ $campaign->end_date->diffForHumans() }}</span>
                            @endif
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ route('campaigns.detail', $campaign->slug) }}" wire:navigate class="block w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold text-center hover:shadow-lg transition">
                            Donasi Sekarang
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-20 h-20 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-slate-500 text-lg font-semibold">Belum ada kampanye aktif</p>
                    <p class="text-slate-400 text-sm mt-2">Kampanye baru akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section id="donasi" class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Donation Form --}}
                <div class="bg-white rounded-3xl shadow-2xl border border-emerald-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white text-center">Form Wakaf</h2>
                        <p class="text-emerald-100 text-sm text-center mt-1">Pilih jenis wakaf dan lengkapi form</p>
                    </div>
                    <livewire:donation-flow />
                </div>

                {{-- Info Section --}}
                <div class="space-y-6">
                    {{-- Tentang Wakaf Pembangunan --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-emerald-50">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-emerald-800">Wakaf Pembangunan</h3>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Wakaf pembangunan digunakan untuk membangun fasilitas umum yang bermanfaat bagi umat seperti masjid, sekolah, rumah sakit, dan infrastruktur lainnya.
                        </p>
                    </div>

                    {{-- Tentang Wakaf Produktif --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-teal-50">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-teal-800">Wakaf Produktif</h3>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Wakaf produktif adalah wakaf yang digunakan untuk usaha produktif yang hasilnya disalurkan untuk kepentingan umat. Pahalanya terus mengalir meskipun pemberi wakaf telah meninggal dunia.
                        </p>
                    </div>

                    {{-- Tentang Donasi Pendidikan --}}
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-cyan-50">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-cyan-800">Donasi Pendidikan</h3>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Donasi pendidikan membantu menyediakan akses pendidikan bagi mereka yang kurang mampu. Setiap kontribusi Anda membuka kesempatan bagi generasi muda untuk meraih masa depan lebih baik.
                        </p>
                    </div>

                    {{-- Keunggulan --}}
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl shadow-xl p-8 text-white">
                        <h3 class="text-xl font-bold mb-4">Mengapa Donasi di Sini?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Transparan dan terpercaya</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Saluran pembayaran mudah</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Laporan donasi berkala</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Bisa donasi anonim</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Riwayat Donasi --}}
    <section id="riwayat" class="py-12 px-6 bg-white">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-emerald-800 mb-2">Riwayat Donasi</h2>
                <p class="text-slate-600">Donasi yang telah disalurkan oleh para donatur</p>
            </div>
            <livewire:donation-history />
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/lws.png') }}" alt="LWS Logo" class="w-10 h-10 object-contain bg-white rounded-full p-1">
                        <span class="text-lg font-bold">Lembaga Wakaf Syafa'aturrasul</span>
                    </div>
                    <p class="text-slate-400 text-sm">
                        Menyalurkan kebaikan dari para donatur untuk mereka yang membutuhkan.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Jenis Donasi</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="#donasi" class="hover:text-emerald-400 transition">Wakaf Pembangunan</a></li>
                        <li><a href="#donasi" class="hover:text-emerald-400 transition">Wakaf Produktif</a></li>
                        <li><a href="#donasi" class="hover:text-emerald-400 transition">Donasi Pendidikan</a></li>
                        <li><a href="{{ route('campaigns.index') }}" wire:navigate class="hover:text-emerald-400 transition">Kampanye Donasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li>info@lembagawakaf.sedekah</li>
                        <li>+62 812-3456-7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} Lembaga Wakaf Syafa'aturrasul. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @livewireScripts
    <script>
        // Scroll to riwayat section when pagination changes
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-riwayat', () => {
                const element = document.getElementById('riwayat-donasi');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
