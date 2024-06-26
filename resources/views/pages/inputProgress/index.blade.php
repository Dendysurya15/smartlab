<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="flex px-5 py-4 bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h2 class="font-bold text-slate-200 dark:text-slate-100">INPUT PENGERJAAN PROGRESS SAMPEL BARU</h2>
            </header>


            <div class="p-5">
                @livewire('input-progress')
            </div>
        </div>
    </div>
    <script>
        // Function to handle beforeunload event
        function handleBeforeUnload(e) {
            const confirmationMessage = 'Anda yakin ingin meninggalkan halaman ini? Data yang anda input akan hilang.';
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        }
        window.addEventListener('beforeunload', handleBeforeUnload);
    </script>
</x-app-layout>