<x-layouts.admin>
    {{-- Top Navigation --}}
    <nav class="bg-white border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/lws.png') }}" alt="LWS Logo" class="w-10 h-10 object-contain">
                    <div>
                        <h1 class="text-xl font-black text-emerald-700">Admin Dashboard</h1>
                        <p class="text-xs text-slate-500">Overview donasi dan statistik</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-slate-600 hidden sm:inline">
                        {{ auth()->user()->name }}
                    </span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-red-50 hover:text-red-600 transition flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Dashboard Content --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 space-y-6">
            
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
                    <p class="text-emerald-100 text-sm font-medium">Total Donasi</p>
                    <p class="text-3xl font-black mt-1">{{ number_format($totalDonations) }}</p>
                    <p class="text-emerald-100 text-xs mt-2">Semua waktu</p>
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
                    <p class="text-teal-100 text-sm font-medium">Total Terkumpul</p>
                    <p class="text-2xl font-black mt-1">{{ formatRupiah($totalAmount) }}</p>
                    <p class="text-teal-100 text-xs mt-2">Dari semua donasi</p>
                </div>

                {{-- Pending --}}
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">⏳</span>
                    </div>
                    <p class="text-amber-100 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-3xl font-black mt-1">{{ number_format($pendingCount) }}</p>
                    <p class="text-amber-100 text-xs mt-2">Perlu ditinjau</p>
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
                    <p class="text-cyan-100 text-sm font-medium">Disetujui</p>
                    <p class="text-3xl font-black mt-1">{{ number_format($approvedCount) }}</p>
                    <p class="text-cyan-100 text-xs mt-2">Donasi diverifikasi</p>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Monthly Trend Chart --}}
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Tren Donasi (6 Bulan)
                    </h3>
                    <div class="h-64 flex items-end justify-between space-x-2">
                        @foreach($monthlyData as $data)
                        @php
                            $maxAmount = collect($monthlyData)->max('amount');
                            $height = $maxAmount > 0 ? ($data['amount'] / $maxAmount) * 100 : 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t-lg transition-all duration-500" style="height: {{ max($height, 5) }}%"></div>
                            <p class="text-xs text-slate-500 font-medium mt-2">{{ $data['month'] }}</p>
                            <p class="text-[10px] text-slate-400">Rp {{ number_format($data['amount']/1000, 0) }}k</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Donation by Type --}}
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 shadow-sm">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                        Donasi per Kategori
                    </h3>
                    <div class="space-y-4">
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
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                @php $wakafPembangunanPercent = $totalAmount > 0 ? ($wakafPembangunanAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $wakafPembangunanPercent }}%"></div>
                            </div>
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
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                @php $wakafProduktifPercent = $totalAmount > 0 ? ($wakafProduktifAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-teal-500 h-2 rounded-full transition-all duration-500" style="width: {{ $wakafProduktifPercent }}%"></div>
                            </div>
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
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                @php $donasiPendidikanPercent = $totalAmount > 0 ? ($donasiPendidikanAmount / $totalAmount) * 100 : 0; @endphp
                                <div class="bg-cyan-500 h-2 rounded-full transition-all duration-500" style="width: {{ $donasiPendidikanPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Donations Table --}}
            <div class="bg-white rounded-2xl border border-emerald-50 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Donasi Terbaru
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-3 text-center">No</th>
                                <th class="px-6 py-3">Transaksi</th>
                                <th class="px-6 py-3">Donatur</th>
                                <th class="px-6 py-3">Tipe</th>
                                <th class="px-6 py-3">Bank</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentDonations as $index => $donation)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-slate-600">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-600">{{ $donation->trx_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-700">{{ $donation->donatur_name }}</p>
                                        @if($donation->is_anonymous)
                                        <span class="text-[10px] text-slate-400">(Anonim)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-md text-xs font-medium
                                        @if($donation->type === 'wakaf_pembangunan') bg-emerald-50 text-emerald-600
                                        @elseif($donation->type === 'wakaf_produktif') bg-teal-50 text-teal-600
                                        @else bg-cyan-50 text-cyan-600
                                        @endif
                                    ">
                                        @if($donation->type === 'wakaf_pembangunan') Wakaf Pembangunan
                                        @elseif($donation->type === 'wakaf_produktif') Wakaf Produktif
                                        @else Donasi Pendidikan
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    @if($donation->paymentMethod)
                                        {{ $donation->paymentMethod->bank_name }}
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-emerald-600">{{ formatRupiah($donation->amount) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold capitalize
                                        @if($donation->status === 'pending') bg-amber-100 text-amber-700
                                        @elseif($donation->status === 'approved') bg-emerald-100 text-emerald-700
                                        @else bg-red-100 text-red-700
                                        @endif
                                    ">
                                        {{ $donation->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p>Belum ada donasi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Kelola Kampanye
                </a>
                <a href="{{ route('admin.manage') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Kelola Donasi
                </a>
                <a href="{{ route('donation') }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-white text-emerald-700 rounded-xl font-bold border-2 border-emerald-200 hover:border-emerald-400 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Halaman Donasi
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-emerald-100 mt-12">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} Lembaga Wakaf Sedekah. All rights reserved.
                </p>
                <a href="{{ route('donation') }}" class="text-sm text-slate-500 hover:text-emerald-600 transition">
                    ← Kembali ke Halaman Donasi
                </a>
            </div>
        </div>
    </footer>
</x-layouts.admin>
