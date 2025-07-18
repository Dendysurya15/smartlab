<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    @filamentStyles
    @livewireStyles
    @livewire('notifications')
    {!! htmlScriptTagJsApi([
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch',
    ]) !!}
</head>

<body class="bg-gray-100 font-sans">
    <div class="max-w-6xl mx-auto bg-white p-8 my-10 rounded-lg shadow-lg">
        <header class="flex items-center justify-between mb-8">
            <img src="{{asset('images/logocorp.png')}}" alt="Company Logo" class="h-16">
            <div class="text-right">
                <h1 class="text-xl font-bold">PT. Sulung Research Station</h1>
                <h2 class="text-md">Laboratorium Pengujian & Kalibrasi</h2>
            </div>
        </header>
        <main>
            @yield('content')
        </main>

        <footer class="text-sm text-gray-600">
            &copy; {{ date('Y') }} SRS | SmartLab. All rights reserved. Visit us at <a href="https://smartlab.srs-ssms.com/" class="text-blue-500 hover:underline">smartlab.srs-ssms.com</a>.
        </footer>


    </div>
    @filamentScripts
    @livewireScripts
</body>

</html>