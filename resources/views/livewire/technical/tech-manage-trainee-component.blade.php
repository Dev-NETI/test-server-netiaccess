<div>
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-5">
                            <h2 class="fw-bold">Manage Trainees</h2>
                            <p class="mb-0">View/Manage All NTMA/NETI Trainee</p>
                        </div>
                    </div>
                    <div class="col-12 table-responsive">
                        <!-- striped rows -->
                        <table class="table table-default mt-4" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="input-group mb-1">
                                            <label class="input-group-text" for="inputGroupSelect01">Row Count</label>
                                            <select wire:model="rowcount" class="form-select" id="inputGroupSelect01">
                                                <option selected>5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="70">70</option>
                                                <option value="100">100</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <select wire:model="filterfleet" class="form-select" id="inputGroupSelect01">
                                                <option selected value="">Filter By Fleet</option>
                                                <option value="10">NTMA</option>
                                                <option value="17">NTMA-NETI</option>
                                            </select>
                                        </div>                                 
                                        <div class="col-sm-3 ms-auto">
                                            <div class="input-group mb-1 float-right">
                                            <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-search"></i></label>
                                            <input type="text" class="form-control" wire:model.debounce.1000ms="searchtext" placeholder="Search from First, Last, Middle Name">
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            </thead>
                            <thead class="border shadow">
                            <tr>
                                <th scope="col">Action</th>
                                <th scope="col">Enroled ID</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Fleet</th>
                                <th scope="col">Status</th>
                            </tr>
                            </thead>
                            <tbody class="border shadow">
                                @foreach ($trainees as $data)
                                <tr class="@if ($data->is_active == 1) bg-light-success @else  bg-light-danger @endif">
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="" type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Edit
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="">
                                                {{-- <button class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#exampleModal-2"><i class="bi bi-plus" disabled style="font-size: 1em;"></i>&nbsp;Enroll
                                                    Crew</button> --}}
                                                <a class="dropdown-item" href="{{ route('te.editprofile', ['traineeid' => $data->traineeid]) }}"><i class="bi bi-person-check"></i>&nbsp;Edit
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ optional($data)->traineeid }}</td>
                                    <td>{{ optional($data)->l_name }}</td>
                                    <td>{{ optional($data)->f_name }}</td>
                                    <td>{{ optional($data)->m_name }}</td>
                                    <td>{{ optional($data)->fleet->fleet }}</td>
                                    <td>
                                        @if ($data->is_active == 1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-5" style="padding-bottom: 6.5em;">
                    {{ $trainees->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
    </div>
</div>