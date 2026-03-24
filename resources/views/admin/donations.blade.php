<x-layouts.admin>
    {{-- Top Navigation --}}
    <nav class="bg-white border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-emerald-700">Admin Dashboard</h1>
                        <p class="text-xs text-slate-500">Kelola donasi yang masuk</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-slate-600 hidden sm:inline">
                        {{ auth()->user()->name }}
                    </span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-red-50 hover:text-red-600 transition flex items-center space-x-2"
                        >
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

    {{-- Main Content --}}
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6">
            {{-- Donation Manager Component --}}
            <livewire:admin.donation-manager />
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-emerald-100 mt-12">
        <div class="max-w-6xl mx-auto px-6 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} Lembaga Wakaf Sedekah. All rights reserved.
                </p>
                <a href="{{ route('donation') }}" wire:navigate class="text-sm text-slate-500 hover:text-emerald-600 transition">
                    ← Kembali ke Halaman Donasi
                </a>
            </div>
        </div>
    </footer>
</x-layouts.admin>
