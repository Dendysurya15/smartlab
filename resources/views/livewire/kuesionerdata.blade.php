<div>

    <div style="background-color: #f0f0f0; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Kuesioner Penilaian Layanan</h5>
            <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">
                Mohon luangkan waktu sejenak untuk memberikan pendapat yang jujur dan rinci. Jawaban Anda sangat berharga dalam membantu kami meningkatkan kualitas layanan.
            </p>

        </div>
        <svg version="1.1" id="Layer_1" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460 460" xml:space="preserve" fill="#000000">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <g>
                    <path style="fill:#FF5419;" d="M230,0C102.975,0,0,102.975,0,230c0,98.967,62.507,183.335,150.196,215.778L447.33,154.523 C416.094,64.571,330.588,0,230,0z"></path>
                    <path style="fill:#E03A00;" d="M447.313,154.521l-86.24-86.24L21.407,230l116.782,116.781L100,394.5l50.196,51.27 C175.058,454.968,201.94,460,230,460c127.025,0,230-102.975,230-230C460,203.559,455.524,178.166,447.313,154.521z"></path>
                    <polygon style="fill:#FFEDB5;" points="276.563,87.084 276.563,48.281 230,48.281 214.479,87.084 "></polygon>
                    <rect x="183.437" y="48.281" style="fill:#FFFFFF;" width="46.563" height="38.802"></rect>
                    <polygon style="fill:#FFC61B;" points="214.829,371.741 230,394.5 361.073,394.5 361.073,68.281 302,68.281 "></polygon>
                    <polygon style="fill:#C2FBFF;" points="341.07,129 341.07,374.5 230,374.5 210.47,125 "></polygon>
                    <polygon style="fill:#71E2F0;" points="341.07,88.28 341.07,129 230,129 206.66,88.28 "></polygon>
                    <polygon style="fill:#FEE187;" points="158,68.281 100,68.281 100,394.5 230,394.5 230,371.741 "></polygon>
                    <polygon style="fill:#FFFFFF;" points="230,125 230,374.5 118.93,374.5 118.93,129 "></polygon>
                    <rect x="118.93" y="88.28" style="fill:#C2FBFF;" width="111.07" height="40.72"></rect>
                    <polygon style="fill:#FEE187;" points="230,68.281 214.827,88.281 230,108.281 302,108.281 302,68.281 "></polygon>
                    <rect x="158" y="68.281" style="fill:#FFEDB5;" width="72" height="40"></rect>
                    <polygon style="fill:#C2FBFF;" points="138.927,287.5 138.927,307.5 230,307.5 239.5,297.5 230,287.5 "></polygon>
                    <rect x="230" y="287.5" style="fill:#71E2F0;" width="51.073" height="20"></rect>
                    <rect x="301.073" y="287.5" style="fill:#71E2F0;" width="20" height="20"></rect>
                    <polygon style="fill:#C2FBFF;" points="138.927,331 138.927,351 230,351 239.5,341 230,331 "></polygon>
                    <rect x="230" y="331" style="fill:#71E2F0;" width="51.073" height="20"></rect>
                    <rect x="301.073" y="331" style="fill:#71E2F0;" width="20" height="20"></rect>
                    <polygon style="fill:#C2FBFF;" points="138.927,172.5 138.927,152.5 230,152.5 239.5,162.5 230,172.5 "></polygon>
                    <rect x="230" y="152.5" style="fill:#71E2F0;" width="51.073" height="20"></rect>
                    <rect x="301.073" y="152.5" style="fill:#71E2F0;" width="20" height="20"></rect>
                    <polygon style="fill:#FEE187;" points="100,265 21.407,230 100,211.333 "></polygon>
                    <polygon style="fill:#FFFFFF;" points="100,230 21.407,230 100,195 "></polygon>
                    <polygon style="fill:#121149;" points="341.411,264.999 100,265 100,230 351.411,211.333 "></polygon>
                    <polygon style="fill:#366796;" points="351.411,230 100,230 100,195 341.411,195 "></polygon>
                    <rect x="230" y="219.474" style="fill:#FFFFFF;" width="121.411" height="20"></rect>
                    <polygon style="fill:#FEE187;" points="431.411,239.47 431.411,220.53 381.411,220.526 381.411,239.474 "></polygon>
                    <polygon style="fill:#121149;" points="401.64,265 361.64,265 351.64,211.333 401.64,230 "></polygon>
                    <polygon style="fill:#366796;" points="401.64,230 351.64,230 361.64,195 401.64,195 "></polygon>
                    <polygon style="fill:#FEE187;" points="341.411,264.999 341.411,230 346.411,220.666 361.411,230 361.411,264.999 "></polygon>
                    <rect x="341.411" y="195.002" style="fill:#FFFFFF;" width="20" height="35"></rect>
                </g>
            </g>
        </svg>
    </div>

    <form wire:submit.prevent="create" class="flex flex-col items-end" id="formContainer">
        <!-- Hidden captcha response field -->
        <input type="hidden" wire:model="captchaResponse" />

        {{ $this->form }}
        <div class="flex justify-center mt-4">
            <x-filament::modal id="transactionModal">
                <x-slot name="header">
                    Anda yakin ingin mengirim data?
                </x-slot>

                <div class="flex justify-center mt-4">
                    <div class="inline-flex rounded-md shadow-sm margin-2" role="group">
                        <button type="button" wire:click="accpetsubmit" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Ya</button>
                        <button type="button" wire:click="decline" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Tidak</button>
                    </div>
                </div>
            </x-filament::modal>
        </div>
    </form>

    <x-filament-actions::modals />

    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            Livewire.on('thankyou', () => {
                // window.location.href = '/thanks';
                setTimeout(() => {
                    window.location.href = '/thanks';
                }, 1200);
            });
        });
    </script>

    <!-- reCAPTCHA data attributes for JavaScript initialization -->
    <div data-recaptcha-site-key="{{ config('services.recaptcha.site_key_v3') }}"
        data-livewire-id="{{ $this->getId() }}"
        style="display: none;"></div>
</div>