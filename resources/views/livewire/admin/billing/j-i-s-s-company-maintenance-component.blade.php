<section class="container-fluid">
    <div class="row text-center mt-3">
        <div class="col-12">
            <h1>JISS Company Management</h1>
            <p class="text-primary mb-1">Modify the information for the company.</p>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <span class="position-absolute ps-3 search-icon">
                                <i class="fe fe-search"></i>
                            </span>
                            <input type="search" class="form-control ps-6" wire:model="Search"
                                placeholder="Search Company">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-xl-12 col-12 mb-5">
                            <div class="card">
                                <!-- table  -->
                                <div class="table-responsive" style="min-height: 15em;">
                                    <table class="table table-striped text-nowrap mb-0 table-centered">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($companies as $data)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-3 lh-1">
                                                            <h5 class="mb-1"><a href="#" class="text-inherit">{{
                                                                    $data->company }}</a></h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="min-width: 8em;">
                                                    <div class="dropdown">
                                                        <a class="text-body text-primary-hover" href="#" role="button"
                                                            id="dropdownThirteen" data-bs-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="fe fe-more-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownThirteen">
                                                            <a class="dropdown-item" href="#"
                                                                wire:click="editCompany({{$data->id}})">Edit</a>
                                                            <a class="dropdown-item" href="#">Deactivate</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $companies->links('livewire.components.customized-pagination-link') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" wire:click='addcompany()' id="my-button" class="btn btn-primary position-fixed shadow" style="right: 60px;
                bottom: 60px;
                border-radius: 50%;
                height: 80px;
                width: 80px;
                display: flex;
                align-items: center;
                justify-content: center;">
        <i class="bi bi-plus-circle-fill" style="font-size: 3rem; margin: auto;"></i>
    </button>

    <div wire:ignore.self class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container mt-2">
                        <label for="companyname h5">Company Name</label>
                        <input type="text" id="companyname" class="form-control" wire:model="CompanyName">
                    </div>
                    <div class="container mt-2">
                        <label for="recipientname h5">Recipient Name</label>
                        <input type="text" id="recipientname" class="form-control" wire:model="RecipientName">
                    </div>
                    <div class="container mt-2">
                        <label for="recipientpos h5">Recipient Position</label>
                        <input type="text" id="recipientpos" class="form-control" wire:model="RecipientPosition">
                    </div>
                    <div class="container mt-2">
                        <label for="adl h5">Address Line 1</label>
                        <input type="text" id="adl" class="form-control" wire:model="AddressLine">
                    </div>
                    <div class="container mt-2">
                        <label for="adl2 h5">Address Line 2</label>
                        <input type="text" id="adl2" class="form-control" wire:model="AddressLine2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if ($isUpdate == 0)
                    <button type="button" wire:click="ExecuteAddCompany" class="btn btn-primary">Add</button>
                    @else
                    <button type="button" wire:click="executeEditCompany" class="btn btn-primary">Update</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>