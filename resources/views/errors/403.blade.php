<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Link to your CSS file -->
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600">403</h1>
        <p class="text-xl mt-4">Your IP is blocked due to multiple failed attempts.</p>
        <a href="{{ url('/') }}" class="mt-8 inline-block bg-blue-500 text-white px-4 py-2 rounded">Go to Homepage</a>
    </div>
</body>

</html>