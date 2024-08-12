<div style="width: 100%; max-width: 400px;">
    <canvas id="signature-pad" style="border: 1px solid #000; width: 100%; height: auto;"></canvas>

    <script type="module">
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        // Adjust canvas size to be responsive
        function resizeCanvas() {
            const ratio = window.devicePixelRatio || 1;
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.width / 2; // Maintain a 2:1 aspect ratio
            canvas.getContext('2d').scale(ratio, ratio);

            signaturePad.clear(); // Clear canvas to ensure proper scaling
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas(); // Initial resize

        document.getElementById('save').addEventListener('click', () => {
            const dataURL = signaturePad.toDataURL(); // Save as image
            // console.log(dataURL);
            @this.dataURL = dataURL;
        });
    </script>
</div>