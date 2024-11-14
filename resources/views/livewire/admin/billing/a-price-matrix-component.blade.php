<section>
    <div class="container-fluid mt-4 mb-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h2 class="h1 fw-bold mt-3">Client Management</h2>
                        <p class="mb-0 fs-4">Here you can manage informations of clients.</p>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12 form-floating">
                                <input type="text" id="formfloating" placeholder="search company .." wire:model="search"
                                    class="form-control">
                                <label for="formfloating" class="form-label ms-3">Search:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 table-responsive bg-white rounded-end">
                                <table style="min-height: 15em;" class="table table-hover table-striped mb-5"
                                    width="100%">
                                    <thead class="text-center">
                                        <tr>
                                            <th scope="col">Company</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="mb-5">

                                        @if ($companies->count())

                                        @foreach ($companies as $company)
                                        <tr>
                                            <td>{{ $company->company }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.bank-info")'>Bank</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.client-info")'>Client
                                                                Info</a></li>
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.billing-pricematrix")'>Course
                                                                Prices</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.email-management")'>Email</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="text-center">
                                            <td colspan="2">-----No Records Found-----</td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-lg-12">
                                {{ $companies->links('livewire.components.customized-pagination-link') }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            @if (session('companyid') != null)
            <div class="col-lg-12 mt-4">
                <livewire:admin.billing.a-price-matrix-courses-settings-components />
            </div>
            @endif

            @if (session('companyid') != null && session('companyid') == 262)
            <div class="col-lg-12 mt-4">
                <livewire:admin.billing.a-nyksm-company-management-component />
            </div>
            @endif
        </div>

    </div>
</section>