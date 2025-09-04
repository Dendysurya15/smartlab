<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SMARTLAB SRS</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key_v3') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400">

    <main class="bg-white min-h-screen">

        <div class="relative flex min-h-screen">

            <!-- Content -->
            <div class="w-full lg:w-3/5 xl:w-1/2 flex flex-col">

                <!-- Header -->
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-16 px-4 sm:px-6 lg:px-8 pt-20">
                        <!-- Logo -->
                        <a class="block" href="{{ route('dashboard') }}">
                            <div class="">
                                <img class="" src="{{ asset('images/LOGO-SRS.png') }}" width="170" height="170" alt="Alex Shatov" />
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Main Content Area - Flexible and Scrollable -->
                <div class="flex-1 overflow-y-auto">
                    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        <h1 class="text-2xl text-slate-800 dark:text-slate-100 font-bold mb-4">{{ __('Track Progress
                            Sampel')
                            }}</h1>
                        <p class="text-gray-600 mb-6">Masukkan kode unik sistem untuk melacak progress sampel anda</p>

                        @livewire('trackingprogres')

                        <div class="mt-5" id="result" style="display: none">
                            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <ul id="progress-list" class="space-y-4 text-left text-gray-500 dark:text-gray-400">

                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 mb-2 text-sm text-slate-600 font-medium italic" id="result_empty" style="display: none">Tidak menemukan sampel dengan kode <span id="kode_track_failed" class="text-red-600"></span>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Image -->
            <div class="hidden lg:block absolute top-0 bottom-0 right-0 lg:w-2/5 xl:w-1/2" aria-hidden="true">
                <img class="object-cover object-center w-full h-full" src="{{ asset('images/YCH09564.jpg') }}" width="760" height="1024" alt="Authentication image" />
            </div>
        </div>


    </main>

</body>

</html>