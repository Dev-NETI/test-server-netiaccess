<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Handouts
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">
                                Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- row -->
    <div class="row bg-white">

        @if (Auth::user()->u_type == 1)
        <div class="col-md-6 offset-md-6 mt-2">
            <button class="btn btn-primary float-end" wire:click="savePassword()">
                Generate Password
            </button>
        </div>
        @endif

        <div class="col-md-12 table-responsive">

            <table class="table table-hover table-bordered mt-3" width="100%">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($courses as $course)
                    <tr>
                        <td>{{$course->coursecode}} / {{$course->coursename}}</td>
                        <td>{{$course->handout_password}}</td>
                        <td>

                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#handoutpasswordmodal"
                                wire:click="getHandoutPassword({{$course->courseid}})">
                                <i class="bi bi-eye-fill"></i> View
                            </button>

                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>


    </div>






    <!--Enter password modal-->
    <div wire:ignore.self class="modal fade" id="handoutpasswordmodal" tabindex="-1" role="dialog"
        aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Enter handout password
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="verifyHandoutPassword">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Enter handout password</label>
                            <input type="text" class="form-control" wire:model.defer="handout_password" required>
                        </div>


                        <div>
                            <button type="submit" class="btn btn-primary">Next</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




</section>