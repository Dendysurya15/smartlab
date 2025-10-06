<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ (isset($settings) && isset($settings['general']['site_name'])) ? $settings['general']['site_name'] : 'SmartLab' }} - {{ (isset($settings) && isset($settings['general']['site_tagline'])) ? $settings['general']['site_tagline'] : 'Modern Laboratory Management' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
    @livewire('notifications')

    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>

    <style>
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(6, 100px);
            gap: 1rem;
        }

        .gallery-item-1 {
            grid-column: 1 / 8;
            grid-row: 1 / 5;
        }

        .gallery-item-2 {
            grid-column: 8 / 13;
            grid-row: 1 / 3;
        }

        .gallery-item-3 {
            grid-column: 8 / 13;
            grid-row: 3 / 5;
        }

        .gallery-item-4 {
            grid-column: 1 / 5;
            grid-row: 5 / 7;
        }

        .gallery-item-5 {
            grid-column: 5 / 9;
            grid-row: 5 / 7;
        }

        .gallery-item-6 {
            grid-column: 9 / 13;
            grid-row: 5 / 7;
        }

        .gallery-img {
            transition: transform 0.5s ease, filter 0.3s ease;
        }

        .gallery-img:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: linear-gradient(45deg, #10b981 0%, #3b82f6 100%);
            filter: blur(40px);
            opacity: 0.2;
            animation: blob 7s infinite;
        }

        @keyframes blob {

            0%,
            100% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }

            50% {
                border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
            }
        }

        .slide-in {
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .gallery-carousel {
            position: relative;
            overflow: hidden;
        }

        .gallery-carousel img {
            animation: carouselFade 15s infinite;
        }

        @keyframes carouselFade {

            0%,
            100% {
                opacity: 1;
            }

            33% {
                opacity: 0;
            }

            66% {
                opacity: 0;
            }
        }

        .gallery-carousel img:nth-child(2) {
            animation-delay: 5s;
        }

        .gallery-carousel img:nth-child(3) {
            animation-delay: 10s;
        }
    </style>
</head>

<body class="font-inter antialiased bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen overflow-x-hidden">

    <!-- Decorative Blobs -->
    <div class="fixed top-20 -left-20 w-72 h-72 blob"></div>
    <div class="fixed bottom-20 -right-20 w-96 h-96 blob" style="animation-delay: 2s;"></div>

    <!-- Announcements Section -->
    @if(isset($announcements) && $announcements->count() > 0)
    @foreach($announcements->where('position', 'top') as $announcement)
    <div class="announcement-banner bg-{{ $announcement->color ?? 'blue' }}-500 text-white py-3 px-6 text-center {{ $announcement->is_sticky ? 'sticky top-0 z-50' : '' }}"
        id="announcement-{{ $announcement->id }}">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2 flex-1">
                @if($announcement->icon)
                <span class="text-xl">{{ $announcement->icon }}</span>
                @endif
                <div>
                    <strong>{{ $announcement->title }}</strong>
                    <span class="ml-2">{{ strip_tags($announcement->message) }}</span>
                </div>
            </div>
            @if($announcement->is_dismissible)
            <button onclick="document.getElementById('announcement-{{ $announcement->id }}').remove()"
                class="ml-4 hover:bg-white/20 p-1 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            @endif
        </div>
    </div>
    @endforeach
    @endif

    <!-- Modern Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-40 px-6 py-4 {{ (isset($announcements) && $announcements->where('position', 'top')->count() > 0) ? 'mt-12' : '' }}">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl px-6 py-4 flex justify-between items-center border border-white/20">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="bg-gradient-to-br from-emerald-400 to-blue-500 p-2 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset((isset($settings) && isset($settings['general']['site_logo_path'])) ? $settings['general']['site_logo_path'] : 'images/logo_srs_new.png') }}"
                            class="h-8 w-auto brightness-0 invert"
                            alt="{{ (isset($settings) && isset($settings['general']['site_name'])) ? $settings['general']['site_name'] : 'SmartLab' }} Logo">
                    </div>
                    <span class="text-2xl font-outfit font-bold bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent">
                        {{ (isset($settings) && isset($settings['general']['site_name'])) ? $settings['general']['site_name'] : 'SmartLab' }}
                    </span>
                </a>

                <div class="flex items-center space-x-4">
                    <button id="theme-toggle" class="p-3 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-300">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <label for="my_modal_7" class="cursor-pointer bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        Masuk
                    </label>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Modal -->
    <input type="checkbox" id="my_modal_7" class="modal-toggle" />
    <div class="modal backdrop-blur-sm" role="dialog">
        <div class="modal-box bg-white dark:bg-slate-800 p-0 max-w-md w-full rounded-3xl shadow-2xl border-0 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-blue-600 p-8 text-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-3xl font-outfit font-bold mb-2">Selamat Datang! 👋</h1>
                        <p class="text-emerald-50 text-sm">Masuk ke sistem Smart Lab</p>
                    </div>
                    <label for="my_modal_7" class="cursor-pointer hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>
            </div>

            <div class="p-8">
                @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="email-unique" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input type="text" name="email" id="email-unique"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                    placeholder="nama@email.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div>
                            <label for="password-unique" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password-unique"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                    placeholder="••••••••" required autocomplete="current-password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
        </div>
        <label class="modal-backdrop cursor-pointer" for="my_modal_7"></label>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">

            <!-- Header Section -->
            <div class="text-center mb-12 slide-in">
                <div class="inline-flex items-center space-x-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span>{{ (isset($settings) && isset($settings['hero']['hero_badge_text'])) ? $settings['hero']['hero_badge_text'] : 'Sample Tracking System' }}</span>
                </div>

                <h1 class="text-5xl lg:text-6xl font-outfit font-bold text-slate-800 dark:text-slate-100 leading-tight mb-4">
                    {{ (isset($settings) && isset($settings['hero']['hero_title'])) ? $settings['hero']['hero_title'] : 'Lacak Progress' }}
                    <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent">
                        {{ (isset($settings) && isset($settings['hero']['hero_title_highlight'])) ? $settings['hero']['hero_title_highlight'] : 'Sampel' }}
                    </span>
                    {{ (isset($settings) && isset($settings['hero']['hero_title_end'])) ? $settings['hero']['hero_title_end'] : 'Anda' }}
                </h1>

                <p class="text-xl text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto">
                    {{ (isset($settings) && isset($settings['hero']['hero_subtitle'])) ? $settings['hero']['hero_subtitle'] : 'Pantau status sampel laboratorium secara real-time dengan sistem tracking modern dan efisien.' }}
                </p>
            </div>

            <!-- Gallery Mosaic Section -->
            @if(isset($galleryImages) && $galleryImages->count() > 0)
            <div class="mb-12 fade-in">
                <div class="gallery-grid">
                    <!-- Hero/Large Main Image with Carousel -->
                    @if(isset($carouselImages) && $carouselImages->count() > 0)
                    <div class="gallery-item-1 relative rounded-3xl overflow-hidden shadow-2xl group">
                        <div class="gallery-carousel absolute inset-0">
                            @foreach($carouselImages as $image)
                            <img src="{{ Storage::url($image->file_path) }}"
                                class="absolute inset-0 w-full h-full object-cover"
                                alt="{{ $image->alt_text ?? $image->title }}" />
                            @endforeach
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @if($carouselImages->first())
                        <div class="absolute bottom-6 left-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h3 class="text-2xl font-bold mb-1">{{ $carouselImages->first()->title }}</h3>
                            <p class="text-sm text-white/80">{{ $carouselImages->first()->caption }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Top Right -->
                    @foreach($galleryImages->get('top-right', collect()) as $image)
                    <div class="gallery-item-2 relative rounded-2xl overflow-hidden shadow-xl group">
                        <img src="{{ Storage::url($image->file_path) }}"
                            class="gallery-img w-full h-full object-cover"
                            alt="{{ $image->alt_text ?? $image->title }}" />
                        @if($image->overlay_gradient)
                        <div class="absolute inset-0 bg-gradient-to-br from-{{ str_replace('-', '-500/20 to-', $image->overlay_gradient) }}-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        @endif
                    </div>
                    @endforeach

                    <!-- Middle Right -->
                    @foreach($galleryImages->get('middle-right', collect()) as $image)
                    <div class="gallery-item-3 relative rounded-2xl overflow-hidden shadow-xl group">
                        <img src="{{ Storage::url($image->file_path) }}"
                            class="gallery-img w-full h-full object-cover"
                            alt="{{ $image->alt_text ?? $image->title }}" />
                        @if($image->overlay_gradient)
                        <div class="absolute inset-0 bg-gradient-to-br from-{{ str_replace('-', '-500/20 to-', $image->overlay_gradient) }}-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        @endif
                    </div>
                    @endforeach

                    <!-- Bottom Row -->
                    @foreach($galleryImages->get('bottom-left', collect()) as $image)
                    <div class="gallery-item-4 relative rounded-2xl overflow-hidden shadow-xl group">
                        <img src="{{ Storage::url($image->file_path) }}"
                            class="gallery-img w-full h-full object-cover"
                            alt="{{ $image->alt_text ?? $image->title }}" />
                        @if($image->badge_text)
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-{{ $image->badge_color ?? 'emerald' }}-600">
                            {{ $image->badge_text }}
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @foreach($galleryImages->get('bottom-center', collect()) as $image)
                    <div class="gallery-item-5 relative rounded-2xl overflow-hidden shadow-xl group">
                        <img src="{{ Storage::url($image->file_path) }}"
                            class="gallery-img w-full h-full object-cover"
                            alt="{{ $image->alt_text ?? $image->title }}" />
                        @if($image->badge_text)
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-{{ $image->badge_color ?? 'blue' }}-600">
                            {{ $image->badge_text }}
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @foreach($galleryImages->get('bottom-right', collect()) as $image)
                    <div class="gallery-item-6 relative rounded-2xl overflow-hidden shadow-xl group">
                        <img src="{{ Storage::url($image->file_path) }}"
                            class="gallery-img w-full h-full object-cover"
                            alt="{{ $image->alt_text ?? $image->title }}" />
                        @if($image->badge_text)
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-{{ $image->badge_color ?? 'purple' }}-600">
                            {{ $image->badge_text }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tracking Form & Features Section -->
            <div class="grid lg:grid-cols-2 gap-8 items-start">

                <!-- Tracking Form -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 border border-slate-200 dark:border-slate-700 slide-in">
                    @if(isset($slot))
                    {{ $slot }}
                    @else
                    @livewire('trackingprogres')
                    @endif
                </div>

                <!-- Features Grid -->
                <div class="space-y-6 slide-in" style="animation-delay: 0.2s;">
                    <!-- Feature Cards -->
                    @if(isset($featureCards) && $featureCards->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($featureCards as $card)
                        <div class="p-6 rounded-2xl shadow-xl text-white transform hover:scale-105 transition-all duration-300"
                            style="background: linear-gradient(to bottom right, {{ $card->color_from }}, {{ $card->color_to }}); color: {{ $card->text_color }};">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                    @if($card->icon_svg)
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card->icon_svg }}" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-3xl font-bold mb-1">{{ $card->title }}</div>
                                    <div style="color: {{ $card->text_color }}; opacity: 0.9;">{{ $card->subtitle }}</div>
                                </div>
                            </div>
                            @if($card->description)
                            <p class="mt-3 text-sm opacity-90">{{ $card->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Info Box -->
                    <div class="bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 p-6 rounded-2xl border border-slate-300 dark:border-slate-600">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Siap Melacak Progress Sampel?</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                    Masukkan kode tracking di atas untuk melihat status terkini sampel laboratorium Anda. Dapatkan update real-time dan notifikasi otomatis.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            @if(isset($statistics) && $statistics->count() > 0)
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($statistics as $stat)
                <div class="text-center p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="text-4xl font-bold bg-clip-text text-transparent mb-2"
                        style="background-image: linear-gradient(to right, {{ $stat->gradient_from }}, {{ $stat->gradient_to }});">
                        {{ $stat->value }}{{ $stat->suffix }}
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $stat->label }}</div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 text-center text-slate-600 dark:text-slate-400 text-sm">
        <p>{{ (isset($settings) && isset($settings['footer']['footer_text'])) ? $settings['footer']['footer_text'] : 'Copyright © Digital Architect 2023 - Made with ❤️' }}</p>
    </footer>

    @filamentScripts
    @livewireScripts

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const html = document.querySelector('html');
                const isDark = html.classList.contains('dark');

                if (isDark) {
                    html.classList.remove('dark');
                    html.style.colorScheme = 'light';
                    localStorage.setItem('dark-mode', 'false');
                } else {
                    html.classList.add('dark');
                    html.style.colorScheme = 'dark';
                    localStorage.setItem('dark-mode', 'true');
                }
            });
        }
    </script>
</body>

</html>