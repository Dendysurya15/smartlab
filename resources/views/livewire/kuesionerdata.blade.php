<div>
    <form wire:submit.prevent="create" class="flex flex-col items-end" id="testing">
        {{ $this->form }}
    </form>

    {!! NoCaptcha::renderJs() !!}
    <div id="recaptcha" class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}" data-callback="onSubmit">
    </div>

    <script>
        function onSubmit(token) {
            @this.captchaToken = token;
        }

        // Listen for the custom event to reload CAPTCHA
        document.addEventListener('livewire:init', () => {
            Livewire.on('reload-captcha', (event) => {
                location.reload(); // Call location.reload() to reload the page
            });
        });
    </script>



</div>