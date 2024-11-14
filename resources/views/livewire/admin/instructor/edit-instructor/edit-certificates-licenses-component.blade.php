
<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Other Certificates & Licenses</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Instructor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.instructor') }}">Instructor List </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('a.edit-instructor', ['hashid' => $user->hash_id]) }}">Edit
                                    Instructor</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Certificates & Licenses
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- card -->
        <div class="card">
            <div class="col lg-12">
                <div class="card-body">
                    <!-- javascript behaviour -->
                    <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                        <a class="nav-link active" id="coc-tab" data-bs-toggle="tab" href="#coc" role="tab" aria-controls="coc" aria-selected="true">COC</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="cop-tab" data-bs-toggle="tab" href="#cop" role="tab" aria-controls="cop" aria-selected="false">COP</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="imolicense-tab" data-bs-toggle="tab" href="#imolicense" role="tab" aria-controls="imolicense" aria-selected="false">IMO Licenses</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="sirbentries-tab" data-bs-toggle="tab" href="#sirbentries" role="tab" aria-controls="sirbentries" aria-selected="false">SIRB Entries</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="seaservicecard-tab" data-bs-toggle="tab" href="#seaservicecard" role="tab" aria-controls="seaservicecard" aria-selected="false">Sea Service Card</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="trainingcerti-tab" data-bs-toggle="tab" href="#trainingcerti" role="tab" aria-controls="trainingcerti" aria-selected="false">Training Certificates</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div wire:ignore.self  class="tab-pane fade show active" id="coc" role="tabpanel" aria-labelledby="coc-tab">
                            <div class="mt-2">
                                <h3>Certificate of Completion  <button wire:click="addcertitype(1)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="mt-2">
                                <!-- table  -->
                                <div class="table-responsive pb-15">
                                    <table class="table table-bordered text-nowrap mb-0 table-centered">
                                        <thead>
                                            <tr>
                                            <th>Filename</th>
                                            <th>Expiration Date</th>
                                            <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($attachmentscoc->count() == 0)
                                                <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                            @else
                                                @foreach ($attachmentscoc as $attachmentcoc)
                                                    <tr>
                                                        <td>{{$attachmentcoc->filename}}</td>
                                                        <td>{{$attachmentcoc->expirationdate}}</td>
                                                        <td>
                                                        <div class="dropdown ">
                                                            <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fe fe-more-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                            @if ($attachmentcoc->filepath != NULL)
                                                                <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentcoc->filepath}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                            @else
                                                                <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentcoc->filename}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                            @endif
                                                            <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmentcoc->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                            <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmentcoc->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div wire:ignore.self  class="tab-pane fade" id="cop" role="tabpanel" aria-labelledby="cop-tab">
                            <div class="mt-2">
                                <h3>Certificate of Proficiency <button wire:click="addcertitype(2)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="table-responsive pb-15">
                                <table class="table table-bordered text-nowrap mb-0 table-centered">
                                  <thead>
                                    <tr>
                                      <th>Filename</th>
                                      <th>Expiration Date</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if ($attachmentscop->count() == 0)
                                        <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                    @else
                                        @foreach ($attachmentscop as $attachmentcop)
                                            <tr>
                                                <td>{{$attachmentcop->filename}}</td>
                                                <td>{{$attachmentcop->expirationdate}}</td>
                                                <td>
                                                <div class="dropdown ">
                                                    <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                    @if ($attachmentcop->filepath != NULL)
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentcop->filepath}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @else
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentcop->filename}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmentcop->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                    <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmentcop->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  </tbody>
                                </table>
                              </div>
                        </div>
                        <div wire:ignore.self  class="tab-pane fade" id="imolicense" role="tabpanel" aria-labelledby="imolicense-tab">
                            <div class="mt-2">
                                <h3>IMO Licenses <button  wire:click="addcertitype(3)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="table-responsive pb-15">
                                <table class="table table-bordered text-nowrap mb-0 table-centered">
                                  <thead>
                                    <tr>
                                      <th>Filename</th>
                                      <th>Expiration Date</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if ($attachmentsimo->count() == 0)
                                        <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                    @else
                                        @foreach ($attachmentsimo as $attachmentimo)
                                            <tr>
                                                <td>{{$attachmentimo->filename}}</td>
                                                <td>{{$attachmentimo->expirationdate}}</td>
                                                <td>
                                                <div class="dropdown ">
                                                    <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                    @if ($attachmentimo->filepath != NULL)
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentimo->filepath}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @else
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentimo->filename}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmentimo->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                    <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmentimo->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  </tbody>
                                </table>
                            </div>
                        </div>
                        <div wire:ignore.self  class="tab-pane fade" id="sirbentries" role="tabpanel" aria-labelledby="sirbentries-tab">
                            <div class="mt-2">
                                <h3>SRIB Entries <button wire:click="addcertitype(4)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="table-responsive pb-15">
                                <table class="table table-bordered text-nowrap mb-0 table-centered">
                                  <thead>
                                    <tr>
                                      <th>Filename</th>
                                      <th>Expiration Date</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if ($attachmentssrib->count() == 0)
                                        <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                    @else
                                        @foreach ($attachmentssrib as $attachmentsrib)
                                            <tr>
                                                <td>{{$attachmentsrib->filename}}</td>
                                                <td>{{$attachmentsrib->expirationdate}}</td>
                                                <td>
                                                <div class="dropdown ">
                                                    <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                    @if ($attachmentsrib->filepath != NULL)
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentsrib->filepath}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @else
                                                        <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentsrib->filename}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmentsrib->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                    <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmentsrib->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  </tbody>
                                </table>
                              </div>
                        </div>
                        <div wire:ignore.self  class="tab-pane fade" id="seaservicecard" role="tabpanel" aria-labelledby="seaservicecard-tab">
                            <div class="mt-2">
                                <h3>Sea Service Card <button  wire:click="addcertitype(5)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="mt-2">
                                <!-- table  -->
                                <div class="table-responsive pb-15">
                                    <table class="table table-bordered text-nowrap mb-0 table-centered">
                                    <thead>
                                        <tr>
                                        <th>Filename</th>
                                        <th>Expiration Date</th>
                                        <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attachmentsssc->count() == 0)
                                            <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                        @else
                                            @foreach ($attachmentsssc as $attachmentssc)
                                                <tr>
                                                    <td>{{$attachmentssc->filename}}</td>
                                                    <td>{{$attachmentssc->expirationdate}}</td>
                                                    <td>
                                                    <div class="dropdown ">
                                                        <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                        @if ($attachmentssc->filepath != NULL)
                                                            <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentssc->filepath}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                        @else
                                                            <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmentssc->filename}}" ><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                        @endif
                                                        <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmentssc->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                        <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmentssc->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                        </div>
                                                    </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div wire:ignore.self  class="tab-pane fade" id="trainingcerti" role="tabpanel" aria-labelledby="trainingcerti-tab">
                            <div class="mt-2">
                                <h3>Training Certificates <button  wire:click="addcertitype(6)" class="btn btn-sm btn-info" style="margin-left:10px;"><i class="bi bi-plus-lg"></i> Add</button></h3>
                            </div>
                            <div class="mt-2">
                                <!-- table  -->
                                <div class="table-responsive pb-15">
                                    <table class="table table-bordered text-nowrap mb-0 table-centered">
                                    <thead>
                                        <tr>
                                        <th>Filename</th>
                                        <th>Expiration Date</th>
                                        <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attachmentstc->count() == 0)
                                            <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                        @else
                                            @foreach ($attachmentstc as $attachmenttc)
                                                <tr>
                                                    <td>{{$attachmenttc->filename}}</td>
                                                    <td>{{$attachmenttc->expirationdate}}</td>
                                                    <td>
                                                    <div class="dropdown ">
                                                        <a class="btn btn-info text-primary-hover" href="#" role="button" id="dropdownSeventeen"
                                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownSeventeen">
                                                        @if ($attachmenttc->filepath != NULL)
                                                            <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmenttc->filepath}}" target="_blank"><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                        @else
                                                            <a class="dropdown-item" target="_blank" href="/storage/uploads/instructorattachment/{{$attachmenttc->filename}}" target="_blank"><i class="bi bi-eye-fill"></i><span style="padding-left: .2em;">View</span></a>
                                                        @endif
                                                        <a class="dropdown-item" href="#" wire:click.prevent="updatecerti({{ $attachmenttc->id  }})"><i class="bi bi-pencil-square"></i><span style="padding-left: .2em;">Update</span></a>
                                                        <button class="dropdown-item" wire:click.prevent="confirmdelete({{ $attachmenttc->id }})"><i class="bi bi-trash-fill"></i><span style="padding-left: .2em;">Delete</span></button>
                                                        </div>
                                                    </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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

    <div wire:ignore.self class="modal fade" id="modaladdcertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Upload Certificate - {{$certiname}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formcertiadd" wire:submit.prevent="formcertiadd" enctype="multipart/form-data">
                @csrf
                <label for="" class="form-label">Choose File <span class="text-danger fs-6"><i>(only accepts pdf)</i></span></label>
                <input type="file" accept=".pdf" wire:model.defer="certifile" class="form-control" required>
                <input type="text" hidden wire:model.defer="certitype" class="form-control">
                <label for="" class="form-label mt-2">Expiration Date</label>
                <input type="text" wire:model.defer="certiexpdate" class="form-control flatpickr" placeholder="Please select expiration date">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              <button type="submit" form="formcertiadd" class="btn btn-primary">Upload</button>
            </div>
          </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalupdatecertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Update Certificate</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formcertiupdate" wire:submit.prevent="formcertiupdate">
                @csrf
                <input type="text" hidden wire:model.defer="upcertiid" class="form-control">
                <label for="" class="form-label mt-2">Expiration Date</label>
                <input type="text" wire:model.defer="updcertiexpdate" class="form-control flatpickr" required placeholder="Please select expiration date">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              <button type="submit" form="formcertiupdate" class="btn btn-primary">Upload</button>
            </div>
          </div>
        </div>
    </div>
</section>

