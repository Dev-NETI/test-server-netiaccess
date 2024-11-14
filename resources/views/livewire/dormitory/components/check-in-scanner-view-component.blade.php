<div class="row">
    
    <div class="offset-xl-3 col-xl-6 col-12 mt-5">

        <div class="card mb-4 bg-gray-200">
            <div class="card-body">
                <h1 class="mb-4 hw-fw-bold">Check In Scanner Module</h1>
                
                <div class="row gx-3">

                    <x-dormitory-request-message />
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Scan here</label>
                        <input wire:model.debounce.500ms="scan" class="form-control" placeholder="Scan here" autofocus>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
