<section class="container-fluid p-4">
    <div class="row border-bottom">
        <div class="col-lg-12 text-center">
            <label class="h2" for="">JISS Billing Dashboard</label>
            <p>Here you can monitor the status for JISS training payment.</p>
        </div>
    </div>
    <div class="row mt-5 text-center">
        <style>
            .example_a {
                text-transform: uppercase;
                text-decoration: none;
                display: inline-block;
                border: none;
                transition: all 0.4s ease 0s;
            }

            .example_a:hover {
                letter-spacing: 1px;
                transition: all 0.4s ease 0s;
                padding: 0;
                margin: 0;
            }
        </style>
        <div class="row">
            @can('authorizeAdminComponents', 97)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(0)">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-file-post" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Pending Statements Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 1</label><br>
                        <label for="" class="h4">({{ $count[0] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            {{-- @can('authorizeAdminComponents', 98)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(1)">
                <div class="card shadow-lg">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-eye-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Billing Statement Review Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 2</label><br>
                        <label for="" class="h4">({{ $count[1] }})</label>
                    </div>
                </div>
            </a>
            @endcan --}}

            @can('authorizeAdminComponents', 99)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(1)">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white">
                        <i class="bi bi-eye-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">BOD Manager Review Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 2</label><br>
                        <label for="" class="h4">({{ $count[1] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            @can('authorizeAdminComponents', 100)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(2)">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color:#8f0d5d;">
                        <i class="bi bi-eye-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">GM Review Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 3</label><br>
                        <label for="" class="h4">({{ $count[2] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            @can('authorizeAdminComponents', 101)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(3)">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-eye-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">BOD Manager Review Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 4</label><br>
                        <label for="" class="h4">({{ $count[3] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            @can('authorizeAdminComponents', 102)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(4)">
                <div class="card shadow-lg">
                    <div class="card-header bg-secondary text-white">
                        <i class="bi bi-person-check-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Billing Sent to Client</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 5</label><br>
                        <label for="" class="h4">({{ $count[4] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            {{-- @can('authorizeAdminComponents', 103)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(5)">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color:#a7490b;">
                        <i class="bi bi-file-post" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">View Proof of Payment Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 6</label><br>
                        <label for="" class="h4">({{ $count[5] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            @can('authorizeAdminComponents', 104)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(6)">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color:#0b6620;">
                        <i class="bi bi-file-post" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Official Receipt Issuance Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 7</label><br>
                        <label for="" class="h4">({{ $count[6] }})</label>
                    </div>
                </div>
            </a>
            @endcan --}}

            @can('authorizeAdminComponents', 105)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(7)">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color:#014757;">
                        <i class="bi bi-file-post" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Official Receipt Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 8</label><br>
                        <label for="" class="h4">({{ $count[7] }})</label>
                    </div>
                </div>
            </a>
            @endcan

            @can('authorizeAdminComponents', 106)
            <a href="#" class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3 example_a" wire:click="Redirectto(8)">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color:#8f0d5d;">
                        <i class="bi bi-file-x-fill" style="font-size: 2em;"></i>
                        <br>
                        <span class="text-white h5">Transaction Close Board</span>
                    </div>
                    <div class="card-body">
                        <label for="" class="h5 badge bg-info-soft">Step 9</label><br>
                        <label for="" class="h4">({{ $count[8] }})</label>
                    </div>
                </div>
            </a>
            @endcan

        </div>
    </div>
    {{-- <div class="card-footer">
        <div class="row">
            <div class="col-12 d-grid">
                <button class="btn btn-lg btn-info">
                    Add
                </button>
            </div>
        </div>
    </div> --}}
</section>