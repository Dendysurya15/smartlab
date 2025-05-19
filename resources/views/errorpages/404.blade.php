<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-8 bg-white rounded-lg shadow-lg text-center">
        <h1 class="text-9xl font-bold text-blue-600">404</h1>
        <div class="my-6 h-1 w-24 bg-blue-500 mx-auto rounded-full"></div>
        <p class="text-gray-700 text-xl mb-2">Data yang anda cari mungkin terhapus / tidak tersedia.</p>
        <p class="text-gray-500 mb-8">Silahkan cek kembali</p>
        <a href="{{ route('history_sampel.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out">
            Dashboard
        </a>
    </div>
</body>

</html>