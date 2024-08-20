<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartlab</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
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

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 h-screen overflow-hidden">
    <nav class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-white/30 dark:bg-slate-800/30 backdrop-blur-lg shadow-lg rounded-full w-10/12 max-w-5xl px-6 py-2 flex justify-between items-center z-50 mt-4">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/LOGO-SRS.png') }}" width="100" height="100" alt="Smartlab Logo">
        </a>

        <!-- Login Button -->
        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
            Login
        </button>
    </nav>


    <main class="bg-white dark:bg-slate-900 h-full mt-16 flex">
        <!-- Content -->
        <div class="w-full md:w-1/2 flex items-center justify-center">
            <div class="max-w-sm mx-auto w-full px-4 py-8">
                {{ $slot }}
            </div>
        </div>

        <!-- Image -->
        <div class="hidden md:block md:w-1/2">
            <img class="object-cover object-center w-full h-full" src="{{ asset('images/YCH09527.jpg') }}"
                width="760" height="1024" alt="Authentication image" />
        </div>
    </main>
    <!-- Modal -->
    <div id="login-modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 hidden">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-lg max-w-md w-full">
            <!-- Modal Header -->
            <h1 class="text-3xl text-slate-800 dark:text-slate-700 font-bold mb-6">{{ __('Selamat Datang!') }}</h1>
            <h1 class="italic mb-4">
                Silahkan masukkan username dan password untuk masuk ke sistem web
                <span class="text-emerald-600 font-medium">Smart Lab</span>!
            </h1>

            <!-- Display Session Status -->
            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Email</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <input
                                type="text"
                                name="email"
                                id="email"
                                class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-2 ring-inset ring-emerald-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-700 sm:text-sm sm:leading-6"
                                placeholder="Masukkan Email"
                                required>
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-300">Password</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-2 ring-inset ring-emerald-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-700 sm:text-sm sm:leading-6"
                                placeholder="Masukkan Password"
                                required>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="flex items-center justify-between mt-6">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @filamentScripts
    @livewireScripts
    <script>
        function openModal() {
            document.getElementById('login-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('login-modal').classList.add('hidden');
        }
    </script>

</body>

</html>