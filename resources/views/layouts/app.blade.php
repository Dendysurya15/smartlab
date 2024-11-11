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

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400" :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex h-[100dvh]  overflow-hidden">

        <x-app.sidebar />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if($attributes['background']){{ $attributes['background'] }}@endif" x-ref="contentarea">

            <x-app.header />

            <main class="grow">
                {{ $slot }}
            </main>

        </div>

    </div>
    @filamentScripts
    @livewireScripts
    <script type="module">
        const userId = "{{ auth()->id() }}";
        let notificationElement = null;
        let downloadProgressElement = null;
        let currentProgress = 0;

        // Make removeDownloadProgress available globally
        window.removeDownloadProgress = function() {
            if (downloadProgressElement) {
                downloadProgressElement.remove();
                downloadProgressElement = null;
            }
        };

        const channel = window.Echo.channel(`pdf-export.${userId}`);
        // console.log('Subscribing to channel:', `pdf-export.${userId}`);

        function showNotification(message, progress = null, isError = false) {
            if (notificationElement) {
                notificationElement.remove();
            }

            notificationElement = document.createElement('div');
            notificationElement.className = `fixed bottom-4 right-4 bg-white dark:bg-slate-800 shadow-lg rounded-lg p-4 max-w-sm w-full mx-auto ${isError ? 'border-red-500 border' : ''}`;
            notificationElement.innerHTML = `
                <div class="flex items-center mb-2">
                    <div class="flex-1">${message}</div>
                </div>
                ${progress !== null ? `
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: ${progress}%"></div>
                </div>` : ''}
            `;

            document.body.appendChild(notificationElement);
        }

        function showDownloadProgress(filename, progress, status) {
            if (!downloadProgressElement) {
                downloadProgressElement = document.createElement('div');
                downloadProgressElement.className = 'fixed bottom-4 right-4 bg-white dark:bg-slate-800 shadow-lg rounded-lg p-4 w-80 z-50';
                document.body.appendChild(downloadProgressElement);
            }

            const statusColor = progress === 100 ? 'text-green-500' : 'text-blue-500';

            downloadProgressElement.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 ${statusColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        <div class="text-sm font-medium truncate max-w-[180px]" title="${filename || 'Preparing download...'}">
                            ${filename || 'Preparing download...'}
                        </div>
                    </div>
                    <div class="text-sm font-medium ${statusColor}">${progress}%</div>
                    ${progress === 100 ? `
                        <button onclick="removeDownloadProgress()" class="ml-2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    ` : ''}
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 mb-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: ${progress}%"></div>
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    ${status}
                </div>
            `;
        }

        function downloadPdf(url, filename) {
            removeDownloadProgress();

            showDownloadProgress(filename, 0, 'Starting download...');

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Download failed');
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(link.href);

                    showDownloadProgress(filename, 100, 'Download completed!');
                })
                .catch(error => {
                    console.error('Download error:', error);
                    showDownloadProgress(filename, 0, 'Download failed. Please try again.');
                    setTimeout(removeDownloadProgress, 5000);
                });
        }

        channel.listen('.PdfExportProgress', (event) => {
            // console.log('Received event:', event);
            currentProgress = event.progress;

            if (event.progress === 0) {
                removeDownloadProgress();
                showDownloadProgress(null, 0, 'Preparing PDF generation...');
            } else if (event.progress === 100 && event.filename && event.downloadUrl) {
                showDownloadProgress(event.filename, 90, 'PDF Generated! Starting download...');
                downloadPdf(event.downloadUrl, event.filename);
            } else if (event.status === "Merging PDFs...") {
                showDownloadProgress(null, 90, 'Merging PDFs...');
            } else {
                const message = event.currentChunk && event.totalChunks ?
                    `Processing chunk ${event.currentChunk} of ${event.totalChunks}` :
                    `Processing... ${event.progress}%`;
                showDownloadProgress(null, event.progress, message);
            }
        });
    </script>

</body>

</html>