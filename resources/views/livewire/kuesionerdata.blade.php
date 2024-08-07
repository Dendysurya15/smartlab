<div>
    <form wire:submit.prevent="create" class="flex flex-col items-end" id="testing">
        {{ $this->form }}
    </form>

    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            Livewire.on('reload-captcha', () => {
                location.reload(); // Call location.reload() to reload the page
            });
        });

        function callbackThen(response) {
            response.json().then(function(data) {
                if (data.success && data.score > 0.5) {
                    console.log('valid recaptcha');
                    @this.captchaToken = 'valid recaptcha';
                } else {
                    alert('recaptcha error');
                }
            });
        }

        function callbackCatch(error) {
            console.error('Error:', error);
        }

        // Function to be called after the CAPTCHA is completed
        function onSubmit(token) {
            @this.captchaToken = token; // Update Livewire component with token
        }
    </script>
</div>