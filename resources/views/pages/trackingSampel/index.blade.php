<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SMARTLAB SRS</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400">

    <main class="bg-white">

        <div class="relative flex">


            <!-- Content -->
            <div class="w-full md:w-1/2">

                <div class="min-h-screen h-full flex flex-col after:flex-1">

                    <!-- Header -->
                    <div class="flex-1">
                        <div class="flex items-center justify-center h-16 px-4 sm:px-6 lg:px-8 pt-20">
                            <!-- Logo -->
                            <a class="block" href="{{ route('dashboard') }}">
                                <div class="">
                                    <img class="" src="{{ asset('images/LOGO-SRS.png') }}" width="170" height="170" alt="Alex Shatov" />
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="w-full max-w-sm mx-auto px-4 py-8">
                        <h1 class="text-3xl text-slate-800 dark:text-slate-100 font-bold mb-6">{{ __('Track Progress
                            Sampel')
                            }}</h1>
                        <h1 class="italic mb-4">Masukkan kode unik sistem untuk melacak progress sampel anda !
                        </h1>

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
            <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
                <img class="object-cover object-center w-full h-full" src="{{ asset('images/YCH09564.jpg') }}" width="760" height="1024" alt="Authentication image" />
            </div>
        </div>

    </main>

</body>

</html>