<main>
    <section class="pb-5 py-5">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    @include('layouts.partials._DataPrivacy')
        <div class="container">
            <div class="col-md-12 col-12">
                @if ($announcement)
                <div class="col-lg-12 col-12 col-md-12">
                    <div class="alert alert-warning alert-dismissible align-items-center d-flex fade show" role="alert">
                        <div>
                            <strong>ANNOUNCEMENT:</strong> <br>
                            {{strip_tags($announcement->announcement)}}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
                @endif
                <h3>My Enrolled Courses</h2>
                    <div>
                        @if ($enrolled_courses->isNotEmpty())
                        @foreach ($enrolled_courses as $course)
                        <div class="card shadow-lg mb-2">
                            <div class="row align-items-center g-0 m-4">
                                <div class="col">
                                    <h4><b>#{{ $course->enroledid }}</b> <i>- {{ optional($course->course)->coursename
                                            }}</i></h4>
                                    @if($course->pendingid == 1)
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#archiveModal" wire:click="openModal({{ $course->enroledid }})">
                                        <i class="bi bi-archive"></i> Delete my request
                                    </button>
                                    @elseif($course->pendingid == 0 || $course->passid == 2  || $course->passid == 1)
                                    <button class="btn btn-danger btn-sm"
                                        wire:click.prevent="confirmdrop({{ $course->enroledid }})"
                                        data-bs-toggle="modal" data-bs-target="#generateModalDrop"><i class="bi bi-x"></i>DROP</i>
                                    </button>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <h6>
                                        <span
                                            class="badge rounded-pill {{ $course->pendingid == 1 ? 'bg-warning' : ($course->pendingid == 2 ? 'bg-danger' : 'bg-success') }}">
                                            {{ match($course->pendingid) {
                                            1 => 'PENDING',
                                            2 => 'DROPPED',
                                            3 => 'REMEDIAL',
                                            0 => 'ENROLLED'
                                            } }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-stretch">
                                    @if ($course->pendingid == 0)
                                    <div class="col-6 col-md-3 mb-2 d-grid ">
                                        <a class="btn btn-success btn-block btn-col"
                                            href="{{ route('t.coursedetails', ['regis' => $course->enroledid]) }}">VIEW
                                            DETAILS</a>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2 d-grid ">
                                        <a class="btn btn-info btn-block btn-col"
                                            href="{{ route('t.viewadmission', ['enrol_id' => $course->enroledid]) }}"
                                            target="_blank">ADMISSION SLIP</a>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2 d-grid ">
                                        <a wire:click.prevent="goToLMS({{ $course->schedule->scheduleid }})"
                                            class="btn btn-warning btn-block btn-col">LMS</a>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2 d-grid ">
                                        <button class="btn btn-primary btn-block btn-col" data-bs-toggle="modal"
                                            data-bs-target="#handoutpasswordmodal"
                                            wire:click="getHandoutPassword({{ optional($course->schedule->course)->courseid }})">HANDOUT</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-0 m-4">
                                <div class="col">
                                    <span>
                                        <i class="bi bi-clock"></i>
                                        <span class="text-dark fw-medium">Duration:</span>
                                        {{ $course->course->trainingdays > 1 ? $course->course->trainingdays . ' days' :
                                        $course->course->trainingdays . ' day' }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <span>
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="text-dark fw-medium">Training Schedule:</span> {{
                                        $course->schedule->batchno }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        {{ $enrolled_courses->links() }}
                        @else
                        <div style="height: 50vh">
                            <div class="card shadow-lg mb-2">
                                <div class="d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex">
                                        <h5 class="mb-1 text-muted">
                                            <p>You are not enrolled in any courses</p>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div wire:ignore.self class="modal fade" id="generateModalDrop" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Confirmation
                                        of Drop</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="drop" id="drop">
                                        <div class="row gx-3">
                                            <div class="col-md-12 col-12">
                                                <div class="alert alert-danger">
                                                    Are you sure you want to mark the record as dropped? Click "Proceed"
                                                    to confirm and process the drop action.
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <label for="">Add a reason why you drop the trainee:</label>
                                                <textarea class="form-control" name="" id="" cols="30" rows="10"
                                                    wire:model.defer="reason"></textarea>
                                                @error('reason')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="drop" class="btn btn-primary">Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- <div class="col-md-12 col-12">
                            <h3>My Certificates</h2>
                                @if ($certificates->isEmpty())
                                <div style="height: 50vh">
                                    <div class="card shadow-lg mb-2">
                                        <div class="d-flex justify-content-between align-items-center p-4">
                                            <div class="d-flex">

                                                <h5 class="mb-1 text-muted">
                                                    <p>You dont have any certificates</p>
                                                </h5>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @foreach ($certificates as $certificate)
                                <div class="card shadow-lg mb-2 hover ">
                                    <div class="d-flex justify-content-between align-items-center p-4">
                                        <div class="d-flex">
                                            <div class="row">
                                                <h4 class="mb-1">
                                                    <span class="link-primary" target="_blank"
                                                        wire:click.prevent="redirectToCertHistoryDetails({{ $certificate->certificatehistoryid }})">
                                                        {{ $certificate->certificatenumber }}
                                                    </span>


                                                </h4>
                                                <p class="mb-0 fs-6">
                                                    <span class="me-2">
                                                        <span><span class="text-dark fw-medium">Registration
                                                                Number:</span>
                                                            {{ $certificate->registrationnumber }}</span>
                                                        <span><span class="text-dark fw-medium">Created:</span>
                                                            {{
                                                            \Carbon\Carbon::parse($certificate->date_printed)->format('d
                                                            M Y') }}</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        </div> --}}
                        <!-- <div class="col-md-6 col-12">
                    <h3>My Uploaded Documents</h2>

                        @if ($documents->isEmpty())
                        <div style="height: 50vh">
                            <div class="card shadow-lg mb-2">
                                <div class="d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex">

                                        <h5 class="mb-1 text-muted">
                                            <p>You dont have uploaded any documents</p>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @foreach ($documents as $doc)
                        <div class="card shadow-lg mb-2">
                            <div class="d-flex justify-content-between align-items-center p-4">
                                <div class="d-flex">
                                    <div class="row">
                                        <h4 class="mb-1">
                                            <a href="/storage/uploads/{{ $doc->d_path }}" target="_blank">
                                                {{ $doc->d_name }}
                                            </a>
                                        </h4>
                                        <p class="mb-0 fs-6"> <span class="me-2">
                                                <span class="text-dark fw-medium">Uploaded:</span>


                                                <span><span class="text-dark fw-medium">
                                                    </span>
                                                    {{ Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div> -->

                <div wire:ignore.self class="modal fade" id="archiveModal" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"
                                                        wire:model.defer="title">Delete request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form wire:submit.prevent="setArchieved" id="setPassed">
                                                        @csrf
                                                        <div class="row gx-3">
                                                            <div class="alert alert-success d-flex align-items-center"
                                                                role="alert">
                                                                <div>
                                                                    Are you sure you want to archive your enrolled
                                                                    courses?
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" form="setPassed" class="btn btn-primary">Yes,
                                                        confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                        <!--Enter handout password modal-->
                        <div wire:ignore.self class="modal fade" id="handoutpasswordmodal" tabindex="-1" role="dialog"
                            aria-labelledby="editmodal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title mb-0" id="newCatgoryLabel">
                                            Enter handout password
                                        </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <x-request-message />
                                        <form wire:submit.prevent="verifyHandoutPassword">
                                            @csrf
                                            <div class="mb-3 mb-2">
                                                <label class="form-label" for="title">Enter handout password</label>
                                                <input type="text" class="form-control"
                                                    wire:model.defer="handout_password" required>
                                            </div>


                                            <div>
                                                <button type="submit" class="btn btn-primary">Next</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @include('livewire.components.data-privacy-modal')
    </section>
</main>