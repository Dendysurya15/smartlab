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
                                {{-- <svg width="32" height="32" viewBox="0 0 32 32">
                                    <defs>
                                        <linearGradient x1="28.538%" y1="20.229%" x2="100%" y2="108.156%" id="logo-a">
                                            <stop stop-color="#A5B4FC" stop-opacity="0" offset="0%" />
                                            <stop stop-color="#A5B4FC" offset="100%" />
                                        </linearGradient>
                                        <linearGradient x1="88.638%" y1="29.267%" x2="22.42%" y2="100%" id="logo-b">
                                            <stop stop-color="#38BDF8" stop-opacity="0" offset="0%" />
                                            <stop stop-color="#38BDF8" offset="100%" />
                                        </linearGradient>
                                    </defs>
                                    <rect fill="#6366F1" width="32" height="32" rx="16" />
                                    <path
                                        d="M18.277.16C26.035 1.267 32 7.938 32 16c0 8.837-7.163 16-16 16a15.937 15.937 0 01-10.426-3.863L18.277.161z"
                                        fill="#4F46E5" />
                                    <path
                                        d="M7.404 2.503l18.339 26.19A15.93 15.93 0 0116 32C7.163 32 0 24.837 0 16 0 10.327 2.952 5.344 7.404 2.503z"
                                        fill="url(#logo-a)" />
                                    <path
                                        d="M2.223 24.14L29.777 7.86A15.926 15.926 0 0132 16c0 8.837-7.163 16-16 16-5.864 0-10.991-3.154-13.777-7.86z"
                                        fill="url(#logo-b)" />
                                </svg> --}}
                                <div class="">
                                    <img class="" src="{{ asset('images/LOGO-SRS.png') }}" width="170" height="170"
                                        alt="Alex Shatov" />
                                </div>
                            </a>
                            {{-- <p>kasdjflk</p> --}}
                        </div>
                    </div>

                    <div class="w-full max-w-sm mx-auto px-4 py-8">
                        <h1 class="text-3xl text-slate-800 dark:text-slate-100 font-bold mb-6">{{ __('Track Progress
                            Sampel')
                            }}</h1>
                        <h1 class="italic mb-4">Masukkan kode unik sistem untuk melacak progress sampel anda !
                            {{-- <span class="text-emerald-600 font-medium">Smart Lab</span>! --}}
                        </h1>
                        <form method="POST" action="{{ route('register') }}" id="tracking-form">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-jet-label for="kode">{{ __('Kode') }} <span class="text-rose-500">*</span>
                                    </x-jet-label>
                                    <x-jet-input id="kode" type="number" name="kode" :value="old('kode')" required
                                        autofocus autocomplete="kode" />
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-6">

                                <x-jet-button>
                                    {{ __('Submit') }}
                                </x-jet-button>
                            </div>

                        </form>


                        <div class="mt-5" id="result" style="display: none">
                            <div
                                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <a href="#">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                                        id="kode_track_hasil">
                                    </h5>
                                </a>
                                {{-- <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400">
                                    <li class="flex items-start space-x-3">
                                        <svg class="mt-1 w-4.5 h-3.5 text-green-500 dark:text-green-400"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 16 12">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                        </svg>
                                        <div>
                                            <p class="mb-3" id="caption">sdjkfkdsjf</p>

                                            <p class="mb-3" id="tgl_update"></p>
                                        </div>
                                    </li>
                                </ul> --}}
                                <ul id="progress-list" class="space-y-4 text-left text-gray-500 dark:text-gray-400">

                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 mb-2 text-sm text-slate-600 font-medium italic" id="result_empty"
                            style="display: none">Tidak menemukan sampel dengan kode <span id="kode_track_failed"
                                class="text-red-600"></span>

                        </div>
                    </div>

                </div>

            </div>

            <!-- Image -->
            <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
                <img class="object-cover object-center w-full h-full" src="{{ asset('images/YCH09564.jpg') }}"
                    width="760" height="1024" alt="Authentication image" />
                {{-- <img class="absolute top-1/4 left-0 -translate-x-1/2 ml-8 hidden lg:block"
                    src="{{ asset('images/auth-decoration.png') }}" width="218" height="224"
                    alt="Authentication decoration" /> --}}
            </div>
        </div>

    </main>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('tracking-form');
    const resultElement = document.getElementById('result');
    const resultEmptyElement = document.getElementById('result_empty');
    const kode_track_hasil = document.getElementById('kode_track_hasil');
    const kode_track_failed = document.getElementById('kode_track_failed');
    const ulElement = document.getElementById('progress-list');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const kode = document.getElementById('kode').value;

        // Make an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('search_sampel_progress') }}', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        
        xhr.onerror = function() {
            console.error('Network request failed');
        };

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {

                if (xhr.status === 200) {
                    
                        const response = JSON.parse(xhr.responseText);
                        console.log(response);

                        if (response && Object.keys(response).length > 0) {
                            resultEmptyElement.style.display = 'none';
                            resultElement.style.display = 'block';
                            
                            if (!ulElement) {
                                // If the ul element doesn't exist, create it
                                ulElement = document.createElement('ul');
                                ulElement.id = 'progress-list';
                                ulElement.classList.add('space-y-4', 'text-left', 'text-gray-500', 'dark:text-gray-400');

                                // Append the newly created ul to the resultElement
                                resultElement.appendChild(ulElement);
                            }
                            ulElement.innerHTML = '';
                            kode_track_hasil.textContent = response.kode_track;
                            var arr_progress = response.progress;

                            arr_progress.forEach(element => {
                                const li = document.createElement('li');
                                li.classList.add('flex', 'items-start', 'space-x-3');

                                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                                svg.classList.add('mt-1', 'w-4.5', 'h-3.5', 'text-green-500', 'dark:text-green-400');
                                svg.setAttribute('aria-hidden', 'true');
                                svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                                svg.setAttribute('fill', 'none');
                                svg.setAttribute('viewBox', '0 0 16 12');

                                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                                path.setAttribute('stroke', 'currentColor');
                                path.setAttribute('stroke-linecap', 'round');
                                path.setAttribute('stroke-linejoin', 'round');
                                path.setAttribute('stroke-width', '2');
                                path.setAttribute('d', 'M1 5.917 5.724 10.5 15 1.5');
                                svg.appendChild(path);

                                const div = document.createElement('div');
                                const p = document.createElement('p');
                                p.classList.add('mb-0', 'text-sm', 'text-slate-800', 'font-semibold');
                                p.textContent = element;
                                const p2 = document.createElement('p');
                                p2.classList.add('mb-2', 'text-sm', 'text-slate-600', 'font-medium', 'italic');
                                p2.textContent = response.last_update;

                                // Append the SVG and text content to the li element
                                div.appendChild(p);
                                div.appendChild(p2);
                                li.appendChild(svg);
                                li.appendChild(div);

                                // Append the li element to the ul
                                ulElement.appendChild(li);
                            });
                        } else {
                            resultElement.style.display = 'none';
                            resultEmptyElement.style.display = 'block';
                            // console.log('Empty response received.');
                            kode_track_failed.textContent =  kode ;
                        }
                    
                }
                // Handle other HTTP status codes here if necessary
            }
        };

        const data = JSON.stringify({ kode: kode });
        xhr.send(data);
    });
});

</script>