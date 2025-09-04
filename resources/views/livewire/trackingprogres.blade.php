<div class="w-full max-h-[70vh] overflow-y-auto">

    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Hidden captcha response field -->
        <input type="hidden" wire:model="captchaResponse" />

        <div>
            <x-label for="kode" class="block text-sm font-medium text-gray-700">
                {{ __('Kode Tracking') }} <span class="text-rose-500">*</span>
            </x-label>
            <div class="mt-1 relative">
                <x-input type="text"
                    wire:model.live="progressid"
                    id="kode"
                    name="kode"
                    placeholder="Contoh: SRS2025"
                    :value="old('kode')"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 pr-10"
                    required
                    autofocus
                    autocomplete="off" />

                @if($progressid)
                <button type="button"
                    wire:click="clearInput"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </button>
                @endif
            </div>
            <p class="mt-2 text-sm text-gray-500">
                Masukkan kode unik sistem untuk melacak progress sampel Anda
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <!-- Submit Button -->
            <button type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <svg wire:loading wire:target="save" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Submit</span>
                <span wire:loading wire:target="save">Mencari Data...</span>
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
                <span wire:loading.remove wire:target="downloadPdf">Download KUPA</span>
                <span wire:loading wire:target="downloadPdf" class="inline-flex items-center">
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

            @if($fotoSampel && count($fotoSampel) > 0)
            <div wire:key="photos-{{ $progressid }}" class="mt-4 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Foto Sampel</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($fotoSampel as $index => $foto)
                        <div class="relative group">
                            @php
                            $cleanFoto = ltrim($foto, '/');
                            $imagePath = 'storage/' . $cleanFoto;
                            @endphp
                            <img src="{{ asset($imagePath) }}"
                                alt="Foto Sampel {{ $index + 1 }}"
                                class="w-full h-80 sm:h-96 lg:h-[500px] object-cover rounded-lg shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105"
                                data-image-src="{{ asset($imagePath) }}"
                                data-image-title="Foto Sampel {{ $index + 1 }}"
                                onclick="openImageModal(this.dataset.imageSrc, this.dataset.imageTitle)"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4='; this.alt='Image not found';">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif


            @endif
        </div>
    </form>
    @if ($resultData === null)
    <!-- Initial State - Belum ada input -->
    <div wire:key="initial-state" class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">
                    Siap Melacak Progress Sampel
                </h3>
                <p class="mt-1 text-sm text-blue-700">
                    Masukkan kode tracking di atas untuk melihat status progress pengerjaan sampel Anda.
                </p>
            </div>
        </div>
    </div>
    @elseif ($resultData !== null && $resultData !== 'kosong')
    <!-- Success Message -->
    <div wire:key="success-{{ $progressid }}" class="mt-8 bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">
                    Data Tracking Ditemukan!
                </h3>
                <p class="mt-1 text-sm text-green-700">
                    Berhasil menemukan data untuk kode tracking <strong>"{{ $progressid }}"</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Progress Data -->
    <div wire:key="progress-{{ $progressid }}" class="mt-4 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Progress Pengerjaan Sampel</h3>
        </div>
        <div class="max-h-[500px] overflow-y-auto">
            @foreach ($resultData as $key => $value)
            <div class="px-6 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-sm font-medium text-gray-900">
                            {{$value['text']}}
                        </h2>
                        @if ($value['status'] === 'checked' && $value['time'])
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $value['time'] }}
                        </p>
                        @endif
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        @if ($value['status'] === 'checked')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Selesai
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Menunggu
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif ($resultData === 'kosong')
    <div wire:key="error-{{ $progressid }}" class="mt-8 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Kode Tracking Tidak Ditemukan
                </h3>
                <p class="mt-2 text-sm text-red-700">
                    Kode tracking <strong>"{{ $progressid }}"</strong> yang Anda masukkan tidak terdaftar di database kami.
                    <br>Harap periksa dan pastikan kode yang dimasukkan sudah benar.
                </p>
                <div class="mt-3 text-xs text-red-600">
                    <p>• Pastikan tidak ada spasi di awal atau akhir kode</p>
                    <p>• Periksa huruf besar/kecil (case sensitive)</p>
                    <p>• Hubungi customer service jika Anda yakin kode sudah benar</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Terjadi Kesalahan
                </h3>
                <p class="mt-2 text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>

    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let siteKey = "{{ config('services.recaptcha.site_key_v3') }}";
            let currentToken = null;

            // console.log("🔑 reCAPTCHA siteKey:", siteKey);

            // Function to execute reCAPTCHA and store token
            function executeRecaptcha() {
                return new Promise((resolve, reject) => {
                    grecaptcha.ready(function() {
                        // console.log("✅ reCAPTCHA ready, executing...");
                        grecaptcha.execute(siteKey, {
                            action: 'submit'
                        }).then(function(token) {
                            // console.log("🎟️ reCAPTCHA token received:", token);
                            currentToken = token;

                            // Set ke Livewire dengan multiple methods
                            try {
                                // Method 1: Direct wire:model binding
                                let hiddenInput = document.querySelector('input[wire\\:model="captchaResponse"]');
                                if (hiddenInput) {
                                    hiddenInput.value = token;
                                    // Trigger input event untuk Livewire
                                    hiddenInput.dispatchEvent(new Event('input'));
                                }

                                // Method 2: Livewire component set
                                let lw = Livewire.find("{{ $this->getId() }}");
                                if (lw) {
                                    lw.set('captchaResponse', token);
                                    // console.log("📡 Token injected ke Livewire:", token);
                                }

                                resolve(token);
                            } catch (error) {
                                console.error("❌ Error setting token:", error);
                                reject(error);
                            }
                        }).catch(function(err) {
                            console.error("⚠️ Error saat execute reCAPTCHA:", err);
                            reject(err);
                        });
                    });
                });
            }

            // Execute reCAPTCHA when page loads
            executeRecaptcha();

            // Intercept form submission
            document.querySelector('form[wire\\:submit\\.prevent="save"]').addEventListener('submit', function(e) {
                e.preventDefault();

                // console.log("🔄 Form submission intercepted");

                // Ensure we have a fresh token
                executeRecaptcha().then(() => {
                    // Small delay to ensure token is set
                    setTimeout(() => {
                        // console.log("🚀 Submitting form with token:", currentToken);
                        // Manually trigger Livewire save method
                        Livewire.find("{{ $this->getId() }}").call('save');
                    }, 200);
                }).catch((error) => {
                    console.error("Failed to get captcha token:", error);
                    alert("Captcha verification failed. Please try again.");
                });
            });

            // Listen for Livewire events
            document.addEventListener('livewire:init', () => {
                Livewire.on('refresh-captcha', () => {
                    // console.log("🔄 Refreshing captcha from Livewire event");
                    executeRecaptcha();
                });
            });
        });
    </script>


</div>