<section class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-bulletin-board"></i> Company Maintenance</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Company Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Company Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <i class="mdi mdi-library-plus"></i> Add Company
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-header border-bottom-0">
                    <form class="d-flex align-items-center">
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search" placeholder="Search Company">
                    </form>
                </div>

                <div class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox table-centered table-hover">
                        <thead class="table-secondary">
                            <tr>

                                <th>NO</th>
                                <th>COMPANY</th>
                                <th>ACTION</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ( $companies as $company_maint )
                            <tr>
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $loop->index + 1}}</h5>
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $company_maint->company}}</h5>
                                    </a>
                                </td>

                                <td>
                                    <button class="btn btn-primary btn-sm" wire:click="functionGetCompany({{$company_maint->companyid}})" data-bs-toggle="modal" data-bs-target="#editCompanyModal">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Edit
                                    </button>

                                    <div wire:ignore.self class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                                                        Edit Company
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form wire:submit.prevent="updateCompany">
                                                        <div class="mb-3 mb-2">
                                                            <div class="mb-3">
                                                                <label for="company">Company Name</label>
                                                                <input type="text" class="form-control" id="company" name="company" wire:model.defer="company">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="designation">Designation</label>
                                                                <input type="text" class="form-control" wire:model.defer="designation" id="designation">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="addressline1">Address Line 1</label>
                                                                <input type="text" class="form-control" wire:model.defer="addressline1" id="addressline1">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="addressline2">Address Line 2</label>
                                                                <input type="text" class="form-control" wire:model.defer="addressline2" id="addressline2">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="addressline3">Address Line 3</label>
                                                                <input type="text" class="form-control" wire:model.defer="addressline3" id="addressline3">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="position">Position</label>
                                                                <input type="text" class="form-control" wire:model.defer="position" id="position">
                                                            </div>

                                                        </div>


                                                        <div>
                                                            <button type="submit" class="btn btn-primary">Update Room</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-danger btn-sm" wire:click="deleteCompany({{$company_maint->companyid}})">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row mt-5" style="padding-bottom: 6.5em;">
                        {{ $companies->links('livewire.components.customized-pagination-link') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add Company
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addCompany">
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Company name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="company_name" required>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>