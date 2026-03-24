<div class="max-w-6xl mx-auto p-4 sm:p-6">
    {{-- Flash Message --}}
    @if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="mb-4 p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="hover:bg-emerald-600 p-1 rounded">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="mb-4 p-4 bg-red-500 text-white rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm sm:text-base">{{ session('error') }}</span>
        </div>
        <button @click="show = false" class="hover:bg-red-600 p-1 rounded">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-xl border border-emerald-50 overflow-hidden mb-6">
        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('admin.donations') }}" class="p-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 transition flex-shrink-0" title="Kembali ke Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold">Kelola Kampanye</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Buat dan kelola kampanye donasi</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.campaign-donation') }}" class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 bg-white text-emerald-700 rounded-lg text-xs sm:text-sm font-bold hover:bg-emerald-50 transition shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Donasi</span>
                    </a>
                    <button
                        wire:click="openCreateForm"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 bg-white text-emerald-600 rounded-lg font-semibold text-xs sm:text-sm hover:bg-emerald-50 transition flex items-center justify-center space-x-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Buat Baru</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Campaigns List --}}
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($campaigns as $campaign)
                <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-emerald-300 transition shadow-sm hover:shadow-md">
                    {{-- Image --}}
                    @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-40 sm:h-48 object-cover">
                    @else
                    <div class="w-full h-40 sm:h-48 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                        <svg class="w-12 h-12 sm:w-14 sm:h-14 text-emerald-300/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    @endif

                    <div class="p-3 sm:p-4">
                        {{-- Title & Status --}}
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-slate-800 text-sm sm:text-base line-clamp-1 flex-1 mr-2">{{ $campaign->title }}</h3>
                            <span class="px-2 py-1 rounded-md text-xs font-bold capitalize flex-shrink-0
                                @if($campaign->status === 'active') bg-emerald-100 text-emerald-700
                                @elseif($campaign->status === 'completed') bg-blue-100 text-blue-700
                                @elseif($campaign->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700
                                @endif
                            ">
                                {{ $campaign->status }}
                            </span>
                        </div>

                        {{-- Short Description --}}
                        <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 mb-3">{{ $campaign->short_description ?? Str::limit($campaign->description, 80) }}</p>

                        {{-- Progress --}}
                        <div class="mb-3">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-slate-500">Terkumpul</span>
                                <span class="font-semibold text-emerald-600">{{ number_format($campaign->progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all" style="width: {{ $campaign->progress }}%"></div>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="flex justify-between items-center text-xs sm:text-sm mb-3">
                            <span class="text-slate-500 font-medium">Rp {{ number_format($campaign->raised_amount, 0, ',', '.') }}</span>
                            <span class="text-slate-400">/ Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                        </div>

                        {{-- Donors --}}
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>{{ $campaign->donor_count }} Donatur</span>
                            </div>
                            @if($campaign->end_date)
                            <span class="text-[10px] sm:text-xs">{{ $campaign->end_date->diffForHumans() }}</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <button
                                wire:click="editCampaign({{ $campaign->id }})"
                                wire:loading.attr="disabled"
                                wire:target="editCampaign"
                                class="flex-1 px-2 sm:px-3 py-2 sm:py-2 bg-emerald-50 text-emerald-600 rounded-lg text-xs sm:text-sm font-semibold hover:bg-emerald-100 transition disabled:opacity-50 disabled:cursor-not-allowed min-h-[36px]"
                            >
                                <span wire:loading.remove wire:target="editCampaign({{ $campaign->id }})">Edit</span>
                                <span wire:loading wire:target="editCampaign({{ $campaign->id }})">Loading...</span>
                            </button>
                            <button
                                wire:click="deleteCampaign({{ $campaign->id }})"
                                wire:confirm="Yakin ingin menghapus kampanye ini?"
                                class="px-2 sm:px-3 py-2 sm:py-2 bg-red-50 text-red-600 rounded-lg text-xs sm:text-sm font-semibold hover:bg-red-100 transition min-h-[36px]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 sm:py-16">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-sm sm:text-base text-slate-500 mt-2">Belum ada kampanye. Buat kampanye pertama Anda!</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>

    {{-- Create Form Modal --}}
    @if($showCreateForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4" wire:click="closeCreateForm">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[95vh] sm:max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">Buat Kampanye Baru</h3>
                    <button wire:click="closeCreateForm" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createCampaign" class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Kampanye</label>
                        <input type="text" wire:model="title" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base" placeholder="Contoh: Bangun Masjid Al-Ikhlas">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat</label>
                        <textarea wire:model="short_description" rows="2" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base" placeholder="Deskripsi singkat (max 500 karakter)"></textarea>
                        @error('short_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Lengkap</label>
                        <textarea wire:model="description" rows="5" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base" placeholder="Ceritakan detail kampanye Anda"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Target Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Target Dana (Rp)</label>
                        <input type="number" wire:model="target_amount" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base" placeholder="100000000">
                        @error('target_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Berakhir</label>
                            <input type="date" wire:model="end_date" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <select wire:model="status" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            <option value="draft">Draft</option>
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Kampanye</label>
                        <input type="file" wire:model="image" accept="image/*" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg">
                        </div>
                        @endif
                    </div>

                    {{-- Featured --}}
                    <label class="flex items-start space-x-3 p-3 sm:p-4 bg-slate-50 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-emerald-300 transition">
                        <input type="checkbox" wire:model="is_featured" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mt-0.5">
                        <span class="text-sm sm:text-base text-slate-700 font-semibold">Jadikan Kampanye Unggulan</span>
                    </label>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" wire:click="closeCreateForm" class="w-full sm:flex-1 px-4 py-3 bg-white text-slate-700 rounded-xl font-semibold border-2 border-slate-200 hover:border-emerald-300 transition text-sm sm:text-base">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition text-sm sm:text-base">
                            Buat Kampanye
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
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[95vh] sm:max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">Edit Kampanye</h3>
                    <button wire:click="closeEditForm" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateCampaign" class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Kampanye</label>
                        <input type="text" wire:model="title" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat</label>
                        <textarea wire:model="short_description" rows="2" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"></textarea>
                        @error('short_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Lengkap</label>
                        <textarea wire:model="description" rows="5" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Target Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Target Dana (Rp)</label>
                        <input type="number" wire:model="target_amount" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                        @error('target_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Berakhir</label>
                            <input type="date" wire:model="end_date" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <select wire:model="status" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                            <option value="draft">Draft</option>
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Kampanye</label>
                        <input type="file" wire:model="image" accept="image/*" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-slate-200 rounded-lg focus:border-emerald-500 focus:ring-0 text-sm sm:text-base">
                        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($selectedCampaign?->image && !$image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $selectedCampaign->image) }}" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg">
                        </div>
                        @endif
                        @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg">
                        </div>
                        @endif
                    </div>

                    {{-- Featured --}}
                    <label class="flex items-start space-x-3 p-3 sm:p-4 bg-slate-50 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-emerald-300 transition">
                        <input type="checkbox" wire:model="is_featured" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mt-0.5">
                        <span class="text-sm sm:text-base text-slate-700 font-semibold">Jadikan Kampanye Unggulan</span>
                    </label>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" wire:click="closeEditForm" class="w-full sm:flex-1 px-4 py-3 bg-white text-slate-700 rounded-xl font-semibold border-2 border-slate-200 hover:border-emerald-300 transition text-sm sm:text-base">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition text-sm sm:text-base">
                            Update Kampanye
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 sm:px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-bounce max-w-[calc(100vw-2rem)]">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium truncate">{{ session('success') }}</span>
    </div>
    @endif
</div>
