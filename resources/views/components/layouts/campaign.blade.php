<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Kampanye - Lembaga Wakaf Sedekah' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/lws.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/lws.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gradient-to-br from-emerald-50 via-white to-teal-50">
    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('donation') }}" class="flex items-center space-x-2 sm:space-x-3">
                    <img src="{{ asset('images/lws.png') }}" alt="LWS Logo" class="w-10 h-10 sm:w-12 sm:h-12 object-contain">
                    <span class="text-base sm:text-xl font-black text-emerald-700 leading-tight">Lembaga Wakaf Sedekah</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/lws.png') }}" alt="LWS Logo" class="w-10 h-10 object-contain bg-white rounded-full p-1">
                        <span class="text-lg font-bold">Lembaga Wakaf Sedekah</span>
                    </div>
                    <p class="text-slate-400 text-sm">
                        Menyalurkan kebaikan dari para donatur untuk mereka yang membutuhkan.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Jenis Donasi</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="{{ route('donation') }}" class="hover:text-emerald-400 transition">Wakaf Pembangunan</a></li>
                        <li><a href="{{ route('donation') }}" class="hover:text-emerald-400 transition">Wakaf Produktif</a></li>
                        <li><a href="{{ route('donation') }}" class="hover:text-emerald-400 transition">Donasi Pendidikan</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="hover:text-emerald-400 transition">Kampanye Donasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li>info@lembagawakaf.sedekah</li>
                        <li>+62 812-3456-7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} Lembaga Wakaf Sedekah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
