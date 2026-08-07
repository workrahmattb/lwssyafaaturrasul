<?php

namespace Tests\Feature;

use Tests\TestCase;

class LivewireSingleLoadTest extends TestCase
{
    /**
     * Semua layout/halaman yang memuat app.js (yang mengimpor Livewire ESM
     * via Vite) harus memakai @livewireScriptConfig, BUKAN @livewireScripts.
     *
     * Regression: memuat Livewire dua kali (ESM di app.js + UMD dari
     * @livewireScripts) membuat dua Alpine saling berebut inisialisasi DOM,
     * sehingga interaksi pertama pada komponen baru setelah wire:navigate
     * gagal senyap (tombol Batal tidak menutup modal, Simpan tidak tersimpan).
     */
    public function test_all_layouts_use_livewire_script_config(): void
    {
        $views = [
            resource_path('views/components/layouts/admin.blade.php'),
            resource_path('views/components/layouts/app.blade.php'),
            resource_path('views/components/layouts/guest.blade.php'),
            resource_path('views/components/layouts/campaign.blade.php'),
            resource_path('views/admin/login.blade.php'),
            resource_path('views/donation.blade.php'),
        ];

        foreach ($views as $view) {
            $content = file_get_contents($view);

            $this->assertStringNotContainsString(
                '@livewireScripts',
                $content,
                "{$view} tidak boleh memakai @livewireScripts (menyebabkan double Livewire). Gunakan @livewireScriptConfig."
            );
            $this->assertStringContainsString(
                '@livewireScriptConfig',
                $content,
                "{$view} harus memakai @livewireScriptConfig agar Livewire hanya dimuat sekali via app.js."
            );
            // app.js wajib di-load supaya Livewire ESM tersedia di halaman ini.
            $this->assertStringContainsString(
                "@vite(['resources/css/app.css', 'resources/js/app.js'])",
                $content,
                "{$view} harus memuat resources/js/app.js via @vite (Livewire di-start dari sana)."
            );
        }
    }

    public function test_app_js_starts_livewire(): void
    {
        $appJs = file_get_contents(resource_path('js/app.js'));

        $this->assertStringContainsString(
            'Livewire.start()',
            $appJs,
            'resources/js/app.js harus memanggil Livewire.start() karena layout memakai @livewireScriptConfig.'
        );
    }
}
