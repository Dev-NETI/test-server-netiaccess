@props(['mobileScannerRoute','mobileScanner' => false,'mobileLabel'])
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($mobileScanner)
                        <a href="{{ route($mobileScannerRoute); }}" class="btn btn-outline-primary float-end mb-2">
                            {{$mobileLabel}}
                        </a>
                        @endif
                        <h3 for="">Local</h3>
                        <x-request-message />
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" id="inputGroup-sizing-lg"><i
                                    class="bi bi-upc-scan"></i></span>

                            <input type="text" class="form-control" placeholder="Scan Barcode Here" {{$attributes}}>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
