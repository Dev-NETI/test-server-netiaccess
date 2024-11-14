<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Certificate Releasing </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card rounded-3">
                <!-- Card header -->
                <div class="card-header p-0">
                    <div>
                        <!-- Nav -->
                        <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses" role="tab" aria-controls="courses" aria-selected="true">All</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-4 row">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <select name="selected_stat" class="form-select" wire:model="selected_batch">
                                <option value="">Select a Batch Week</option>
                                @foreach ($batchWeeks as $week)
                                <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Table -->
                    <div class="tab-content" id="tabContent">
                        <!--Tab pane -->
                        <div class="tab-pane fade show active" id="courses" role="tabpanel" aria-labelledby="courses-tab">
                            <div class="table-responsive border-0 overflow-y-hidden">
                                <table class="table mb-0 text-nowrap table-centered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                #
                                            </th>
                                            <th scope="col">
                                                TRAINING SCHEDULE
                                            </th>
                                            <th scope="col">
                                                NO. OF TRAINEES
                                            </th>
                                            <th scope="col">
                                                STATUS
                                            </th>
                                            <th scope="col">
                                                ACTION
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($training_schedules->count())
                                        @foreach($training_schedules as $training_schedule)
                                        <tr wire:key="schedule-{{$training_schedule->scheduleid}}">
                                            <td>
                                                <small> #{{$training_schedule->scheduleid}}</small>
                                            </td>
                                            <td>
                                                <a class="text-inherit">
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-3">
                                                            <h4 class="mb-1 text-primary-hover text-uppercase">
                                                                <i class="mdi mdi-file-document"></i> {{$training_schedule->course->coursecode}} <i>- {{$training_schedule->course->coursename}}</i>
                                                            </h4>
                                                            <small class="text-muted">Training schedule: {{date('d F, Y', strtotime($training_schedule->startdateformat))}} - {{date('d F, Y', strtotime($training_schedule->enddateformat))}}</small>
                                                            <br>
                                                            <small class="text-muted">Instructor: @if ($training_schedule->instructor)
                                                                @if ($training_schedule->instructor->userid === 93)
                                                                N/A
                                                                @else
                                                                {{$training_schedule->instructor->user->formal_name()}}
                                                                @endif
                                                                @endif
                                                            </small>
                                                            <br>
                                                            <small class="text-muted">Assessor: @if ($training_schedule->assessor)
                                                                @if ($training_schedule->assessor->userid === 93)
                                                                N/A
                                                                @else
                                                                {{$training_schedule->assessor->user->formal_name()}}
                                                                @endif
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class=" mb-0 text-center" style="text-transform: uppercase;" nonce={{Vite::useCspNonce()}}>{{$training_schedule->enrolled_pending_count}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($training_schedule->pending_releasing_count > 0)
                                                <span class="badge-dot bg-info me-1 d-inline-block align-middle"></span><small>Releasing certificate <span style="color:red;"><i>({{$training_schedule->pending_releasing_count}} remaining trainee)</i></span></small>
                                                @elseif($training_schedule->pending_releasing_count == 0)
                                                <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span><small>Nothing to follows</small>
                                                @else
                                                <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span><small>Not generated</small>
                                                @endif

                                            </td>
                                            <td>
                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#GenerateTrainees" wire:click.prevent="viewTrainees({{$training_schedule->scheduleid}})">View trainees</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                ----NO RECORD FOUND----
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div wire:ignore.self class="modal fade" id="GenerateTrainees" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:key="GenerateTraineesModal">
                                <div class="modal-dialog modal-fullscreen" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="float-end">
                                                        @if($scheduleid)
                                                        <div style="display: inline-block;">

                                                            <div x-data="{ url: '{{ route('a.certificates', ['scheduleid' => $scheduleid]) }}' }">
                                                                <button @click="window.open(url, '_blank')" class="btn btn-danger mt-1 mb-1">
                                                                    <i class="bi bi-filetype-pdf"></i> CERTIFICATE OF COMPLETION
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">

                                                            <div x-data="{ url: '{{ route('a.viewattendance', ['scheduleid' => $scheduleid]) }}' }">
                                                                <button @click="window.open(url, '_blank')" class="btn btn-primary mt-1 mb-1">
                                                                    <i class="bi bi-filetype-pdf"></i> F-NETI-026: ATTENDANCE
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($scheduleid)
                                            <livewire:admin.approval.releasing.a-certificate-releasing-table-component :training_id="$scheduleid" :key="$scheduleid" />
                                            @else
                                            <p>No schedule selected.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" wire:click="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center mb-0">
                                        @if ($training_schedules)
                                        {{ $training_schedules->links('livewire.components.customized-pagination-link')}}
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>