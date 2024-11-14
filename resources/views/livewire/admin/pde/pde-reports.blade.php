<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE Reports</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.pdereport') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                PDE Reports
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">

                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center mt-2" width="100%">
                        <thead>
                            <tr>
                                <th>Reports</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>


                            {{-- <tr>
                                <td>Electronic Log</td>
                                <td>
                                    <input type="submit" class="btn btn-primary sb-btn-xs rounded-pill" form="formelectroniclog" wire:click.prevent="btnprint" value="Print">
                                    <input type="submit" class="btn btn-success sb-btn-xs rounded-pill" form="formelectroniclog" wire:click.prevent="btndownload" value="Download">
                                </td>
                                <td>
                                    <form id="formelectroniclog" >
                                        From:
                                        <input type="date" form="formelectroniclog" wire:model.defer="datefrom" required>
                                        To:
                                        <input type="date" form="formelectroniclog" wire:model.defer="dateto" required>
                                    </form>
                                </td>
                            </tr> --}}

                            {{-- <tr>
                                <td>Electronic Log</td>
                                <td>
                                    <form wire:submit.prevent="formelectroniclog" id="formelectroniclog">
                                        <input type="submit" class="btn btn-primary sb-btn-xs rounded-pill" value="Print">
                                        From: <input type="date" wire:model.defer="datefrom" required>
                                        To: <input type="date" wire:model.defer="dateto" required>
                                    </form>
                                </td>
                            </tr> --}}


                            <!-- <tr>
                                <td>Annex 1 / Monthly Report</td>
                                <td>
                                    <form wire:submit.prevent="formannexone" id="formannexone">
                                        <input type="submit" class="btn btn-primary sb-btn-xs rounded-pill" value="Print">
                                        From: <input type="date" wire:model.defer="datefrom" required>
                                        To: <input type="date" wire:model.defer="dateto" required>
                                    </form>
                                </td>
                            </tr> -->


                            <!-- <tr>
                                <td>Annex 2 / Daily Report</td>
                                <td>
                                    <form wire:submit.prevent="formannextwo" id="formannextwo">
                                        <input type="submit" class="btn btn-primary sb-btn-xs rounded-pill" value="Print">
                                        From: <input type="date" wire:model.defer="datefrom" required>
                                        To: <input type="date" wire:model.defer="dateto" required>
                                    </form>
                                </td>
                            </tr> -->

                            <tr>
                                <td>Report </td>
                                <td>
                                    <form wire:submit.prevent="formexcelreport" id="formexcelreport">
                                        <input type="submit" class="btn btn-primary sb-btn-xs rounded-pill" value="Print">
                                        From: <input type="date" wire:model.defer="datefrom" required>
                                        To: <input type="date" wire:model.defer="dateto" required>
                                    </form>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</section>