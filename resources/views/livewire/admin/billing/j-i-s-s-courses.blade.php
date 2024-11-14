<section class="container-fluid">
    <div class="row text-center mt-3">
        <div class="col-12">
            <h1>JISS Courses Management</h1>
            <p class="text-primary mb-1">Modify the information about the courses.</p>
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
                            <input type="search" wire:model="Search" class="form-control ps-6"
                                placeholder="Search Courses">
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
                                                <th>Courses</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($courses as $data)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-3 lh-1">
                                                            <h5 class="mb-1"><a href="#" class="text-inherit">{{
                                                                    $data->coursename }}</a></h5>
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
                                                            <a class="dropdown-item"
                                                                wire:click="updateCourse({{$data->id}})"
                                                                href="#">Edit</a>
                                                            <a class="dropdown-item"
                                                                href="{{route('a.jiss-edittemplate', [$data->id])}}">Edit
                                                                Template</a>
                                                            <a class="dropdown-item" href="#"
                                                                wire:click="deleteInfo({{$data->id}})">Deactivate</a>
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
                </div>
            </div>
        </div>
    </div>

    <button type="button" wire:click='addcourse()' id="my-button" class="btn btn-primary position-fixed shadow" style="right: 60px;
                bottom: 60px;
                border-radius: 50%;
                height: 80px;
                width: 80px;
                display: flex;
                align-items: center;
                justify-content: center;">
        <i class="bi bi-plus-circle-fill" style="font-size: 3rem;"></i>
    </button>

    <div wire:ignore.self class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ $updateID == null ? 'Add' : 'Edit' }} Course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                @if($updateID == null)
                <form action="" wire:submit.prevent='ExecuteAddCourse' enctype="multipart/form-data">
                    @else
                    <form action="" wire:submit.prevent='ExecuteUpdateCourse' enctype="multipart/form-data">
                        @endif
                        <div class="modal-body">
                            <div class="container mt-2">
                                <label for="coursename h5">Course Name</label>
                                <input type="text" id="coursename" class="form-control" wire:model="CourseName">
                            </div>
                            <div class="container mt-2">
                                <label for="template h5">Billing Template <span wire:loading wire:target='file'
                                        class="text-warning"><small>Uploading in progress</small></span></label>
                                <input type="file" id="file" class="form-control" wire:model="file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" wire:loading.class='disabled' wire:target='file'
                                class="btn btn-primary">{{$updateID == null ? 'Add' : 'Update'}}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</section>