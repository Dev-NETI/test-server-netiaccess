
<section class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Certificates/Accreditations
                        ({{ count($user->instructor->instructorlicense) }})</h1>
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
                                Certificates
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- card -->
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <p class="h3">Instructor's Profile <span class="fw-normal">({{ $user->f_name }}
                                        {{ $user->l_name }})</span></p>
                            </div>
                            <div class="col-6 text-end">
                                <button type="reset" form="adddocu" class="btn btn-primary mt-1" data-bs-toggle="modal"
                                    data-bs-target="#addmodal">Add Accreditation</button>

                                {{-- Add License Modal --}}
                                <div wire:ignore.self id="addmodal" class="modal fade gd-example-modal-lg text-start" tabindex="-1"
                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalToggleLabel">Add Document</h3>
                                            </div>
                                            <div class="modal-body pt-1">
                                                <form id="adddocu" action="" wire:submit.prevent="adddocument"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <!-- Danger alert -->
                                                    <div class="alert alert-danger d-flex align-items-center mt-1"
                                                        role="alert">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                            height="50" fill="currentColor"
                                                            class="bi bi-exclamation-triangle-fill me-2"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                        </svg>
                                                        <div>
                                                            WARNING!!! THE SYSTEM ONLY ACCEPTS PDF FILES, PLEASE CONVERT
                                                            OTHER FILE TYPES TO PDF FIRST
                                                        </div>
                                                    </div>
                                                    <label class="form-label" for="">LICENSE/DOCUMENT TITLE
                                                        <span class="text-danger" style="font-size: .8em;">(ex. Panama
                                                            License, JISS Certificate, etc.)</span></label>
                                                    <input required class="form-control" type="text"
                                                        wire:model.defer="license">
                                                    <input class="form-control" hidden type="text"
                                                        wire:model.defer="instructorid">
                                                    <label class="form-label mt-1" for="">LICENSE TYPE</label>
                                                    <select required class="form-select" wire:model.defer="licensetypeid"
                                                        data-width="100%">
                                                        <option value="">Select License Type</option>
                                                        @foreach ($licensetype as $license)
                                                            <option value="{{ $license->instructorlicensetypeid }}">
                                                                {{ $license->licensetype }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label class="form-label mt-1" for="">SELECT FILE <span
                                                            class="text-danger" style="font-size: .8em;">(must be in pdf
                                                            format)</span></label>
                                                    <input id="licensefile" required type="file" name="" accept="application/pdf"
                                                        wire:model.defer="licensefile" class="form-control">
                                                    <label class="form-label mt-1" for="">LICENSE/CERTIFICATE
                                                        NO.</label>
                                                    <input required class="form-control" type="text"
                                                        wire:model.defer="licensecertno">
                                                    <label class="form-label mt-1" for="">ISSUING AUTHORITY
                                                        <span class="text-danger" style="font-size: .8em;">(ex. PANAMA,
                                                            PRC, MLIT, etc.)</span></label>
                                                    <input required class="form-control" type="text"
                                                        wire:model.defer="issuingauthority">
                                                    <label class="form-label mt-1" for="">Date of Issue <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" id="dateofissue" class="form-control flatpickr is-invalid"
                                                        wire:model.defer="dateofissue" placeholder="Click to pick date">
                                                    <label class="form-label mt-1" for="">Expiration
                                                        Date</label>
                                                    <input type="date" class="form-control flatpickr"
                                                        wire:model.defer="expirationdate"
                                                        placeholder="Click to pick date">
                                            </div>
                                            <div class="modal-footer">
                                                </form>
                                                <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                <button id="resetadddocu" class="btn btn-warning" type="reset"
                                                    form="adddocu">Reset</button>
                                                <button id="adddocubtn" form="adddocu" disabled class="btn btn-info" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <button class="btn btn-info mt-1">Download Template</button> --}}
                                {{-- <button class="btn btn-success mt-1" data-bs-toggle="modal" data-bs-target="#importexcelaccreditation">Import Accreditation from Excel</button> --}}

                                <div id="importexcelaccreditation" class="modal fade gd-example-modal-lg text-start" tabindex="-1"
                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalToggleLabel">Import Accreditation</h3>
                                            </div>
                                            <div class="modal-body p-2">
                                                <form>
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="" class="form-label"><h5>Excel File:</h5></label>
                                                            <input class="form-control" type="file">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer p-1 col-lg-12">
                                                <button class="btn d-block btn-success text-center">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <script>
                            $(document).ready(function(){
                                $("#licensefile").on('input', function() {
                                    $("#dateofissue").val('');

                                });

                                $("#dateofissue").on('input', function() {
                                    $("#adddocubtn").removeAttr('disabled');
                                    $(this).removeClass('is-invalid').addClass('is-valid');
                                });

                                $("#resetadddocu").click(function(){
                                    $("#dateofissue").removeClass().addClass('form-control flatpickr is-invalid');
                                    $("#adddocubtn").attr('disabled', 'disabled');
                                });
                            });
                        </script>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- table  -->
                        <div class="card-body">
                            <div class="table-card">
                                <table class="table table-hover" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Action</th>
                                            <th>Accreditation Number</th>
                                            <th>Accreditation Course</th>
                                            <th>Capacity</th>
                                            <th>Issuing Authority</th>
                                            <th>Date Issued</th>
                                            <th>Expiration Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->instructor->instructorlicense as $user->instructor->instructorlicense)
                                            <tr>
                                                <td>
                                                    <button class="btn btn-warning mt-1"
                                                        wire:click.prevent="editlicense({{ $user->instructor->instructorlicense->instructorlicense }})"
                                                        data-bs-toggle="modal" data-bs-target="#editmodal"><i
                                                            class="bi bi-pencil-square"
                                                            style="font-size: 1em;"></i></button>
                                                            @if ($user->instructor->instructorlicense->licensepath != NULL)
                                                                <a href="/storage/uploads/instructorlicenses/{{$user->instructor->instructorlicense->licensepath}}" target="_blank" class="btn btn-info mt-1"><i
                                                                class="bi bi-eye-fill"></i></a>
                                                            @else
                                                                <a href="/storage/uploads/instructorlicenses/{{$user->instructor->instructorlicense->d_name}}" target="_blank" class="btn btn-info mt-1"><i
                                                                class="bi bi-eye-fill"></i></a>
                                                            @endif
                                                    
                                                    <button class="btn btn-danger mt-1"
                                                        wire:click.prevent="alertconfirm({{ $user->instructor->instructorlicense->instructorlicense }})"><i
                                                            class="bi bi-archive-fill"></i></button>
                                                </td>
                                                <td>{{ $user->instructor->instructorlicense->licensenumber }}</td>
                                                <td>{{ $user->instructor->instructorlicense->license }}</td>
                                                <td>@php
                                                    if ($user->instructor->instructorlicense->instructorlicensetype->instructortype == 1) {
                                                        echo "Instructor";
                                                    }elseif ($user->instructor->instructorlicense->instructorlicensetype->instructortype == 2) {
                                                        echo "Assessor";
                                                    }else{
                                                        echo "Others";
                                                    }
                                                @endphp</td>
                                                <td>{{ $user->instructor->instructorlicense->issuingauthority }}</td>
                                                <td>{{ \Carbon\Carbon::parse($user->instructor->instructorlicense->dateofissue)->format('F j, Y') }}
                                                </td>
                                                <td>
                                                    @if (
                                                        $user->instructor->instructorlicense->expirationdate != '0000-00-00' &&
                                                            $user->instructor->instructorlicense->expirationdate != null)
                                                        {{ \Carbon\Carbon::parse($user->instructor->instructorlicense->expirationdate)->format('F j, Y') }}
                                                    @else
                                                        No Expiration Date
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>


                                    {{-- Edit License Modal --}}
                                    <div wire:ignore.self class="modal fade gd-example-modal-lg text-start"
                                        tabindex="-1" role="dialog" id="editmodal"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="exampleModalToggleLabel">Edit Document
                                                    </h3>
                                                </div>
                                                <div class="modal-body p-1">
                                                    <form id="updateform" wire:submit="updatelicense">
                                                        @csrf
                                                        <!-- Danger alert -->
                                                        <div class="alert alert-danger d-flex align-items-center mt-1"
                                                            role="alert">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                                height="50" fill="currentColor"
                                                                class="bi bi-exclamation-triangle-fill me-2"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                            </svg>
                                                            <div>
                                                                WARNING!!! THE SYSTEM ONLY ACCEPTS PDF FILES, PLEASE
                                                                CONVERT OTHER FILE TYPES TO PDF FIRST
                                                            </div>
                                                        </div>
                                                        <label class="form-label" for="">LICENSE/DOCUMENT
                                                            TITLE <span class="text-danger"
                                                                style="font-size: .8em;">(ex. Panama License, JISS
                                                                Certificate, etc.)</span></label>
                                                        <input class="form-control" type="text"
                                                            wire:model.defer="license">
                                                        <input class="form-control" hidden type="text"
                                                            wire:model.defer="licenseid">
                                                        <label class="form-label mt-1"
                                                            for="">LICENSE/CERTIFICATE NO.</label>
                                                        <input class="form-control" type="text"
                                                            wire:model.defer="licensecertificateno">
                                                        <label class="form-label mt-1" for="">ISSUING
                                                            AUTHORITY <span class="text-danger"
                                                                style="font-size: .8em;">(ex. PANAMA, PRC, MLIT,
                                                                etc.)</span></label>
                                                        <input class="form-control" type="text"
                                                            wire:model.defer="issuingauthority">
                                                        <label class="form-label mt-1" for="">Date of
                                                            Issue</label>
                                                        <input type="text" class="form-control flatpickr"
                                                            wire:model.defer="dateofissue" placeholder="Click to pick date">
                                                        <label class="form-label mt-1" for="">Expiration
                                                            Date</label>
                                                        <input type="text" class="form-control flatpickr"
                                                            wire:model.defer="expirationdate"
                                                            placeholder="Click to pick date">
                                                        <label class="form-label mt-1" for="">LICENSE
                                                            TYPE</label>
                                                        {{-- <select class="selectpicker" data-width="100%" wire:model.defer="licensetypedetails">
                                                    @foreach ($editlicensetype as $editlicensetype)
                                                        <option value="{{$editlicensetype->instructorlicensetypeid}}">{{$editlicensetype->licensetype}}</option>
                                                    @endforeach
                                                 </select> --}}
                                                        <select class="form-select text-secondary"
                                                            wire:model.defer="licensetypeid" id="">
                                                            @foreach ($editlicensetype as $editlicensetype)
                                                                <option
                                                                    value="{{ $editlicensetype->instructorlicensetypeid }}">
                                                                    {{ $editlicensetype->licensetype }}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                </form>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button form="updateform" class="btn btn-info" type="submit">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
