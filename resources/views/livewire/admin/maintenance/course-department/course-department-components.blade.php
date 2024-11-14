<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> Course Department Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.maintenance') }}">Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Course Department Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    {{-- <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add New
                        Course Department</a> --}}
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
                    <!-- Form -->
                    <form class="d-flex align-items-center">
                        @csrf
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                            @csrf
                            <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                            <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                                placeholder="Search Course Department">
                        </form>
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>NO.</th>
                                <th>ACTION</th>
                                <th>COURSE DEPARTMENT</th>
                                <th>COURSE DEPARTMENT CODE</th>
                                <th>DEPARTMENT HEAD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coursedepartmentQuery as $coursedepartments)
                            <tr>
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                        
                                <td>
                                    <a href="#" class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        wire:click="coursedepartmentedit({{ $coursedepartments->coursedepartmentid }})">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Edit
                                    </a>
                                </td>
                        
                                <td>
                                    {{ $coursedepartments->coursedepartment }}
                                </td>
                        
                                <td>
                                    {{ $coursedepartments->coursedepartmentsuffix }}
                                </td>
                        
                                <td>
                                    {{ $coursedepartments->departmenthead }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Course Department Modal -->
    <div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Edit Department Head
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="coursedepartmentupdate">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Department name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="coursedepartmentname" required>
                        </div>

                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Department Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="coursedepartmentsuffix" required>
                        </div>

                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Department Head<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="departmenthead" required>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Update Course Department</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Course Department Modal -->


   



</section>