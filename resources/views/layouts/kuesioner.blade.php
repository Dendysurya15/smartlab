<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire</title>
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
            <img src="your-logo-url.png" alt="Company Logo" class="h-16">
            <div class="text-right">
                <h1 class="text-xl font-bold">PT. Citra Borneo Indah</h1>
                <h2 class="text-md">Laboratorium Pengujian & Kalibrasi</h2>
            </div>
        </header>
        <main>
            @yield('content')
        </main>

        <footer class="text-sm text-gray-600">
            <p>Please send the filled form back to <a href="mailto:cs.labkbi@citraborneo.co.id" class="text-blue-500">cs.labkbi@citraborneo.co.id</a></p>
            <p>Document No: FR.5.4.2.1 | Effective Date: 01 October 2022</p>
        </footer>
    </div>
    @filamentScripts
    @livewireScripts
</body>

</html>