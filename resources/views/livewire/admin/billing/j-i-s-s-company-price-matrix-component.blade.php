<section class="container-fluid">
    <div class="row text-center mt-3">
        <div class="col-12">
            <h1>JISS Price Matrix</h1>
            <p class="text-primary mb-1">Modify the price per courses per company.</p>
        </div>
    </div>
    <hr>    
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="h5">Filter by Company</label>
                                <select name="" class="form-select" wire:model="FilterCompany" id="">
                                    <option value="" selected>All</option>
                                    @foreach ($loadcompany as $data)
                                        <option value="{{ $data->id }}">{{ $data->company }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="" class="h5">Filter by Course</label>
                                <select name="" class="form-select" wire:model="FilterCourse" id="">
                                    <option value="" selected>All</option>
                                        @foreach ($loadcourses as $data)
                                            <option value="{{ $data->id }}">{{ $data->coursename }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-xl-12 col-12 mb-5">
                            <div class="card">
                                <!-- table  -->
                                <div class="table-responsive" style="min-height: 15em;">
                                    <table class="table table-hover text-nowrap mb-0 table-centered">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Courses</th>
                                                <th>Currency</th>
                                                <th>Course Rate</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pricematrix as $data)
                                                <tr class="{{ $data->is_Deleted == 1 ? 'bg-light-danger' : '' }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3 lh-1">
                                                                <h5 class="mb-1"><a href="#" class="text-inherit">{{ $data->company->company }}</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3 lh-1">
                                                                <h5 class="mb-1"><a href="#" class="text-inherit">{{ $data->course->coursename }}</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3 lh-1">
                                                                <h5 class="mb-1">
                                                                    <a href="#" class="text-inherit">
                                                                        @if ($data->PHP_USD == 0)
                                                                            USD
                                                                        @else
                                                                            PHP
                                                                        @endif
                                                                    </a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3 lh-1">
                                                                <h5 class="mb-1"><a href="#" class="text-inherit">{{ $data->courserate }}</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td  style="min-width: 8em;">
                                                        <div class="dropdown">
                                                            <a class="text-body text-primary-hover" href="#" role="button" id="dropdownThirteen" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fe fe-more-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownThirteen">
                                                                <a class="dropdown-item" href="#" wire:click="editData({{$data->id}})">Edit</a>
                                                                @if ($data->is_Deleted == 0)
                                                                    <a class="dropdown-item" href="#" wire:click="deactivateData({{$data->id}})">Deactivate</a>
                                                                @else
                                                                    <a class="dropdown-item" href="#" wire:click="reactivateData({{$data->id}})">Activate</a>
                                                                @endif
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
                    <div class="cardd-footer">
                        {{ $pricematrix->links('livewire.components.customized-pagination-link') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" wire:click='AddPriceMatrix()' id="my-button" class="btn btn-primary position-fixed shadow" style="right: 60px;
                bottom: 60px;
                border-radius: 50%;
                height: 80px;
                width: 80px;
                display: flex;
                align-items: center;
                justify-content: center;">
                <i class="bi bi-plus-circle-fill" style="font-size: 3rem; margin: auto;"></i>
    </button>

    <div wire:ignore.self class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">{{ $isUpdate ? 'Update' : 'Add' }} Price Matrix</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" wire:model="RateType" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        @if ($RateType == 0)
                            <label class="form-check-label" for="flexSwitchCheckDefault">USD</label>
                        @else
                            <label class="form-check-label" for="flexSwitchCheckDefault">PHP</label>
                        @endif
                    </div>
                </div>
                <div class="container mt-2">
                    <label for="company" class="h5">Select Company</label>
                    <select name="" class="form-select" wire:model="SelectedCompany" id="company">
                        <option value="" selected>---</option>
                        @foreach ($loadcompany as $data)
                            <option value="{{ $data->id }}">{{ $data->company }}</option>
                        @endforeach
                    </select>
                    @error($SelectedCompany)
                        <span class="text-danger"><small>Please select company</small></span>
                    @enderror
                </div>

                <div class="container mt-2">
                    <label for="course" class="h5">Select Course</label>
                    <select class="form-select" wire:model="SelectedCourse" id="course">
                        <option value="" selected>---</option>
                        @foreach ($loadcourses as $data)
                            <option value="{{ $data->id }}">{{ $data->coursename }}</option>
                        @endforeach
                    </select>
                    @error($SelectedCourse)
                        <span class="text-danger"><small>Please select course</small></span>
                    @enderror
                </div>

                <div class="container mt-2">
                    <label for="" class="h5">Course Rate</label>
                    <input type="number" wire:model="CourseRate" class="form-control" placeholder="Please enter rate for this course">
                    @error($CourseRate)
                        <span class="text-danger"><small>Rate is empty</small></span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              @if ($isUpdate == 0)
                <button type="button" wire:click="ExecuteAddPriceMatrix()" class="btn btn-primary">Add</button>  
              @else
                <button type="button" wire:click="executeUpdatePM({{$updateID}})" class="btn btn-info">Update</button>  
              @endif
            </div>
          </div>
        </div>
      </div>
</section>