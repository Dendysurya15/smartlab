@php
use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ landing_setting('site_name', 'SMARTLAB SRS') }}</title>
    <meta name="description" content="{{ landing_setting('site_description', 'Laboratorium analisis sampel yang terpercaya dan profesional') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
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
</head>

<body class="font-inter antialiased bg-white text-slate-600">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-lg shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('images/logo_srs_new.png') }}" class="h-10 w-auto" alt="{{ landing_setting('site_name', 'SMARTLAB SRS') }} Logo">
                    <span class="ml-3 text-xl font-bold text-slate-800">{{ landing_setting('site_name', 'SMARTLAB SRS') }}</span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-full transition-all duration-200 transform hover:scale-105">
                        Log in
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-16 bg-gradient-to-br from-emerald-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-6xl font-bold text-slate-800 mb-6">
                        {{ landing_setting('hero_title', 'SMARTLAB SRS') }}
                    </h1>
                    <p class="text-xl text-slate-600 mb-8">
                        {{ landing_setting('hero_subtitle', 'Laboratorium Terpercaya untuk Analisis Sampel') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#tracking" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 text-center">
                            Track Sampel
                        </a>
                        <a href="#contact" class="border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-600 hover:text-white px-8 py-3 rounded-lg font-medium transition-all duration-200 text-center">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="relative">
                    @php
                    $heroImages = landing_hero_images();
                    $defaultImage = asset('images/YCH09527aa.jpg');
                    @endphp

                    @if(count($heroImages) > 0)
                    <!-- Hero Slideshow Container -->
                    <div id="hero-slideshow" class="w-full h-96 rounded-lg shadow-2xl overflow-hidden relative">
                        @foreach($heroImages as $index => $image)
                        <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                            data-bg="{{ $image['url'] }}">
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- Fallback single image -->
                    <img src="{{ $defaultImage }}"
                        alt="{{ landing_setting('hero_title', 'Laboratorium SMARTLAB SRS') }}"
                        class="w-full h-96 object-cover rounded-lg shadow-2xl">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    {{ landing_setting('features_title', 'Layanan Kami') }}
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    {{ landing_setting('features_description', 'Kami menyediakan berbagai layanan analisis laboratorium yang komprehensif') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg border border-slate-200 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">Analisis Berkualitas</h3>
                    <p class="text-slate-600">Laboratorium dengan standar internasional dan peralatan modern</p>
                </div>

                <div class="text-center p-6 rounded-lg border border-slate-200 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">Hasil Cepat</h3>
                    <p class="text-slate-600">Proses analisis yang efisien dengan hasil yang akurat</p>
                </div>

                <div class="text-center p-6 rounded-lg border border-slate-200 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">Tracking Real-time</h3>
                    <p class="text-slate-600">Pantau progress analisis sampel Anda secara real-time</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if(count(landing_gallery_images()) > 0)
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    {{ landing_setting('gallery_title', 'Galeri Laboratorium') }}
                </h2>
                <p class="text-xl text-slate-600">Fasilitas dan peralatan laboratorium modern</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach(landing_gallery_images() as $image)
                <div class="aspect-square overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <img src="{{ $image['url'] }}"
                        alt="Gallery Image"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Announcements Section -->
    @php $announcements = landing_announcements_list(); @endphp
    @if(count($announcements) > 0)
    <section class="py-16 bg-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Pengumuman</h2>
                <p class="text-xl text-slate-600">Informasi terbaru dari SMARTLAB SRS</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($announcements as $announcement)
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-800 mb-2">{{ $announcement['title'] }}</h3>
                            <p class="text-sm text-slate-500 mb-3">
                                {{ \Carbon\Carbon::parse($announcement['date'])->format('d F Y') }}
                            </p>
                            <div class="text-slate-600 prose prose-sm max-w-none">
                                {!! nl2br(e($announcement['content'])) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Tracking Section -->
    <section id="tracking" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Track Progress Sampel</h2>
                <p class="text-xl text-slate-600">Masukkan kode tracking untuk melihat status analisis sampel Anda</p>
            </div>

            @livewire('tracking-form')
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-slate-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Hubungi Kami</h2>
                <p class="text-xl text-slate-300">Kami siap membantu kebutuhan analisis laboratorium Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Telepon</h3>
                    <p class="text-slate-300">{{ landing_setting('contact_phone', '+62 21 1234 5678') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Email</h3>
                    <p class="text-slate-300">{{ landing_setting('contact_email', 'info@smartlab-srs.com') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Alamat</h3>
                    <p class="text-slate-300">{{ landing_setting('contact_address', 'Jl. Contoh No. 123, Jakarta Selatan, Indonesia') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-slate-400 mb-2">{{ landing_setting('footer_description', 'Laboratorium analisis sampel terpercaya dengan teknologi terdepan') }}</p>
                <p class="text-slate-500">{{ landing_setting('footer_copyright', '© 2024 SMARTLAB SRS. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    @filamentScripts
    @livewireScripts

    <script>
        // Hero Slideshow Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const slideshow = document.getElementById('hero-slideshow');
            if (!slideshow) return;

            const slides = slideshow.querySelectorAll('.hero-slide');
            if (slides.length <= 1) return;

            // Set background images for each slide
            slides.forEach(slide => {
                const bgUrl = slide.getAttribute('data-bg');
                if (bgUrl) {
                    slide.style.backgroundImage = `url('${bgUrl}')`;
                }
            });

            let currentSlide = 0;
            const totalSlides = slides.length;

            function showSlide(index) {
                // Hide all slides
                slides.forEach(slide => {
                    slide.style.opacity = '0';
                });

                // Show current slide
                slides[index].style.opacity = '1';
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            // Start slideshow - change image every 10 seconds
            setInterval(nextSlide, 10000);

            // Optional: Pause slideshow on hover
            slideshow.addEventListener('mouseenter', function() {
                // You can add pause functionality here if needed
            });

            slideshow.addEventListener('mouseleave', function() {
                // You can add resume functionality here if needed
            });
        });
    </script>
</body>

</html>