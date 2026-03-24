<div>
    {{-- Table Container with Horizontal Scroll --}}
    <div class="overflow-x-auto rounded-2xl border border-emerald-100 shadow-lg bg-white">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-xs font-bold uppercase">
                <tr>
                    <th class="p-3 text-left whitespace-nowrap">No</th>
                    <th class="p-3 text-left whitespace-nowrap">Donatur</th>
                    <th class="p-3 text-left whitespace-nowrap">Tipe</th>
                    <th class="p-3 text-right whitespace-nowrap">Jumlah</th>
                    <th class="p-3 text-right whitespace-nowrap hidden sm:table-cell">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-50">
                @forelse($history as $index => $h)
                <tr class="hover:bg-emerald-50 transition">
                    <td class="p-3 text-center font-bold text-slate-600">{{ $index + 1 }}</td>
                    <td class="p-3 font-semibold text-slate-700">
                        <span>{{ $h->donatur_name }}</span>
                    </td>
                    <td class="p-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            @if($h->type === 'wakaf_pembangunan') bg-emerald-100 text-emerald-700
                            @elseif($h->type === 'wakaf_produktif') bg-teal-100 text-teal-700
                            @else bg-cyan-100 text-cyan-700
                            @endif
                        ">
                            @if($h->type === 'wakaf_pembangunan') Wakaf Pembangunan
                            @elseif($h->type === 'wakaf_produktif') Wakaf Produktif
                            @else Donasi Pendidikan
                            @endif
                        </span>
                    </td>
                    <td class="p-3 text-right font-bold text-emerald-600">
                        Rp {{ number_format($h->amount, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-right text-slate-500 text-xs hidden sm:table-cell">
                        {{ $h->created_at->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center">
                        <div class="text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm">Belum ada donasi yang disetujui.</p>
                            <p class="text-xs mt-1">Jadilah yang pertama menyalurkan kebaikan!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $history->links() }}
    </div>
</div>
