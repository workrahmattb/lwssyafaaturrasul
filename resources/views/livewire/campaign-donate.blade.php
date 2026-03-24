<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-50">

    {{-- Main Content --}}
    <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 py-8 lg:py-12">

        {{-- Single Column Layout - Form Only --}}
        <div>

            {{-- Progress Steps --}}
            <div class="flex items-center gap-0 mb-6">
                @foreach([['num' => 1, 'label' => 'Isi Data'], ['num' => 2, 'label' => 'Pembayaran'], ['num' => 3, 'label' => 'Selesai']] as $s)
                <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                            {{ $step >= $s['num']
                                ? 'text-white shadow-lg shadow-emerald-500/30'
                                : 'border-2 border-slate-200 text-slate-500 bg-white' }}"
                             style="{{ $step >= $s['num'] ? 'background: linear-gradient(135deg, #10b981, #0d9488);' : '' }}">
                            @if($step > $s['num'])
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $s['num'] }}
                            @endif
                        </div>
                        <span class="text-xs mt-1.5 font-medium {{ $step >= $s['num'] ? 'text-emerald-600' : 'text-slate-500' }} whitespace-nowrap">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    @if(!$loop->last)
                    <div class="flex-1 h-px mx-3 mb-5 transition-all duration-500
                        {{ $step > $s['num'] ? 'bg-emerald-500' : 'bg-slate-200' }}">
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Main Form Card --}}
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-xl">
                @include('livewire.campaign-donate-form')
            </div>
        </div>
    </div>
</div>
