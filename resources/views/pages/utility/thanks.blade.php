<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Terima Kasih - SMARTLAB SRS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-inter antialiased bg-gradient-to-br from-emerald-50 to-teal-50 text-slate-600">

    <main class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="relative flex flex-col md:flex-row">

                    <!-- Content -->
                    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">

                        <!-- Logo -->
                        <div class="flex justify-center mb-8">
                            <img src="{{ asset('images/logo_srs_new.png') }}" class="h-16 w-auto" alt="SRS Logo">
                        </div>

                        <!-- Success Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Thank You Message -->
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-slate-800 mb-4">Terima Kasih!</h1>
                            <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                                Terima kasih telah meluangkan waktu untuk mengisi kuesioner penilaian layanan kami.
                                Jawaban Anda sangat berharga dalam membantu kami meningkatkan kualitas layanan
                                <span class="text-emerald-600 font-semibold">Smartlab SRS</span>.
                            </p>

                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-8">
                                <p class="text-emerald-800 text-sm">
                                    <strong>Data Anda telah tersimpan dengan aman.</strong><br>
                                    Tim kami akan menganalisis feedback Anda untuk peningkatan layanan yang berkelanjutan.
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ url('/kuesioner') }}"
                                    class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Isi Kuesioner Lagi
                                </a>
                                <a href="https://smartlab.srs-ssms.com"
                                    class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Kunjungi Website
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Image -->
                    <div class="hidden md:block md:w-1/2 relative">
                        <img class="object-cover object-center w-full h-full"
                            src="{{ asset('images/YCH09564.jpg') }}"
                            alt="Laboratory image" />
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent to-emerald-900/20"></div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-8 py-4 text-center">
                    <p class="text-sm text-slate-500">
                        &copy; {{ date('Y') }} PT. Sulung Research Station - Laboratorium Pengujian & Kalibrasi
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto redirect after 30 seconds (optional)
        // setTimeout(() => {
        //     window.location.href = '/kuesioner';
        // }, 30000);
    </script>

</body>

</html>