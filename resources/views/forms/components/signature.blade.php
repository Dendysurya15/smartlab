<div class="flex flex-col items-center">
    <h1 class="mb-4">Ttd Pelanggan:</h1>
    <div class="flex items-center justify-center">
        <canvas id="myCanvas" class="border-gray-300 border-2 rounded-lg"></canvas>
    </div>
    <input type="button" value="Hapus" id='resetSign' class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    <script type="module">
        $(document).ready(function() {
            $('#myCanvas').sign({
                resetButton: $('#resetSign'),
                lineWidth: 2,
                height: 150,
                width: 600
            });

            $('#save').click(function() {
                const canvas = document.getElementById('myCanvas');
                const dataURL = canvas.toDataURL(); // Save as image in Base64 format

                // Create an image object to load the data URL
                const img = new Image();
                img.src = dataURL;

                img.onload = function() {
                    const context = canvas.getContext('2d');
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    let hasSignature = false;

                    // Check if there are any non-transparent pixels
                    for (let i = 0; i < imageData.data.length; i += 4) {
                        if (imageData.data[i + 3] > 0) { // Alpha channel check
                            hasSignature = true;
                            break;
                        }
                    }

                    if (!hasSignature) {
                        // $('#error-message').removeClass('hidden'); // Show error message
                    } else {
                        // $('#error-message').addClass('hidden'); // Hide error message
                        // Send dataURL to server
                        @this.dataURL = dataURL;

                        // Optionally submit the form or perform other actions
                    }
                };
            });

            // $('#resetSign').click(function() {
            //     $('#error-message').addClass('hidden'); // Hide error message when resetting
            // });
        });
    </script>
</div>