<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="flex px-5 py-4 bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h2 class="font-bold text-slate-200 dark:text-slate-100">Penambahan Parameter Baru</h2>
            </header>
            @if(session()->has('message'))
            <div id="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p>{{ session('message') }}</p>
            </div>
            @endif


            <div class="p-5">
                @livewire('inputnewparameters')

                {{-- @livewire('delete-confirmation') --}}

            </div>
        </div>
    </div>
</x-app-layout>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 2000);


    window.addEventListener('showSweetAlert', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
        }).then((result) => {
            if (result.isConfirmed) {
                
                window.livewire.emit(event.detail.onConfirmed, event.detail.id);
            }
        });
    });
    
    window.addEventListener('showDeleteSuccessAlert', event => {
    Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.icon,
        showCancelButton: true,
        confirmButtonText: event.detail.confirmButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            // Emit a new event for reloading the page
            window.livewire.emit('reloadPageAfterDelete');
        }
    });
});
 // Refresh the Livewire component when the 'dataDeleted' event is emitted
 Livewire.on('dataDeleted', () => {
    location.reload();
    });
</script>