<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">
    <header class="bg-white rounded-2xl p-6 border border-emerald-100">
        <a href="{{ route('donation') }}" wire:navigate class="text-sm font-semibold text-emerald-700">← Kembali ke Beranda</a>
        <h1 class="text-2xl sm:text-3xl font-black text-emerald-800 mt-4">Dashboard Laporan LWS</h1>
        <p class="text-slate-500 mt-2">Transparansi penerimaan dan penyaluran dana LWS</p>
        <nav aria-label="Tabel laporan" class="flex flex-wrap gap-3 mt-5">
            @foreach(['penyaluran' => 'Penyaluran', 'unit-usaha' => 'Unit Usaha', 'donasi-umum' => 'Wakaf'] as $section => $label)
                <a href="{{ route('reports.table', ['section' => $section]) }}" wire:navigate class="px-4 py-3 bg-emerald-600 text-white font-semibold rounded-xl">Lihat Tabel {{ $label }}</a>
            @endforeach
        </nav>
    </header>
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Donations --}}
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">👥</span>
                    </div>
                    <p class="text-emerald-100 text-sm font-medium">Total Terkumpul Wakaf + Donasi</p>
                    <p class="text-2xl font-black mt-1 break-words">{{ formatRupiah($totalCollectedDonations) }}</p>
                    <p class="text-emerald-100 text-xs mt-2">Donasi yang sudah disetujui</p>
                </div>

                {{-- Total Campaign Donations --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">📋</span>
                    </div>
                    <p class="text-blue-100 text-sm font-medium">Total Unit Usaha</p>
                    <p class="text-2xl font-black mt-1 break-words">{{ formatRupiah($unitUsahaTotalAmount) }}</p>
                    <p class="text-blue-100 text-xs mt-2">Total pemasukan unit usaha</p>
                </div>

                {{-- Total Amount --}}
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">💰</span>
                    </div>
                    <p class="text-teal-100 text-sm font-medium">Penyaluran Total</p>
                    <p class="text-2xl font-black mt-1">{{ formatRupiah($totalDisbursedAmount) }}</p>
                    <p class="text-teal-100 text-xs mt-2">Seluruh dana yang disalurkan</p>
                </div>

                {{-- Approved --}}
                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">✅</span>
                    </div>
                    <p class="text-cyan-100 text-sm font-medium">SALDO LWS</p>
                    <p class="text-2xl font-black mt-1 break-words">{{ formatRupiah($lwsBalance) }}</p>
                    <p class="text-cyan-100 text-xs mt-2">Donasi + unit usaha − penyaluran</p>
                </div>
            </div>

            {{-- Charts Section - Row 1: Monthly & Yearly --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Monthly Trend Chart (Chart.js) --}}
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-emerald-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Tren Donasi Perbulan
                        </h3>
                        <span class="text-xs text-slate-400 font-medium">12 bulan terakhir</span>
                    </div>
                    <div class="h-72 relative">
                        <canvas
                            x-init='initMonthlyChart($el, @json($monthlyLabels), @json($monthlyWakafPembangunanAmounts), @json($monthlyWakafProduktifAmounts), @json($monthlyDonasiPendidikanAmounts))'
                            class="w-full h-full">
                        </canvas>
                    </div>
                </div>

                {{-- Yearly Trend Chart (Chart.js) --}}
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-emerald-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Tren Donasi Pertahun
                        </h3>
                        <span class="text-xs text-slate-400 font-medium">Sejak 2024</span>
                    </div>
                    <div class="h-72 relative">
                        <canvas
                            x-init='initYearlyChart($el, @json($yearlyLabels), @json($yearlyWakafPembangunanAmounts), @json($yearlyWakafProduktifAmounts), @json($yearlyDonasiPendidikanAmounts))'
                            class="w-full h-full">
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                <h3 class="text-lg font-bold text-emerald-800 mb-1">Total Donasi Terkumpul per Tahun</h3>
                <p class="text-sm text-slate-500 mb-6">Wakaf dan donasi yang disetujui · Sejak 2024</p>
                <div class="h-72 relative" wire:ignore>
                    <canvas
                        x-init='initTotalYearlyChart($el, @json($yearlyLabels), @json($yearlyTotalAmounts))'
                        role="img"
                        aria-label="Grafik total donasi terkumpul per tahun"
                        class="w-full h-full">
                    </canvas>
                </div>
            </div>

            {{-- Charts Section - Row 2: Category Pie + Stats --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Category Doughnut Chart --}}
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                        Komposisi per Kategori
                    </h3>
                    <div class="h-64 relative">
                        <canvas
                            x-init='initCategoryChart($el, @json($categoryLabels), @json($categoryAmounts), @json($categoryColors))'
                            class="w-full h-full">
                        </canvas>
                    </div>
                </div>

                {{-- Category Breakdown (Progress Bars) - spans 2 cols --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                        Rincian per Kategori
                    </h3>
                    <div class="space-y-6">
                        {{-- Wakaf Pembangunan --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-slate-700">Wakaf Pembangunan</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-emerald-600">{{ formatRupiah($wakafPembangunanAmount) }}</p>
                                    <p class="text-xs text-slate-400">{{ $wakafPembangunanCount }} donasi</p>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                @php $wakafPembangunanPercent = $totalAmount > 0 ? ($wakafPembangunanAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-700" style="width: {{ $wakafPembangunanPercent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ number_format($wakafPembangunanPercent, 1) }}% dari total</p>
                        </div>

                        {{-- Wakaf Produktif --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-slate-700">Wakaf Produktif</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-teal-600">{{ formatRupiah($wakafProduktifAmount) }}</p>
                                    <p class="text-xs text-slate-400">{{ $wakafProduktifCount }} donasi</p>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                @php $wakafProduktifPercent = $totalAmount > 0 ? ($wakafProduktifAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-teal-500 h-2.5 rounded-full transition-all duration-700" style="width: {{ $wakafProduktifPercent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ number_format($wakafProduktifPercent, 1) }}% dari total</p>
                        </div>

                        {{-- Donasi Pendidikan --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-cyan-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-slate-700">Donasi Pendidikan</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-cyan-600">{{ formatRupiah($donasiPendidikanAmount) }}</p>
                                    <p class="text-xs text-slate-400">{{ $donasiPendidikanCount }} donasi</p>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                @php $donasiPendidikanPercent = $totalAmount > 0 ? ($donasiPendidikanAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-cyan-500 h-2.5 rounded-full transition-all duration-700" style="width: {{ $donasiPendidikanPercent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ number_format($donasiPendidikanPercent, 1) }}% dari total</p>
                        </div>

                        {{-- Total Keseluruhan --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-800">Total Keseluruhan</span>
                                <span class="text-sm font-bold text-emerald-600">{{ formatRupiah($totalAmount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Unit Usaha Report Section --}}
            <div class="bg-white rounded-2xl border border-emerald-50 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-violet-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Laporan Unit Usaha
                    </h3>
                </div>
                <div class="p-6">
                    {{-- Stats Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-4 border border-purple-100">
                            <p class="text-xs text-purple-600 font-medium uppercase tracking-wider">Total Laporan</p>
                            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($unitUsahaTotalReports) }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-4 border border-purple-100">
                            <p class="text-xs text-purple-600 font-medium uppercase tracking-wider">Total Uang</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ formatRupiah($unitUsahaTotalAmount) }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-4 border border-purple-100">
                            <p class="text-xs text-purple-600 font-medium uppercase tracking-wider">Rata-rata / Laporan</p>
                            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $unitUsahaTotalReports > 0 ? formatRupiah($unitUsahaTotalAmount / $unitUsahaTotalReports) : 'Rp 0' }}</p>
                        </div>
                    </div>

                    {{-- Unit Usaha Charts --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        {{-- Monthly Trend Chart --}}
                        <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold text-purple-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    Tren Pemasukan SR Bakery Perbulan
                                </h4>
                                <span class="text-xs text-slate-400 font-medium">Hasil Keuntungan SR Bakery</span>
                            </div>
                            <div class="h-64 relative">
                                <canvas
                                    x-init='initUnitUsahaMonthlyChart($el, @json($unitUsahaMonthlyLabels), @json($unitUsahaMonthlyAmounts))'
                                    class="w-full h-full">
                                </canvas>
                            </div>
                        </div>

                        {{-- Per Unit Doughnut Chart --}}
                        <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold text-purple-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    </svg>
                                    Komposisi per Unit Usaha
                                </h4>
                                <span class="text-xs text-slate-400 font-medium">Berdasarkan total</span>
                            </div>
                            <div class="h-64 relative">
                                <canvas
                                    x-init='initUnitUsahaUnitChart($el, @json($unitUsahaUnitLabels), @json($unitUsahaUnitAmounts), @json($unitUsahaUnitColors))'
                                    class="w-full h-full">
                                </canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm mb-6">
                        <h4 class="text-sm font-bold text-purple-800">Pemasukan Unit Usaha per Tahun</h4>
                        <p class="text-xs text-slate-400 mt-1 mb-4">Seluruh unit usaha · Berdasarkan tanggal laporan · Sejak 2024</p>
                        <div class="h-72 relative" wire:ignore>
                            <canvas
                                x-init='initTotalYearlyChart($el, @json($yearlyLabels), @json($unitUsahaYearlyAmounts), "Pemasukan Unit Usaha", "#8b5cf6")'
                                role="img"
                                aria-label="Grafik pemasukan seluruh unit usaha per tahun"
                                class="w-full h-full">
                            </canvas>
                        </div>
                    </div>

                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-800">Grafik Penyaluran Donasi</h3>
                        <p class="text-sm text-slate-500 mt-1">Total penyaluran per kategori · Semua periode</p>
                    </div>
                    <a href="{{ route('reports.table', ['section' => 'penyaluran']) }}" wire:navigate class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Lihat Tabel Penyaluran →</a>
                </div>
                <p class="text-sm text-slate-500">Total Dana Disalurkan</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1 mb-6">{{ formatRupiah($totalDisbursedAmount) }}</p>
                @if($totalDisbursedAmount == 0)
                    <p class="py-8 text-center text-slate-400">Belum ada data penyaluran.</p>
                @endif
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <h4 class="text-lg font-bold text-emerald-800">Penyaluran per Tahun</h4>
                    <p class="text-sm text-slate-500 mt-1 mb-6">Berdasarkan tanggal penyaluran · Sejak 2024</p>
                    <div class="h-72 relative" wire:ignore>
                        <canvas
                            x-init='initYearlyChart($el, @json($yearlyLabels), @json($yearlyDisbursementsByCategory["wakaf_pembangunan"]), @json($yearlyDisbursementsByCategory["wakaf_produktif"]), @json($yearlyDisbursementsByCategory["donasi_pendidikan"]))'
                            role="img"
                            aria-label="Grafik penyaluran per tahun menurut kategori"
                            class="w-full h-full">
                        </canvas>
                    </div>
                    @if($undatedDisbursementAmount > 0)
                        <p class="text-sm text-amber-700 mt-4">Penyaluran sebesar {{ formatRupiah($undatedDisbursementAmount) }} belum memiliki tanggal dan belum masuk grafik tahunan. Data tersebut belum termasuk grafik tahunan.</p>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                    @foreach($disbursementLabels as $index => $label)
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">{{ $label }}</p>
                            <p class="text-lg font-bold text-slate-800 mt-1">{{ formatRupiah($disbursementAmounts[$index]) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

</div>
