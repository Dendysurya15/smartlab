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
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key_v3') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="font-inter antialiased bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 text-slate-600 dark:text-slate-400 h-screen overflow-hidden">
    <nav class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg shadow-lg rounded-full w-11/12 max-w-6xl px-8 py-3 flex justify-between items-center z-50 mt-6">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo_srs_new.png') }}" class="h-10 w-auto" alt="Smartlab Logo">
        </a>

        <label for="my_modal_7" class="btn btn-success hover:btn-success/90 text-white px-6 rounded-full transition-all duration-200 transform hover:scale-105">
            Log in
        </label>
    </nav>

    <input type="checkbox" id="my_modal_7" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box bg-white dark:bg-slate-800 p-8 max-w-md w-full rounded-2xl shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl text-slate-800 dark:text-slate-100 font-bold mb-3">{{ __('Selamat Datang!') }}</h1>
                <p class="text-slate-600 dark:text-slate-400">
                    Silahkan masukkan username dan password untuk masuk ke sistem web
                    <span class="text-emerald-600 font-semibold">Smart Lab</span>
                </p>
            </div>

            @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="email-unique" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Email</label>
                        <div class="relative">
                            <input type="text" name="email" id="email-unique"
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan Email" autocomplete="email" required>
                        </div>
                    </div>

                    <div>
                        <label for="password-unique" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password-unique"
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan Password" required autocomplete="current-password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>
        </div>
        <label class="modal-backdrop" for="my_modal_7">Close</label>
    </div>

    <main class="bg-white dark:bg-slate-900 h-full mt-16 flex">
        <div class="w-full md:w-1/2 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <h1 class="text-4xl text-slate-800 dark:text-slate-100 font-bold mb-4">{{ __('Track Progress Sampel') }}</h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">
                    Masukkan kode unik sistem untuk melacak progress sampel anda!
                </p>

                {{ $slot }}
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative overflow-hidden">
            <img class="object-cover object-center w-full h-full transform hover:scale-105 transition-transform duration-700"
                src="{{ asset('images/YCH09527aa.jpg') }}" alt="Authentication image" />
        </div>
    </main>

    <div id="login-modal" class="fixed inset-0  items-center justify-center bg-gray-900 bg-opacity-50 z-50 hidden">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-3xl text-slate-800 dark:text-slate-700 font-bold mb-6">{{ __('Selamat Datang!') }}</h1>
            <h1 class="italic mb-4">
                Silahkan masukkan username dan password untuk masuk ke sistem web
                <span class="text-emerald-600 font-medium">Smart Lab</span>!
            </h1>

            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
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
        function onCaptchaVerified(v2Token) {
            window.captchaV2Token = v2Token;
        }

        function onSubmitTrack(e) {
            e.preventDefault();
            console.log('Submit track started');

            if (!window.captchaV2Token) {
                alert('Please complete the captcha verification');
                return;
            }

            grecaptcha.ready(function() {
                console.log('reCAPTCHA v3 ready');

                grecaptcha.execute('{{ config("services.recaptcha.site_key_v3") }}', {
                        action: 'submit'
                    })
                    .then(function(v3Token) {
                        console.log('V3 Token received');

                        if (v3Token) {
                            Livewire.dispatch('setCaptchaToken', {
                                v2Token: window.captchaV2Token,
                                v3Token: v3Token.trim()
                            });
                        } else {
                            console.error('No v3 token received');
                        }
                    })
                    .catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                    });
            });
        }
    </script>

</body>

</html>