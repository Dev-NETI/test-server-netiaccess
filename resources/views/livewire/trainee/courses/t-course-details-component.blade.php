<section class="pt-5 pb-5">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="container">
        <!-- Card -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">{{$selected_c->course->coursename}}
                    @if ($selected_c->pendingid == 1)
                    <span class="badge bg-warning">
                        pending
                    </span>
                    @else
                    <span class="badge bg-success">
                        enrolled
                    </span>
                    @endif
                </h3>
                <p class="mb-0"> <i>Registration number: {{$selected_c->registrationcode}} </i>
                </p>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <h4>ENROLLMENT INFORMATION:</h4>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Training schedule: <i>{{$selected_c->schedule->startdateformat}} - {{$selected_c->schedule->enddateformat}}</i></p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <p>Date of online:
                            @if ($selected_c->schedule->dateonlinefrom)
                            @if ($selected_c->schedule->dateonlinefrom === $selected_c->schedule->dateonlineto)
                            <i>{{$selected_c->schedule->dateonlinefrom}}</i>
                            @else
                            <i>{{$selected_c->schedule->dateonlinefrom}} - {{$selected_c->schedule->dateonlineto}}</i>
                            @endif
                            @else
                            <i> --no schedule for online--</i>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <p>Date of onsite:
                            @if ($selected_c->schedule->dateonsitefrom)
                            @if ($selected_c->schedule->dateonsitefrom === $selected_c->schedule->dateonsiteto)
                            <i>{{$selected_c->schedule->dateonsitefrom}}</i>
                            @else
                            <i>{{$selected_c->schedule->dateonsitefrom}} - {{$selected_c->schedule->dateonsiteto}}</i>
                            @endif
                            @else
                            <i> --no schedule for onsite--</i>
                            @endif
                        </p>
                    </div>
                </div>

                <h4>BILLING INFORMATION:</h4>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Payment mode: {{$selected_c->payment->paymentmode}}</h6>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        @if ($selected_c->paymentmodeid == 1)
                        <p> Total amount: -- n/a --</p>
                        @else
                        <p> Total amount: ₱ {{number_format($selected_c->total,2,'.',',')}}</p>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <p>
                            Room type:
                            @if ($selected_c->dorm)
                            {{$selected_c->dorm->dorm}}
                            @else
                            -- n/a --
                            @endif
                        </p>
                    </div>
                </div>
                @if ($selected_c->paymentmodeid == 1)

                @else
                <div class="mt-3">
                    <b>SELECTED PACKAGE:</b> <i> #{{$selected_c->t_fee_package}}
                        @if ($selected_c->t_fee_package == 1)
                        (Training schedule with Lunch Meal and Polo Shirt.)
                        @elseif ($selected_c->t_fee_package == 2)
                        (Training schedule with Lunch Meal, Polo Shirt and Bus round trip.)
                        @elseif ($selected_c->t_fee_package == 3)
                        (Training schedule with Lunch Meal, Polo Shirt & Bus Daily Trip.)
                        @endif</i>
                </div>
                @endif


                <hr class="my-5">

            </div>
        </div>

        <div class="card my-5">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="text-right mt-2">
                            <h4>SHORCUT KEY</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="float-end">
                            <!-- Button trigger modal -->
                            @if ($selected_c->course->type->coursetypeid == 1)
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModalCenter1">
                                <i class="mdi mdi-checkbox-multiple-marked-circle"></i> REQUIREMENTS
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter1Title" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">List of Requirements</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <small> 1. Medical certification (PEME FORMAT) from any Clinic / Hospital recognized by Marina and accredited by DOH <a href="https://stcw.marina.gov.ph/wp-content/uploads/2016/02/MFOWS-as-of-March-2023-2-1.pdf" target="_blank"><i>(Click here to check)</i></a> <br> 2. Certificate of Proficiency <br> 3. 2x2 ID Picture (Soft Copy) <br> 4. Proof of Payment </small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_file">
                                <i class="mdi mdi-upload"></i> UPLOAD
                            </button> --}}
                            <!-- Modal -->
                            <div wire:ignore.self class="modal fade" id="modal_file" tabindex="-1" role="dialog" aria-labelledby="modal_file" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal_file">UPLOAD THE FILE</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form wire:submit.prevent="upload" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <p class="mb-1 text-dark">Upload the file here: <small style="color: red;"><i>(It only accept pdf,docx,png,jpg,jpeg.)</i></small></p>
                                                    <div class="input-group mb-1">
                                                        <input type="file" class="form-control" wire:model.defer="file">
                                                        <button class="input-group-text" type="submit">Upload</button>
                                                    </div>
                                                    @error('file') <small class="text-muted">{{$message}} </small>@enderror
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <button href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#handoutpasswordmodal" wire:click="">
                                HANDOUT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-body">
                <div class="row">
                    @if ($documents->count())
                    @foreach ($documents as $doc)
                    <div class="col-md-6 col-sm-12">
                        <h4 class="mt-1">
                            <a href="/storage/uploads/{{$doc->d_path}}" target="_blank">
                                {{$doc->d_name}}
                            </a>
                        </h4>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="float-end">
                            <figcaption class="figure-caption">
                                {{ number_format($doc->d_size, 2) }} KB
                                <button type="button" class="btn btn-danger py-1" wire:click.prevent="deletedocs({{$doc->id}})">
                                    <i class="mdi mdi-delete"></i> DELETE
                                </button>
                            </figcaption>
                        </div>
                    </div>
                    <hr class="mt-1">
                    @endforeach
                    @else
                    <div class="text-center">
                        <p class="text-mute">-UPLOADED DOCUMENTS WILL LISTED HERE-</p>
                    </div>
                    @endif
                </div>
            </div> --}}
        </div>



        <!--Enter handout password modal-->
        <div wire:ignore.self class="modal fade" id="handoutpasswordmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
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




    </div>
</section>