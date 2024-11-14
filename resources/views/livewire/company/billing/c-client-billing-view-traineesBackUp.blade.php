<div>
    <span wire:loading>
      <livewire:components.loading-screen-component />
    </span>
    <div class="py-lg-14 bg-light pt-8 pb-10">
        <!-- container -->

        <div class="container">
          <div class="row">
            <!-- col -->

            <div class="col-md-12 col-12">

              <div class="row text-center">
                <div class="col-md-12 px-lg-10 mb-8 mt-6">
                  <!-- text -->

                  <span class="text-uppercase text-primary fw-semibold ls-md">Payment Monitoring
                  </span>
                  <!-- heading -->

                  <h2 class="h1 fw-bold mt-3"> {{ $billingstatus_data->billingstatus }}
                  </h2>
                  <!-- text -->

                  <p class="mb-0 fs-4"> {{ $company_name }} / {{ $training_date }} </p>

                </div>
              </div>

              <div class="row">

                    <div class="col-md-6">

                      <table wire:ignore.self class="table table-hover">
                                  <thead>
                                          <tr>
                                                  <th>Name</th>
                                                  <th>Rank</th>
                                          </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($trainees as $trainee)
                                          <tr>
                                                  <th>{{ $trainee->l_name  }}, {{ $trainee->f_name  }} {{ $trainee->m_name  }} {{ $trainee->suffix  }}</th>
                                                  <th>{{ $trainee->rankacronym }}</th>
                                          </tr>
                                    @endforeach
                                  </tbody>
                      </table>


                    </div>



                    <div class="col-md-6">

                     
                        <div class="card" id="courseAccordion">
                            <!-- List group -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item p-0 bg-transparent">
                                    <!-- Toggle -->
                                    <a class="d-flex align-items-center text-inherit text-decoration-none py-3 px-4" data-bs-toggle="collapse" href="#courseTwo" role="button" aria-expanded="false" aria-controls="courseTwo">
                                        <div class="me-auto">
                                          <h4 class="mb-0">Billing Functions</h4>
                                          <p class="mb-0 text-muted">Here you can process billing.</p>
                                        </div>
                                    <!-- Chevron -->
                                    <span class="chevron-arrow ms-4">
                                        <i class="fe fe-chevron-down fs-4"></i>
                                    </span>
                                    </a>
                                    <!-- / .row -->
                                    <!-- Collapse -->
                                    <div class="collapse show" id="courseTwo" data-bs-parent="#courseAccordion">
                                    <!-- List group -->
                                    <ul class="list-group list-group-flush">
                                      <!-- List group -->
                                      <li class="list-group-item">
                                        <a wire:click="viewBillingStatement()" class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                            <div class="text-truncate">
                                              <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i class="bi bi-receipt-cutoff"></i></span>
                                              <span>View Billing Statement</span>
                                            </div>
                                        </a>
                                      </li>
                                      <li class="list-group-item">
                                          <a data-bs-target="#ViewAttachmentModal" data-bs-toggle='modal' class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                              <div class="text-truncate">
                                                <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i class="bi bi-binoculars"></i></span>
                                                <span>View Attachment</span>
                                              </div>
                                          </a>
                                      </li>
                                      @if($billingstatusid === 2)
                                        <li class="list-group-item">
                                                <a wire:click="confirmBillingStatement()" class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                    <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i class="bi bi-check-all"></i></span>
                                                    <span>Confirm Receipt of Billing Statement </span>
                                                    </div>
                                                </a>
                                        </li>
                                      @elseif($billingstatusid === 3)
                                        <li class="list-group-item">
                                            <a data-bs-target="#uploadAttachmentModal" data-bs-toggle='modal' class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                <div class="text-truncate">
                                                  <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i class="bi bi-file-arrow-up"></i></span>
                                                  <span>Upload Payment Slip</span>
                                                </div>
                                            </a>
                                        </li>
                                      @elseif($billingstatusid === 5)
                                        <li class="list-group-item">
                                                <a wire:click="confirmOfficialReceipt()" class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                    <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i class="bi bi-check-all"></i></span>
                                                    <span>Confirm receipt of the official receipt</span>
                                                    </div>
                                                </a>
                                        </li>
                                      @endif
                                    </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>


                    </div>
                    

              </div>

            </div>
          </div>

        </div>
      </div>


      {{-- upload attachment modal --}}
      {{-- upload attachment modal --}}
        <div wire:ignore class="modal fade" id="uploadAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <form wire:submit.prevent="uploadPaymentSlip">
                    @csrf
                          <div class="form-group form-row">
                                    <div class="col-md-12 mt-3">
                                              <label>Title</label>
                                              <input type="text" class="form-control" wire:model.defer="title" required>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                              <label>Choose file</label>
                                              <input type="file" class="form-control" wire:model.defer="file" required>
                                    </div>
                                    <div class="col-md-12 mt-3 ">
                                              <button type="submit" class="btn btn-primary">Save changes</button>
                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                              
                                    </div>
                          </div>
                  </form>
              </div>
              
            </div>
          </div>
        </div>
      {{-- upload attachment modal end--}}
      {{-- upload attachment modal end--}}

      {{-- view attachment modal --}}
      {{-- view attachment modal --}}
      <div wire:ignore class="modal fade" id="ViewAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">View Attachment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                      <table class="table table-bordered table-hover">
                              <thead>
                                        <tr>
                                                <th>Title</th>
                                                <th>Attachment Type</th>
                                                <th>Action</th>
                                        </tr>
                              </thead>
                              <tbody>
                                    @foreach($attachments as $attachment)
                                        <tr>
                                                <td>{{ $attachment->title }}</td>
                                                <td>{{ $attachment->attachmenttype->attachmenttype }}</td>
                                                <td>

                                                  <a href="/storage/{{ $attachment->filepath }}" class="btn btn-primary"><i class="bi bi-cloud-download-fill"></i></a>

                                                </td>
                                        </tr>
                                    @endforeach
                              </tbody>
                      </table>
            </div>
            <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    {{-- view attachment modal end--}}
    {{-- view attachment modal end--}}

</div>
