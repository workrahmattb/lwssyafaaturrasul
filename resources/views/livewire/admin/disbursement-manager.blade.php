<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="bg-white rounded-2xl shadow-xl border border-emerald-50 overflow-hidden">
        <div class="px-4 sm:px-6 py-5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.donations') }}" wire:navigate class="text-sm text-emerald-50 hover:underline" title="Kembali ke Dashboard">← Kembali ke Dashboard</a>
                <h1 class="text-xl font-bold mt-2">Kelola Penyaluran</h1>
                <p class="text-sm text-emerald-100 mt-1">Catat dan kelola penyaluran dana donasi</p>
            </div>
            <a href="{{ route('admin.export.penyaluran') }}" class="px-4 py-3 bg-emerald-700 text-white rounded-xl font-bold hover:bg-emerald-800">Export Excel</a>
            <button wire:click="openCreateForm" class="px-4 py-3 bg-white text-emerald-700 rounded-xl font-bold">+ Tambah Penyaluran</button>
        </div>
        <div class="p-4 sm:p-6 bg-slate-50 border-b border-slate-100">
            <p class="text-sm text-slate-500">Total Dana Disalurkan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ formatRupiah($totalAmount) }}</p>
        </div>
        @if(session()->has('success'))
            <p role="status" class="m-4 p-4 bg-emerald-50 text-emerald-800 rounded-xl">{{ session('success') }}</p>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal Penyaluran</th>
                        <th class="px-6 py-4">Deskripsi Penyaluran</th>
                        <th class="px-6 py-4">Jenis Donasi</th>
                        <th class="px-6 py-4 text-right">Jumlah yang Disalurkan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($disbursements as $index => $disbursement)
                        <tr wire:key="disbursement-{{ $disbursement->id }}" class="hover:bg-emerald-50">
                            <td class="px-6 py-4 text-slate-600">{{ $disbursements->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $disbursement->disbursed_at?->format('d/m/Y') ?? 'Belum diisi' }}</td>
                            <td class="px-6 py-4 min-w-64 whitespace-pre-line break-words text-slate-700">{{ $disbursement->description }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $categories[$disbursement->type] }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600 whitespace-nowrap">{{ formatRupiah($disbursement->amount) }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button wire:click="edit({{ $disbursement->id }})" class="px-3 py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold">Edit</button>
                                <button wire:click="delete({{ $disbursement->id }})" wire:confirm="Hapus data penyaluran ini?" wire:loading.attr="disabled" class="px-3 py-2 rounded-lg bg-red-50 text-red-700 font-semibold">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data penyaluran. Klik Tambah Penyaluran untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">{{ $disbursements->links() }}</div>
    </div>

    @if($showForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click.self="closeForm" wire:keydown.escape="closeForm">
            <section role="dialog" aria-modal="true" aria-labelledby="disbursement-form-title" class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h2 id="disbursement-form-title" class="text-xl font-bold text-slate-800">{{ $selectedId ? 'Edit Penyaluran' : 'Tambah Penyaluran' }}</h2>
                    <button type="button" wire:click="closeForm" aria-label="Tutup formulir" class="p-2 text-slate-500">✕</button>
                </div>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label for="disbursement-date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Penyaluran</label>
                        <input id="disbursement-date" type="date" wire:model="disbursed_at" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500">
                        @error('disbursed_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="disbursement-description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Penyaluran</label>
                        <textarea id="disbursement-description" wire:model="description" rows="4" maxlength="5000" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="disbursement-amount" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah yang Disalurkan (Rp)</label>
                        <input id="disbursement-amount" type="text" inputmode="decimal" wire:model="amount"
                            x-on:input.capture="
                                const parts = $event.target.value.replace(/[^0-9,]/g, '').split(',');
                                const whole = parts[0].replace(/^0+(?=\d)/, '');
                                $event.target.value = whole.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + (parts.length > 1 ? ',' + parts[1].slice(0, 2) : '');
                            "
                            required placeholder="Contoh: 1.500.000" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500">
                        @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="disbursement-type" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Donasi</label>
                        <select id="disbursement-type" wire:model="type" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500">
                            <option value="">Pilih jenis donasi</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex flex-wrap justify-end gap-3 pt-4">
                        <button type="button" wire:click="closeForm" class="px-4 py-3 border border-slate-200 rounded-xl font-semibold text-slate-600">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-3 bg-emerald-600 text-white rounded-xl font-semibold disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">Simpan Penyaluran</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif
</div>
