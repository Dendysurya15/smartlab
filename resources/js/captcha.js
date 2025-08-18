/**
 * reCAPTCHA v3 integration for Livewire components
 */

window.initRecaptcha = function(siteKey, componentId) {
    if (typeof grecaptcha === 'undefined') {
        console.warn('reCAPTCHA not loaded');
        return;
    }

    grecaptcha.ready(function() {
        // Generate token when page loads
        grecaptcha.execute(siteKey, {
            action: 'homepage'
        }).then(function(token) {
            // console.log('reCAPTCHA token generated:', token.substring(0, 20) + '...');
            
            // Set the token to the Livewire component
            if (window.Livewire && componentId) {
                try {
                    // Try different methods to find and set the component
                    const component = window.Livewire.find(componentId);
                    if (component) {
                        component.set('captchaResponse', token);
                        // console.log('Captcha token set successfully to component:', componentId);
                    } else {
                        console.error('Livewire component not found:', componentId);
                        
                        // Alternative method: try to use wire:model directly
                        const element = document.querySelector('[wire\\:model="captchaResponse"]');
                        if (element) {
                            element.value = token;
                            element.dispatchEvent(new Event('input', { bubbles: true }));
                            // console.log('Captcha token set via wire:model');
                        } else {
                            console.error('No wire:model element found for captchaResponse');
                        }
                    }
                } catch (error) {
                    console.error('Error setting captcha response:', error);
                }
            } else {
                console.error('Livewire not available or missing component ID');
            }
        }).catch(function(error) {
            console.error('reCAPTCHA execution error:', error);
        });
    });
};

// Function to initialize reCAPTCHA when element becomes available
function initRecaptchaWhenReady() {
    const recaptchaElement = document.querySelector('[data-recaptcha-site-key]');
    
    if (recaptchaElement) {
        const siteKey = recaptchaElement.getAttribute('data-recaptcha-site-key');
        const componentId = recaptchaElement.getAttribute('data-livewire-id');
        
        // console.log('Site key:', siteKey);
        // console.log('Component ID:', componentId);
        
        if (siteKey && componentId) {
            // console.log('Waiting for Livewire to initialize...');
            
            // Try multiple ways to detect Livewire initialization
            function tryInitRecaptcha() {
                if (window.Livewire) {
                    // console.log('Livewire found, starting reCAPTCHA...');
                    initRecaptcha(siteKey, componentId);
                    return true;
                }
                return false;
            }
            
            // Method 1: Check if Livewire is already available
            if (!tryInitRecaptcha()) {
                // Method 2: Listen for livewire:initialized event
                document.addEventListener('livewire:initialized', function() {
                    // console.log('Livewire initialized event fired, starting reCAPTCHA...');
                    initRecaptcha(siteKey, componentId);
                });
                
                // Method 3: Listen for livewire:load event (older Livewire versions)
                document.addEventListener('livewire:load', function() {
                    // console.log('Livewire load event fired, starting reCAPTCHA...');
                    initRecaptcha(siteKey, componentId);
                });
                
                // Method 4: Polling fallback (last resort)
                let attempts = 0;
                const maxAttempts = 50;
                const pollInterval = setInterval(function() {
                    attempts++;
                    if (tryInitRecaptcha()) {
                        clearInterval(pollInterval);
                    } else if (attempts >= maxAttempts) {
                        console.error('Failed to initialize Livewire after', maxAttempts, 'attempts');
                        clearInterval(pollInterval);
                    }
                }, 100);
            }
        } else {
            console.error('Missing site key or component ID');
        }
        return true; // Element found and processed
    } else {
        return false; // Element not found yet
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Try immediately
    if (!initRecaptchaWhenReady()) {
        // If element not found, wait for Livewire components to load
        document.addEventListener('livewire:initialized', function() {
            setTimeout(initRecaptchaWhenReady, 100);
        });
        
        document.addEventListener('livewire:load', function() {
            setTimeout(initRecaptchaWhenReady, 100);
        });
        
        // Polling fallback - check every 500ms for up to 10 seconds
        let pollAttempts = 0;
        const maxPollAttempts = 20;
        const pollInterval = setInterval(function() {
            pollAttempts++;
            if (initRecaptchaWhenReady() || pollAttempts >= maxPollAttempts) {
                clearInterval(pollInterval);
                if (pollAttempts >= maxPollAttempts) {
                    console.warn('reCAPTCHA element not found after polling');
                }
            }
        }, 500);
    }
});
