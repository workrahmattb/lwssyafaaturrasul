<div class="max-w-5xl mx-auto p-4 sm:p-6">
    <div class="bg-white rounded-2xl shadow-xl border border-emerald-50 overflow-hidden">
        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('admin.donations') }}" class="p-2 rounded-lg bg-emerald-500/50 hover:bg-emerald-400 transition flex-shrink-0" title="Kembali ke Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold">Kelola Donasi</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Approve/reject donasi umum</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.export.donations', $filter ? ['status' => $filter] : []) }}" class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition whitespace-nowrap bg-white/90 text-emerald-700 hover:bg-white cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span class="ml-1 hidden sm:inline">Export CSV</span>
                    </a>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="mt-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="Cari berdasarkan nama donatur..." 
                        class="w-full pl-10 pr-4 py-2 bg-white/90 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-white focus:border-white text-sm text-slate-700 placeholder-emerald-300"
                    >
                </div>
            </div>

            {{-- Filter Tabs - Scrollable on mobile --}}
            <div class="flex overflow-x-auto gap-2 pb-2 sm:pb-0 -mx-4 sm:mx-0 px-4 sm:px-0 scrollbar-hide mt-3">
                @foreach(['pending', 'approved', 'rejected', ''] as $status)
                <button
                    wire:click="setFilter('{{$status}}')"
                    class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition whitespace-nowrap {{ $filter == $status ? 'bg-white text-emerald-700' : 'bg-emerald-500/50 text-white hover:bg-emerald-400' }}"
                >
                    {{ $status === '' ? 'Semua' : ucfirst($status) }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Desktop Table View (hidden on mobile) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3 text-center">No</th>
                        <th class="px-6 py-3">Transaksi</th>
                        <th class="px-6 py-3">Donatur</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Bank</th>
                        <th class="px-6 py-3 text-right">Jumlah</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-center">Bukti</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($donations as $index => $donation)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-600">{{ $donations->firstItem() + $index }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold text-slate-600">{{$donation->trx_id}}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-700">{{$donation->donatur_name}}</p>
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
                                {{$donation->paymentMethod->bank_name}}
                            @else
                                <span class="text-slate-400 text-xs">Bank tidak tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-emerald-600">{{ formatRupiah($donation->amount) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500">{{ $donation->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($donation->proof_of_transfer)
                            <button
                                wire:click="$set('selectedProof', '{{ asset('storage/' . $donation->proof_of_transfer) }}')"
                                class="text-emerald-600 hover:text-emerald-800 font-semibold text-xs flex items-center justify-center space-x-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Lihat</span>
                            </button>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($donation->status === 'pending')
                            <div class="flex justify-center space-x-2">
                                <button
                                    wire:click="approve({{$donation->id}})"
                                    wire:confirm="Approve this donation?"
                                    class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition"
                                >
                                    ✓ Approve
                                </button>
                                <button
                                    wire:click="reject({{$donation->id}})"
                                    wire:confirm="Reject this donation?"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition"
                                >
                                    ✗ Reject
                                </button>
                            </div>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold capitalize
                                @if($donation->status === 'approved') bg-emerald-100 text-emerald-700
                                @else bg-red-100 text-red-700
                                @endif
                            ">
                                {{$donation->status}}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                wire:click="delete({{$donation->id}})"
                                wire:confirm="Yakin ingin menghapus donasi ini?"
                                class="text-red-500 hover:text-red-700 transition"
                                title="Hapus"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Tidak ada donasi untuk ditampilkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View (visible only on mobile) --}}
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($donations as $index => $donation)
            <div class="p-4 space-y-3">
                {{-- Header: No & Status --}}
                <div class="flex justify-between items-start">
                    <span class="text-xs font-bold text-slate-500">#{{ $donations->firstItem() + $index }}</span>
                    @if($donation->status === 'pending')
                    <div class="flex gap-1">
                        <button
                            wire:click="approve({{$donation->id}})"
                            wire:confirm="Approve donasi ini?"
                            class="px-2.5 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button
                            wire:click="reject({{$donation->id}})"
                            wire:confirm="Reject donasi ini?"
                            class="px-2.5 py-1.5 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    </div>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize
                        @if($donation->status === 'approved') bg-emerald-100 text-emerald-700
                        @else bg-red-100 text-red-700
                        @endif
                    ">
                        {{$donation->status}}
                    </span>
                    @endif
                </div>

                {{-- Transaction ID --}}
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span class="font-mono text-xs font-bold text-slate-600">{{$donation->trx_id}}</span>
                </div>

                {{-- Donatur Info --}}
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div>
                        <span class="font-medium text-sm text-slate-700">{{$donation->donatur_name}}</span>
                        @if($donation->is_anonymous)
                        <span class="text-[10px] text-slate-400 block">(Anonim)</span>
                        @endif
                    </div>
                </div>

                {{-- Tipe Donasi --}}
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="px-2 py-1 rounded-md text-xs font-medium inline-block
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
                </div>

                {{-- Bank Info --}}
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    <span class="text-sm text-slate-500">{{$donation->paymentMethod ? $donation->paymentMethod->bank_name : '-'}}</span>
                </div>

                {{-- Amount --}}
                <div class="flex items-center justify-between bg-emerald-50 rounded-lg px-3 py-2">
                    <span class="text-xs text-slate-500">Jumlah Donasi</span>
                    <span class="font-bold text-emerald-600 text-sm">{{ formatRupiah($donation->amount) }}</span>
                </div>

                {{-- Created At --}}
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $donation->created_at->format('d M Y, H:i') }}</span>
                </div>

                {{-- Proof & Delete Actions --}}
                <div class="flex gap-2 pt-2">
                    @if($donation->proof_of_transfer)
                    <button
                        wire:click="$set('selectedProof', '{{ asset('storage/' . $donation->proof_of_transfer) }}')"
                        class="flex-1 px-3 py-2.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold hover:bg-emerald-100 transition flex items-center justify-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Lihat Bukti
                    </button>
                    @endif
                    <button
                        wire:click="delete({{$donation->id}})"
                        wire:confirm="Yakin ingin menghapus donasi ini?"
                        class="px-3 py-2.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition"
                        title="Hapus"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-slate-400">
                <svg class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-slate-500">Tidak ada donasi untuk ditampilkan.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-4 sm:px-6 py-4 bg-slate-50 border-t border-slate-100 overflow-x-auto">
            {{$donations->links()}}
        </div>
    </div>

    {{-- Modal Bukti Transfer --}}
    @if($selectedProof)
    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-3 sm:p-4" wire:click="$set('selectedProof', null)">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[95vh] sm:max-h-[90vh] overflow-auto" wire:click.stop>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-slate-700">Bukti Transfer</h3>
                    <button wire:click="$set('selectedProof', null)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <img src="{{ $selectedProof }}" alt="Bukti Transfer" class="w-full rounded-xl">
            </div>
        </div>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 sm:px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-bounce z-50 max-w-[calc(100vw-2rem)]">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium truncate">{{ session('success') }}</span>
    </div>
    @endif
</div>
