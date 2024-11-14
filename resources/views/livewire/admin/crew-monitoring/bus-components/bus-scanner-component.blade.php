<div class="container">
    <div class="card text-center mt-5">

        <div class="card-header">
            <h2 class="card-title fw-bold">Bus Scanner</h2>
        </div>

        <div class="card-body">
            <x-request-message />
            <div id="reader" width="600px"></div>
        </div>

        <div class="card-footer text-body-secondary">
        </div>

    </div>

        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script type="text/javascript">
            function onScanSuccess(decodedText, decodedResult) {
                Livewire.emit('qrCodeScanned', decodedText);
            }

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: 250
                });
            html5QrcodeScanner.render(onScanSuccess);
        </script>
        
</div>
