<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">
    <header class="bg-white p-6 rounded-2xl border border-emerald-100">
        <a href="{{ route('reports.dashboard') }}" wire:navigate class="text-emerald-700 text-sm font-semibold">← Kembali ke Dashboard Laporan</a>
        <h1 class="text-2xl font-black text-emerald-800 mt-4">{{ $title }}</h1>
        <nav class="flex flex-wrap gap-3 mt-5" aria-label="Pilih laporan">
            @foreach(['penyaluran' => 'Penyaluran', 'unit-usaha' => 'Unit Usaha', 'donasi-umum' => 'Wakaf'] as $value => $label)
                <a href="{{ route('reports.table', ['section' => $value]) }}" wire:navigate class="px-4 py-2 rounded-xl font-semibold {{ $section === $value ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </header>
    <section class="bg-white rounded-2xl border border-emerald-100 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100">
            <label for="public-report-search" class="block text-sm font-semibold text-slate-700 mb-2">{{ $section === 'donasi-umum' ? 'Cari kategori donasi' : 'Cari laporan' }}</label>
            <input id="public-report-search" type="search" wire:model.live.debounce.300ms="search" placeholder="{{ $section === 'donasi-umum' ? 'Contoh: pembangunan, produktif, pendidikan' : 'Ketik kata pencarian...' }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        @if($section === 'donasi-umum')<th class="px-4 py-3">Nama Donatur</th>@endif
                        @if($section !== 'donasi-umum')<th class="px-4 py-3">{{ $section === 'penyaluran' ? 'Deskripsi Penyaluran' : 'Nama Unit Usaha' }}</th>@endif
                        @if($section !== 'unit-usaha')<th class="px-4 py-3">Jenis Donasi</th>@endif
                        <th class="px-4 py-3 text-right">Jumlah (Rp)</th>
                        @if($section === 'unit-usaha')<th class="px-4 py-3">Catatan</th>@endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rows as $index => $row)
                        <tr wire:key="public-{{ $section }}-{{ $row->id }}">
                            <td class="px-4 py-4">{{ $rows->firstItem() + $index }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ match ($section) { 'penyaluran' => $row->disbursed_at?->format('d/m/Y') ?? 'Belum diisi', 'unit-usaha' => $row->tanggal->format('d/m/Y'), 'donasi-umum' => $row->created_at->format('d/m/Y') } }}</td>
                            @if($section === 'donasi-umum')<td class="px-4 py-4">{{ $row->donatur_name }}</td>@endif
                            @if($section !== 'donasi-umum')<td class="px-4 py-4 whitespace-pre-line min-w-48">{{ $section === 'penyaluran' ? $row->description : $row->nama }}</td>@endif
                            @if($section !== 'unit-usaha')<td class="px-4 py-4">{{ $categories[$row->type] ?? $row->type }}</td>@endif
                            <td class="px-4 py-4 text-right font-bold text-emerald-700 whitespace-nowrap">{{ formatRupiah($section === 'unit-usaha' ? $row->jumlah_uang : $row->amount) }}</td>
                            @if($section === 'unit-usaha')<td class="px-4 py-4 whitespace-pre-line">{{ $row->catatan ?? '-' }}</td>@endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-slate-500">Belum ada data atau tidak ada laporan yang sesuai dengan pencarian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $rows->links() }}</div>
    </section>
</div>
