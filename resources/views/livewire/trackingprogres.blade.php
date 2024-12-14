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
            <!-- Submit Button -->
            <button type="submit"
                @if(!$captchaResponse) disabled @endif
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                Submit
                <div wire:loading wire:target="save" class="ml-2"> <!-- Add wire:target="save" -->
                    <svg aria-hidden="true" class="w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- ... svg path ... -->
                    </svg>
                </div>
            </button>

            @if ($resultData !== null && $resultData !== 'kosong')
            <!-- PDF Download -->
            <button id="downloadPdfBtn"
                wire:click.prevent="downloadPdf"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                <span wire:loading.remove wire:target="downloadPdf">Download PDF</span>
                <span wire:loading wire:target="downloadPdf" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <!-- ... svg path ... -->
                    </svg>
                    Processing...
                </span>
            </button>

            <!-- Excel Download -->
            <button id="downloadExcelBtn"
                wire:click.prevent="downloadExcel"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                <span wire:loading.remove wire:target="downloadExcel">Download Excel</span>
                <span wire:loading wire:target="downloadExcel" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <!-- ... svg path ... -->
                    </svg>
                    Processing...
                </span>
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
            @endif
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
    @if (session()->has('error') && !request()->isMethod('post'))
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
        function onCaptchaVerified(token) {
            @this.dispatch('captchaResponse', {
                response: token
            });
        }

        function resetCaptchaWidget() {
            grecaptcha.reset();
        }

        document.addEventListener('livewire:load', function() {
            Livewire.on('resetCaptcha', function() {
                resetCaptchaWidget();
            });
        });
    </script>
</div>