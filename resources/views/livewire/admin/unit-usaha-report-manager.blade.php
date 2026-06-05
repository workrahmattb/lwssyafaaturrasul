<div>
    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-xl border border-emerald-50 overflow-hidden">
        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('admin.donations') }}" wire:navigate class="p-2 rounded-lg bg-emerald-500/50 hover:bg-emerald-400 transition flex-shrink-0" title="Kembali ke Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold">Laporan Unit Usaha</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Catat dan kelola laporan unit usaha</p>
                    </div>
                </div>
                <button
                    wire:click="openCreateForm"
                    class="inline-flex items-center px-4 py-2.5 bg-white text-emerald-600 rounded-xl text-xs sm:text-sm font-bold hover:bg-emerald-50 transition shadow-lg"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Laporan
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 sm:p-6 bg-slate-50 border-b border-slate-100">
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Laporan</p>
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalCount) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Uang Masuk</p>
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-emerald-600">{{ formatRupiah($totalAmount) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Rata-rata</p>
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalCount > 0 ? formatRupiah($totalAmount / $totalCount) : 'Rp 0' }}</p>
            </div>
        </div>

        {{-- Content --}}
        <div>
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3 text-center">No</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3 text-right">Jumlah Uang</th>
                            <th class="px-6 py-3">Catatan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $index => $report)
                        <tr wire:key="report-{{ $report->id }}" class="hover:bg-emerald-50 transition">
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-600">{{ $reports->firstItem() + $index }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $report->nama }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $report->tanggal->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-emerald-600">{{ formatRupiah($report->jumlah_uang) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 line-clamp-2 max-w-xs">{{ $report->catatan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button
                                        wire:click="edit({{ $report->id }})"
                                        class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition"
                                        title="Edit"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="delete({{ $report->id }})"
                                        wire:confirm="Yakin ingin menghapus laporan ini?"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-semibold">Belum ada laporan unit usaha</p>
                                <p class="text-xs mt-1">Klik "Tambah Laporan" untuk memulai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($reports as $index => $report)
                <div wire:key="report-mobile-{{ $report->id }}" class="p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $report->nama }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $report->tanggal->format('d/m/Y') }}</p>
                        </div>
                        <span class="font-bold text-emerald-600">{{ formatRupiah($report->jumlah_uang) }}</span>
                    </div>
                    @if($report->catatan)
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $report->catatan }}</p>
                    @endif
                    <div class="flex gap-2 pt-1">
                        <button
                            wire:click="edit({{ $report->id }})"
                            class="flex-1 px-3 py-2.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-semibold hover:bg-amber-100 transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button
                            wire:click="delete({{ $report->id }})"
                            wire:confirm="Yakin ingin menghapus laporan ini?"
                            class="px-3 py-2.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-4 py-16 text-center text-slate-400">
                    <svg class="w-14 h-14 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm font-semibold">Belum ada laporan unit usaha</p>
                    <p class="text-xs mt-1">Klik "Tambah Laporan" untuk memulai</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        <div class="px-4 sm:px-6 py-4 bg-slate-50 border-t border-slate-100 overflow-x-auto">
            {{ $reports->links() }}
        </div>
    </div>

    {{-- Create Form Modal --}}
    @if($showCreateForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4" wire:click="closeCreateForm">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[95vh] sm:max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">Tambah Laporan Unit Usaha</h3>
                    <button wire:click="closeCreateForm" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="create" class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model="nama"
                            placeholder="Nama laporan / unit usaha"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        >
                        @error('nama') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            wire:model="tanggal"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        >
                        @error('tanggal') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jumlah Uang --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Uang (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">Rp</span>
                            <input
                                type="number"
                                wire:model="jumlah_uang"
                                min="0"
                                placeholder="0"
                                class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                            >
                        </div>
                        @error('jumlah_uang') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                        <textarea
                            wire:model="catatan"
                            rows="3"
                            placeholder="Keterangan tambahan (opsional)"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        ></textarea>
                        @error('catatan') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" wire:click="closeCreateForm" class="w-full sm:flex-1 px-4 py-3 bg-white text-slate-700 rounded-xl font-semibold border-2 border-slate-200 hover:border-emerald-300 transition text-sm">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition text-sm">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Edit Form Modal --}}
    @if($showEditForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4" wire:click="closeEditForm">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[95vh] sm:max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">Edit Laporan Unit Usaha</h3>
                    <button wire:click="closeEditForm" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="update" class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model="nama"
                            placeholder="Nama laporan / unit usaha"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        >
                        @error('nama') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            wire:model="tanggal"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        >
                        @error('tanggal') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jumlah Uang --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Uang (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">Rp</span>
                            <input
                                type="number"
                                wire:model="jumlah_uang"
                                min="0"
                                placeholder="0"
                                class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                            >
                        </div>
                        @error('jumlah_uang') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                        <textarea
                            wire:model="catatan"
                            rows="3"
                            placeholder="Keterangan tambahan (opsional)"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"
                        ></textarea>
                        @error('catatan') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" wire:click="closeEditForm" class="w-full sm:flex-1 px-4 py-3 bg-white text-slate-700 rounded-xl font-semibold border-2 border-slate-200 hover:border-emerald-300 transition text-sm">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition text-sm">
                            Update Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Success Toast --}}
    @if(session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 sm:px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-bounce max-w-[calc(100vw-2rem)] z-50">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium truncate">{{ session('success') }}</span>
    </div>
    @endif
</div>
