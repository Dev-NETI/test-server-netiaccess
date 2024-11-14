<section class="container-fluid p-4">
  <div class="row">
    <span wire:loading>
      <livewire:components.loading-screen-component />
    </span>
    <!-- Page Header -->
    <div class="col-lg-12 col-md-12 col-12">
      <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
        <div class="mb-3 mb-md-0">
          <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> All Courses</h1>
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ route('a.maintenance') }}">Maintenance</a>
              </li>

              <li class="breadcrumb-item active" aria-current="page">
                Courses List
              </li>
            </ol>
          </nav>
        </div>
        <div>
          {{-- <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add New Courses</a>
          --}}
          <a href="#" class="btn btn-primary me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Add New
            Courses</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-12">
      <!-- Card -->
      <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header p-0">
          <div>
            <!-- Nav -->
            <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses" role="tab" aria-controls="courses" aria-selected="true">All ({{ $count_allcourses }})</a>
              </li>


            </ul>
          </div>

          <div class="p-4 row">
            <!-- Form -->
            <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
              @csrf
              <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
              <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search" placeholder="Search Course or Course Code">
            </form>
          </div>

        </div>
        <!-- Table -->

        <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
          <table class="table mb-0 text-nowrap table-centered table-hover text-sm">
            <thead class="table-light">
              <tr>
                <th>Action</th>
                <th>Course</th>
                <th>Course department</th>
                <th>Course level</th>
                <th>Course Type</th>
                {{-- <th>Vessel type</th> --}}
                <th>Training days</th>
                <th>Min trainees</th>
                <th>Max trainees</th>
                <th>Mode of delivery</th>

              </tr>
            </thead>
            <tbody>
              @foreach ($retrievecourses as $courses )
              <tr @if($courses->deletedid == 1)class="bg-light-danger"@endif>
                <td>
                  <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="courseedit({{ $courses->courseid }})">
                    <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Edit
                  </button>

                  @if ($courses->deletedid == 1)
                  <button class="btn btn-success" wire:click="activate({{ $courses->courseid }})">
                    <i class="bi bi-arrow-return-left"></i> Activate
                  </button>
                  @else
                  <button class="btn btn-danger" wire:click="delete({{ $courses->courseid }})">
                    <i class="bi bi-file-excel-fill"></i> Deactivate
                  </button>
                  @endif


                </td>
                <td>
                  <a href="#" class="text-inherit">
                    <div class="d-flex">

                      <div class="ms-3">
                        <h4 class="mb-1 text-primary-hover">
                          <span class="badge bg-success" id="{{$courses->courseid}}">{{$courses->coursecode}}</span>
                        </h4>
                        <textarea disabled="" style="height: 55px;">{{$courses->coursename}}</textarea>
                        {{-- <span class="text"></span> --}}
                      </div>
                    </div>
                  </a>
                </td>
                <td> <span class="text">{{optional($courses->coursedepartment)->coursedepartment}}</span></td>
                <td> <span class="text">{{optional($courses->rank_level)->ranklevel}}</span></td>
                <td> <span class="text">{{optional($courses->type)->coursetype}}</span></td>
                <td> <span class="text">{{$courses->trainingdays}}</span></td>
                <td> <span class="text">{{$courses->minimumtrainees}}</span></td>
                <td> <span class="text">{{$courses->maximumtrainees}}</span></td>
                <td> <span class="text">{{optional($courses->mode)->modeofdelivery}}</span></td>
              </tr>
              @endforeach
            </tbody>

          </table>
          <div class="card-footer">
            <div class="row">
              {{ $retrievecourses->appends(['search' =>
              $search])->links('livewire.components.customized-pagination-link')}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Offcanvas -->

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" style="width: 600px;">
    <div class="offcanvas-body" data-simplebar>
      <div class="offcanvas-header px-2 pt-0">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel">Add New Course</h3>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <!-- card body -->
      <div class="container">
        <form id="addnewcourse" wire:submit.prevent="addnewcourse">
          @csrf
          <div class="row">
            <div class="mb-3 col-6">
              <label class="form-label">Course Code <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addcoursecode" class="form-control" placeholder="Enter course code" required>
            </div>

            <div class="mb-3 col-12">
              <label class="form-label">Course Name <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addcoursename" class="form-control" placeholder="Enter course name" required>
            </div>

            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Department</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selecteddepartment">
                <option value="">Select Course Department</option>
                @foreach ($retrievecoursedepartment as $coursedepartment)
                <option value="{{ $coursedepartment->coursedepartmentid }}">{{ $coursedepartment->coursedepartment }}
                </option>
                @endforeach
              </select>
            </div>


            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Type</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedcoursetype">
                <option value="">Select Course Type</option>
                @foreach ($retrievecoursetype as $coursetype )
                <option value="{{ $coursetype->coursetypeid }}">{{ $coursetype->coursetype }}</option>
                @endforeach
                <option value="10">OTHERS</option>
                <option value="11">NON-TEACHING</option>
              </select>

            </div>


            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Level</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedcourselevel">
                <option value="">Select Course Level</option>
                @foreach ($retrievecourselevel as $courselevel )
                <option value="{{ $courselevel->ranklevelid }}">{{ $courselevel->ranklevel }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Vessel Type</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedvesseltype">
                <option value="">Select Vessel Type</option>
                @foreach ($retrievevesseltype as $vesseltype )

                <option value="{{ $vesseltype->vesseltypeid }}">{{ $vesseltype->vesseltype }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Training Days <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addtrainingdays" class="form-control" placeholder="Enter No of Training Days" required>
            </div>

            <div class="mb-3 col-6">

            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Minimum No. of Trainees <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addmintrainees" class="form-control" placeholder="Min No. of Trainees" required>
            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Maximum No. of Trainees <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addmaxtrainees" class="form-control" placeholder="Max No. of Trainees" required>
            </div>


            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Location</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedcourselocation">
                <option value="">Select Location</option>
                @foreach ($retrievecourselocation as $location)
                <option value="{{ $location->courselocationid }}">{{ $location->courselocation }}</option>
                @endforeach
              </select>
            </div>


            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Mode of Delivery</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedmod">
                <option value="">Select Mode of Delivery</option>
                @foreach ($retrievemodeofdelivery as $modeofdelivery)
                <option value="{{ $modeofdelivery->id }}">{{ $modeofdelivery->modeofdelivery }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-4">
              <label class="form-label">ATD/SLAF PACKAGE 1 </label>
              <input type="text" wire:model.defer="editatdpackage1" class="form-control" placeholder="Package 1" required>
            </div>

            <div class="mb-3 col-4">
              <label class="form-label">ATD/SLAF PACKAGE 2 </label>
              <input type="text" wire:model.defer="editatdpackage2" class="form-control" placeholder="Package 2" required>
            </div>
            <div class="mb-3 col-4">
              <label class="form-label">ATD/SLAF PACKAGE 3 </label>
              <input type="text" wire:model.defer="editatdpackage3" class="form-control" placeholder="Package 3" required>
            </div>

            <div class="col-12 md-12">
              <div x-data="{ isUploading : false , progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="mb-3 col-6">
                  <label>Handout</label>
                  <input type="file" class="form-control" wire:model.defer="file">
                  @error('file') <small class="text-danger">{{$message}}</small> @enderror
                  <div x-show="isUploading" class="progress mt-3">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" x-bind:style="`width:${progress}%`">
                      <span class="sr-only"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 mb-3">
              <label class="form-label">ZOOM LINK: </label>
              <textarea type="text" wire:model.defer="zoom_desc" class="form-control" placeholder="Insert here the credentials"></textarea>
            </div>

            <div class="col-12">
              <button class="btn btn-primary" type="submit">Submit</button>
              <button type="button" class="btn btn-outline-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Edit Course Modal -->
  <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title mb-0" id="newCatgoryLabel">
            Edit Course
          </h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form id="updatecourse" wire:submit.prevent="updatecourse">
            @csrf
            <div class="row">
              <div class="mb-3 col-6">
                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editcoursecode" class="form-control" placeholder="Enter course code" required>
              </div>

              <div class="mb-3 col-12">
                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editcoursename" class="form-control" placeholder="Enter course name" required>
              </div>

              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Course Department</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselecteddepartment">
                  <option value="">Select Course Department</option>
                  @foreach ($retrievecoursedepartment as $coursedepartment)
                  <option value="{{ $coursedepartment->coursedepartmentid }}">{{ $coursedepartment->coursedepartment }}
                  </option>
                  @endforeach
                </select>
              </div>


              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Course Type</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedcoursetype">
                  <option value="">Select Course Type</option>
                  @foreach ($retrievecoursetype as $coursetype )
                  <option value="{{ $coursetype->coursetypeid }}">{{ $coursetype->coursetype }}</option>
                  @endforeach
                  <option value="10">OTHERS</option>
                  <option value="11">NON-TEACHING</option>
                </select>

              </div>


              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Course Level</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedcourselevel">
                  <option value="">Select Course Level</option>
                  @foreach ($retrievecourselevel as $courselevel )
                  <option value="{{ $courselevel->ranklevelid }}">{{ $courselevel->ranklevel }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Vessel Type</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedvesseltype">
                  <option value="">Select Vessel Type</option>
                  @foreach ($retrievevesseltype as $vesseltype )

                  <option value="{{ $vesseltype->vesseltypeid }}">{{ $vesseltype->vesseltype }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Instructor License Type</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedinstructortype">
                  <option value="">Select Instructor License Type</option>
                  @foreach ($retrievecourseinstructortype as $instructorlicensetype )

                  <option value="{{ $instructorlicensetype->instructorlicensetypeid }}">{{
                    $instructorlicensetype->licensetype }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Assessor License Type</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedassessortype">
                  <option value="">Select Assessor License Type</option>
                  @foreach ($retrievecourseassessortype as $assessorlicensetype )

                  <option value="{{ $assessorlicensetype->instructorlicensetypeid }}">{{
                    $assessorlicensetype->licensetype }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3 col-6">
                <label class="form-label">Training Days <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="edittrainingdays" class="form-control" placeholder="Enter No of Training Days" required>
              </div>

              <div class="mb-3 col-6">

              </div>

              <div class="mb-3 col-6">
                <label class="form-label">Minimum No. of Trainees <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editmintrainees" class="form-control" placeholder="Min No. of Trainees" required>
              </div>

              <div class="mb-3 col-6">
                <label class="form-label">Maximum No. of Trainees <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editmaxtrainees" class="form-control" placeholder="Max No. of Trainees" required>
              </div>


              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Course Location</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedcourselocation">
                  <option value="">Select Location</option>
                  @foreach ($retrievecourselocation as $location)
                  <option value="{{ $location->courselocationid }}">{{ $location->courselocation }}</option>
                  @endforeach
                </select>
              </div>


              <div class="mb-3 col-md-6 col-12">
                <label class="form-label">Mode of Delivery</label>
                <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedmod">
                  <option value="">Select Mode of Delivery</option>
                  @foreach ($retrievemodeofdelivery as $modeofdelivery)
                  <option value="{{ $modeofdelivery->id }}">{{ $modeofdelivery->modeofdelivery }}</option>
                  @endforeach
                </select>
              </div>



              <div x-data="{ isUploading : false , progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="mb-3 col-6">
                  <label>Handout</label>
                  <input type="file" class="form-control" wire:model.defer="file">
                  @error('file') <small class="text-danger">{{$message}}</small> @enderror
                  <div x-show="isUploading" class="progress mt-3">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" x-bind:style="`width:${progress}%`">
                      <span class="sr-only"></span>
                    </div>
                  </div>

                </div>
              </div>


              @if(Auth::user()->dep_type == 8 || Auth::user()->dep_type == 1)
              <div class="col-md-8"></div>
              <div class="mb-3 col-6">
                <label class="form-label">ATD/SLAF PACKAGE 1 <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editatdpackage1" class="form-control" placeholder="Package 1" required>
              </div>

              <div class="mb-3 col-6">
                <label class="form-label">ATD/SLAF PACKAGE 2 <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editatdpackage2" class="form-control" placeholder="Package 2" required>
              </div>
              <div class="mb-3 col-6">
                <label class="form-label">ATD/SLAF PACKAGE 3 <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="editatdpackage3" class="form-control" placeholder="Package 3" required>
              </div>
              @endcan

              <div class="col-12 mb-3">
                <label class="form-label">ZOOM LINK: </label>
                <textarea type="text" wire:model.defer="zoom_desc" class="form-control" placeholder="Insert here the credentials"></textarea>
              </div>

              <div class="col-12">
                <button class="btn btn-primary" type="submit">Submit</button>
                <button type="button" class="btn btn-white ms-2" data-bs-dismiss="modal">Close</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit Course Modal -->

</section>