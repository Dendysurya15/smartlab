@php
use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ landing_setting('site_name', 'SMARTLAB SRS') }}</title>

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

<body class="font-inter antialiased bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 text-slate-600 dark:text-slate-400 min-h-screen flex items-center justify-center">

    <!-- Login Form -->
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="flex items-center justify-center">
                    <img src="{{ asset('images/logo_srs_new.png') }}" class="h-12 w-auto" alt="{{ landing_setting('site_name', 'SMARTLAB SRS') }} Logo">
                </a>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-4">{{ landing_setting('site_name', 'SMARTLAB SRS') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2">Masuk ke sistem admin</p>
            </div>

            @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan Email" required autocomplete="email" autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan Password" required autocomplete="current-password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                    ← Kembali ke halaman utama
                </a>
            </div>
        </div>
    </div>

    @filamentScripts
    @livewireScripts
</body>

</html>