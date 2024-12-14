<div class="max-w-3xl mx-auto px-4 py-8">

    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <x-label for="kode" class="block text-sm font-medium text-gray-700">
                {{ __('Kode') }} <span class="text-rose-500">*</span>
            </x-label>
            <div class="mt-1">
                <x-input type="text"
                    wire:model="progressid"
                    id="kode"
                    name="kode"
                    :value="old('kode')"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    required
                    autofocus
                    autocomplete="kode" />
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                Submit
                <div wire:loading class="ml-2">
                    <svg aria-hidden="true" class="w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB" />
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor" />
                    </svg>
                </div>
            </button>
        </div>
    </form>

    @if ($resultData !== null && $resultData !== 'kosong')
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="max-h-[400px] overflow-y-auto divide-y divide-gray-200">
            @foreach ($resultData as $key => $value)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium text-gray-900">
                        {{$value['text']}}
                    </h2>
                    <div>
                        @if ($value['status'] === 'checked')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Complete
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Pending
                        </span>
                        @endif
                    </div>
                </div>
                @if ($value['status'] === 'checked' && $value['time'])
                <p class="mt-1 text-xs text-gray-500">
                    Completed on: {{ $value['time'] }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    <div class="mt-4">
        <!-- Download Buttons Section -->
        <div class="grid gap-4">
            <!-- PDF Download -->
            <button id="downloadPdfBtn"
                wire:click="downloadPdf"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                Download PDF
            </button>

            <!-- Excel Download -->
            <button id="downloadExcelBtn"
                wire:click="downloadExcel"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                Download Excel
            </button>

            @if($sertifikat)
            <!-- Certificate Download -->
            <button id="downloadCertBtn"
                wire:click="downloadSertifikat"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                Download Certificate
            </button>
            @endif
        </div>
    </div>
    @elseif ($resultData === 'kosong')
    <div class="mt-8 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Kode Sampel Tidak Valid
                </h3>
                <p class="mt-2 text-sm text-red-700">
                    Harap periksa dan cek kembali kode yang Anda masukkan.
                </p>
            </div>
        </div>
    </div>
    @endif
    <div wire:ignore class="mt-8 mb-4">
        <div class="g-recaptcha"
            data-sitekey="{{ config('services.recaptcha.site_key_v2') }}"
            data-callback="onCaptchaVerified">
        </div>
    </div>
    @if (session()->has('error'))
    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <script>
        window.addEventListener('openNewTab', event => {
            window.open(event.detail.url, '_blank');
        });

        function onCaptchaVerified(token) {
            @this.set('captchaResponse', token);
            enableAllButtons();
            @this.save();
        }

        function enableAllButtons() {
            const buttons = ['downloadPdfBtn', 'downloadExcelBtn', 'downloadCertBtn'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.disabled = false;
            });
        }

        function disableAllButtons() {
            const buttons = ['downloadPdfBtn', 'downloadExcelBtn', 'downloadCertBtn'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.disabled = true;
            });
        }

        // Initialize when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            disableAllButtons();
        });

        // Reset buttons when Livewire updates
        document.addEventListener('livewire:load', function() {
            Livewire.on('resetButtons', function() {
                disableAllButtons();
            });
        });

        function onCaptchaVerified(token) {
            @this.set('captchaResponse', token);
            enableAllButtons();
        }

        function enableAllButtons() {
            const buttons = ['downloadPdfBtn', 'downloadExcelBtn', 'downloadCertBtn'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.disabled = false;
            });
        }

        function disableAllButtons() {
            const buttons = ['downloadPdfBtn', 'downloadExcelBtn', 'downloadCertBtn'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.disabled = true;
            });
        }

        // Initialize when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            disableAllButtons();
        });
        document.addEventListener('livewire:load', function() {
            Livewire.on('notify', function(data) {
                // You can use any notification library here (Toastr, SweetAlert2, etc.)
                // Or create a simple alert
                alert(data.message);
            });
        });
    </script>

</div>