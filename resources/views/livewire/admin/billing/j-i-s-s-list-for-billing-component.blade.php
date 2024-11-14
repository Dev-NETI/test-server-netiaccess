<section class="container-fluid p-4">
    <div class="row border-bottom">
        <div class="col-lg-12 text-center">
            <label class="h2" for="">JISS Billing List</label>
            <p>List of pending billing transactions.</p>
        </div>
    </div>
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search h3"></i></span>
                            <input type="text" class="form-control" placeholder="Search Company..."
                                aria-label="Search Company..." aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 22em;">
                        <table class="table table-striped text-nowrap mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Courses</th>
                                    <th>Trainees</th>
                                    <th>SerialNumber</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($LoadBilling as $data)
                                <tr>
                                    <td>{{ $data->companyinfo->company }}</td>
                                    <td>{{ $data->course->coursename }}</td>
                                    @php
                                    $trainees = json_decode($data->trainees);
                                    @endphp
                                    <td>
                                        {{-- @foreach ($trainees as $trainee)
                                        {{ $trainee->name." - ". optional($trainee)->nationality}} <br>
                                        @endforeach --}}
                                        {{count($trainees)}}
                                    </td>
                                    <td>
                                        @if ($data->serialnumber != null)
                                        {{ $data->serialnumber }}
                                        @else
                                        <span class="text-danger">- NULL -</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $this->generatePDF($data->id, false, true) }}
                                    </td>
                                    <td style="min-width: 8em;">
                                        <div class="dropdown">
                                            <a class="text-body text-primary-hover" href="#" role="button"
                                                id="dropdownThirteen" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownThirteen">

                                                @if ($billingstatusid == 0)
                                                @can('authorizeAdminComponents', 121)
                                                @if ($data->approver_1 == 0 && $billingstatusid == 0)
                                                <a class="dropdown-item" href="#"
                                                    wire:click="editBilling({{$data->id}})"><i
                                                        class="bi bi-pen-fill me-2"></i> Edit</a>
                                                <a class="dropdown-item" href="#"
                                                    wire:click="attachESig({{$data->id}})"><i
                                                        class="bi bi-vector-pen me-2"></i> Attach E-Signature</a>


                                                @else
                                                <a class="dropdown-item" href="#"
                                                    wire:click="removeESig({{$data->id}})"><i
                                                        class="bi bi-x-circle-fill me-2"></i> Remove E-Signature</a>
                                                @endif
                                                @endcan
                                                @endif

                                                @if($billingstatusid > 3)
                                                <a class="dropdown-item" href="#"
                                                    wire:click="openBillingModal({{$data->id}})"><i
                                                        class="bi bi-file-earmark-pdf-fill me-2"></i> Generate PDF</a>
                                                @else
                                                <a class="dropdown-item" href="#"
                                                    wire:click="checkAvailablePM({{$data->id}})"><i
                                                        class="bi bi-file-earmark-pdf-fill me-2"></i> Generate PDF</a>

                                                @endif
                                                @if ($billingstatusid == 2)
                                                @can('authorizeAdminComponents', 122)
                                                @if ($data->approver_3 == 0)
                                                <a class="dropdown-item" href="#"
                                                    wire:click="attachESig3({{$data->id}})"><i
                                                        class="bi bi-vector-pen me-2"></i> Attach E-Signature</a>
                                                @else
                                                <a class="dropdown-item" href="#"
                                                    wire:click="removeESig3({{$data->id}})"><i
                                                        class="bi bi-x-circle-fill me-2"></i> Remove E-Signature</a>
                                                @endif
                                                @endcan
                                                <a class="dropdown-item"
                                                    wire:click="sendBack({{ $data->id }}, {{ 1 }})"><i
                                                        class="bi bi-arrow-return-left me-2"></i> Send Back </a>

                                                <a class="dropdown-item" wire:click="sendToBOD({{ $data->id }})"><i
                                                        class="bi bi-send-fill me-2"></i> Send to BOD Review
                                                    Board</a>

                                                @endif

                                                @if ($billingstatusid == 3)
                                                <a class="dropdown-item"
                                                    wire:click="sendBack({{ $data->id }}, {{ 2 }})"><i
                                                        class="bi bi-arrow-return-left me-2"></i> Send Back to GM</a>
                                                <a class="dropdown-item" wire:click="sendToClient({{ $data->id }})"><i
                                                        class="bi bi-send-fill me-2"></i> Send to Client Board</a>
w
                                                @endif

                                                @if ($billingstatusid == 5)
                                                <a class="dropdown-item" wire:click="sendToClient({{ $data->id }})"><i
                                                        class="bi bi-eye-fill me-2"></i> View Attachment</a>
                                                <a class="dropdown-item"
                                                    wire:click="confirmProofofPayment({{ $data->id }})"><i
                                                        class="bi bi-check me-2"></i> Confirm Proof of Payment</a>
                                                @endif

                                                @if ($billingstatusid == 6)
                                                <a class="dropdown-item"><i class="bi bi-cloud-upload-fill me-2"></i>
                                                    Upload Receipt</a>
                                                @endif

                                                @if ($billingstatusid == 7)
                                                <a class="dropdown-item"
                                                    wire:click="openUploadORModal({{$data->id}})"><i
                                                        class="bi bi-upload me-2"></i> Upload
                                                    Official Receipt</a>
                                                @endif

                                                @if ($billingstatusid == 0)
                                                @if ($data->approver_1 != 0)
                                                <a class="dropdown-item"
                                                    wire:click="sendToBODManagerBoard({{ $data->id }})"><i
                                                        class="bi bi-send-fill me-2"></i> Send
                                                    to BOD Review Board</a>
                                                @endif
                                                <a class="dropdown-item" href="#"
                                                    wire:click="openModalForward({{$data->id}})"><i
                                                        class="bi bi-skip-forward-fill me-2"></i>
                                                    Forward</a>
                                                @elseif ($billingstatusid == 1)
                                                @if ($data->approver_2 == 0)
                                                @can('authorizeAdminComponents', 156)
                                                <a class="dropdown-item"
                                                    wire:click="attachEsigBODManager({{$data->id}})">
                                                    <i class="bi bi-vector-pen me-2"></i>Attach E-Sig
                                                </a>
                                                @endcan
                                                @else
                                                <a class="dropdown-item"
                                                    wire:click="removeEsigBODManager({{$data->id}})">
                                                    <i class="bi bi-x-circle-fill me-2"></i>Remove E-Sig
                                                </a>
                                                @endif
                                                <a class="dropdown-item"
                                                    wire:click="sendBack({{ $data->id }}, {{ 0 }})"><i
                                                        class="bi bi-arrow-return-left me-2"></i> Send Back </a>
                                                <a class="dropdown-item" wire:click="sendToGMBoard({{ $data->id }})"><i
                                                        class="bi bi-send-fill me-2"></i> Send to GM Review Board</a>
                                                @endif

                                                @if ($billingstatusid == 4)
                                                <a class="dropdown-item"
                                                    wire:click="openUploadPPModal({{ $data->id }})"><i
                                                        class="bi bi-upload me-2"></i> Upload Proof of Payment</a>
                                                @endif

                                                @if ($billingstatusid == 0)
                                                <a class="dropdown-item"
                                                    wire:click="confirmJISSBilling({{ $data->id }})"><i
                                                        class="bi bi-trash-fill me-2"></i> Delete</a>
                                                @endif

                                                @if ($billingstatusid >= 7)
                                                <a class="dropdown-item"
                                                    wire:click="openModalViewAttachment({{ $data->id }})"><i
                                                        class="bi bi-eye-fill me-2"></i>
                                                    View Attachments</a>
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
                <div class="card-footer">
                    {{ $LoadBilling->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
    </div>

    <livewire:admin.billing.child.jiss.iframe-jiss-billing-modal :data="$data" />
    <livewire:admin.billing.child.jiss.modal-for-forwarding-billing :billingid="$billingid"
        wire:key="{{rand(0,19999)}}" />
    <livewire:admin.billing.j-i-s-s-billing-list-for-billing-modal-component />
    @if ($billingstatusid == 0)
    <button type="button" id="my-button" wire:click='resetVariables' class="btn btn-primary position-fixed shadow"
        style="right: 60px;
            bottom: 60px;
            border-radius: 50%;
            height: 80px;
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;"> <i class=" bi bi-plus-circle-fill" style="font-size: 3rem; margin: auto;"></i>
    </button>
    @endif

    <div wire:ignore.self class="modal fade gd-example-modal-lg" tabindex="-1" role="dialog"
        id="modalUploadJissAttachment" aria-labelledby="modalUploadJissAttachment" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primay">
                    <h3 class="modal-title" id="exampleModalToggleLabel">Upload Proof of Payments</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" wire:submit.prevent="uploadAttachments" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" accept="application/pdf" class="form-control" wire:model="JISSAttachmentFile"
                            placeholder="Upload file here" aria-label="Upload JISS Billing Attachment"
                            aria-describedby="button-addon2">
                        @error($JISSAttachmentFile)
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        <small wire:loading class="text-warning">Uploading file in progress...</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" wire:loading.class='disabled' type="submit">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade gd-example-modal-lg" tabindex="-1" role="dialog" id="modalAttachments"
        aria-labelledby="modalAttachments" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primay">
                    <h3 class="modal-title" id="exampleModalToggleLabel">Attachments</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table table-hover" width='100%'>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Attachment Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attachments as $key => $value)
                            @php
                            $key++;
                            @endphp
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$value->filetype == 1 ? 'Proof of Payment' : 'Official Receipt'}}</td>
                                <td><a wire:click="view('storage/{{ $value->attachmentpath }}')"
                                        class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade gd-example-modal-lg" tabindex="-1" role="dialog" id="modalUploadJissOR"
        aria-labelledby="modalUploadJissOR" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primay">
                    <h3 class="modal-title" id="exampleModalToggleLabel">Upload Official Receipts</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" wire:submit.prevent="uploadAttachmentsOR" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" accept="application/pdf" class="form-control"
                            wire:model="JISSAttachmentFileOR" placeholder="Upload file here"
                            aria-label="Upload JISS Billing Attachment" aria-describedby="button-addon2">
                        @error($JISSAttachmentFileOR)
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        <small wire:loading class="text-warning">Uploading file in progress...</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" wire:loading.class='disabled' type="submit">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self id="editBillingModal" class="modal fade gd-example-modal-xl" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light-danger">
                    <h3 class="modal-title" id="exampleModalToggleLabel">Edit Billing Statements</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-3">
                            <label for="">Course</label>
                            <select name="" class="form-select" wire:model="SelectedCourse" id="">
                                <option selected value="">Select Course</option>
                                @foreach ($LoadCourse as $data)
                                <option value="{{ $data->id }}">{{ $data->coursename }}</option>
                                @endforeach
                            </select>
                            @error('SelectedCourse')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-3">
                            <label for="">Company</label>
                            <select name="" class="form-select" wire:model="SelectedCompany" id="">
                                <option selected value="">Select Company</option>
                                @foreach ($LoadCompany as $data)
                                <option value="{{ $data->id }}">{{ $data->company }}</option>
                                @endforeach
                            </select>
                            @error('SelectedCompany')<span class="text-danger">{{ $message }}</span>@enderror

                        </div>
                        <div class="col-3">
                            <label for="">Course Title</label>
                            <input type="text" class="form-control" wire:model.lazy="CourseTitle"
                                placeholder="Enter course title">
                            @error('CourseTitle')<span class="text-danger">{{ $message }}</span>@enderror

                        </div>
                        <div class="col-3 mt-4">
                            <div class="form-check form-switch  mb-2">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Is Trainee Name
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        wire:model="isTraineeIncluded" id="">
                                    Included?</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-3">
                            <label for="">Month Covered <small>(Month Year)</small></label>
                            <input type="text" class="form-control" wire:model.lazy="MonthCovered"
                                placeholder="e.g. January 2024">
                            @error('MonthCovered')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-3">
                            <label for="">Serial Number <small>(Optional)</small></label>
                            <input type="text" class="form-control" wire:model.lazy="serialNumber"
                                placeholder="e.g. January 2024">
                            @error('serialNumber')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-3">
                            <label for="">Type <span><small><a
                                            href="{{asset('billingtemplates/JISS/Upload_trainee_tmp.xlsx')}}">(download
                                            template here)</a></small></span></label>
                            <div class="form-check form-switch  mb-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    wire:model.lazy="ToggleType" id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Upload Trainee Data
                                    From
                                    Excel</label>
                            </div>
                        </div>

                        <div class="col-3">
                            <label for="flexSwitchCheck">is Vat 12% or Service Charge (8) <span><small><a
                                            href="{{asset('billingtemplates/JISS/Upload_trainee_tmp.xlsx')}}">(download
                                            template here)</a></small></span></label>
                            <div class="form-check form-switch  mb-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    wire:model.lazy="vatOrSCModel" id="flexSwitchCheck">
                                <label class="form-check-label" for="flexSwitchCheck">{{$vatOrSC}}</label>
                            </div>
                        </div>

                        <div class="col-3 mt-3 text-center">
                            @if (!$addexp)
                            <button wire:click="toggleExpenses(true)" class="btn btn-sm btn-info">Add Other
                                Expenses</button>
                            @else
                            <button wire:click="toggleExpenses(false)" class="btn btn-sm btn-danger">Remove
                                Expenses</button>
                            @endif
                        </div>

                        @if ($addexp)
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="" class="">Dorm Expenses <small>(if any)</small></label>
                                <input type="number" wire:model.lazy="dorm_expenses" class="form-control"
                                    placeholder="Enter amount">
                            </div>
                            <div class="col-4">
                                <label for="" class="">Meal Expenses <small>(if any)</small></label>
                                <input type="number" wire:model.lazy="meal_expenses" class="form-control"
                                    placeholder="Enter amount">
                            </div>
                            <div class="col-4">
                                <label for="" class="">Transportation Expenses <small>(if any)</small></label>
                                <input type="number" wire:model.lazy="transpo_expenses" class="form-control"
                                    placeholder="Enter amount">
                            </div>
                        </div>
                        @endif
                    </div>


                    @if ($ToggleType == 0)
                    <div class="row mt-2">
                        <div class="col-12">
                            <label for="">Number of Trainees</label>
                            <input type="text" class="form-control" wire:model.debounce2000ms="TraineeNumber"
                                placeholder="Number of trainee">
                        </div>
                    </div>

                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <label for="" class="h3">Trainee Information</label>
                        </div>
                    </div>

                    @for ($x = 0; $x <$TraineeNumber; $x++) <div class="row mt-2">
                        <div class="col-6">
                            <label for="">Trainee Name</label>
                            <input type="text" class="form-control" wire:model.lazy="TraineeInfo.{{$x}}.name"
                                placeholder="Enter trainee name">
                        </div>
                        <div class="col-6">
                            <label for="">Nationality</label>
                            <select name="" wire:model.lazy="TraineeInfo.{{$x}}.nationality" class="
                            form-select" id="">
                                @foreach ($nationality as $item)
                                <option value="{{ $item->nationality }}">{{ $item->nationality }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" class="form-control"
                                wire:model.lazy="TraineeInfo.{{$x}}.nationality" placeholder="Enter nationality"> --}}
                        </div>
                </div>
                @endfor
                @else
                <form action="" wire:submit.prevent="upload" enctype="multipart/form-data">
                    <div class="row mt-2">
                        <label for="">Upload Excel File</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" wire:model="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                placeholder="Recipient's username" aria-label="Recipient's username"
                                aria-describedby="button-addon2">

                            <button class="btn btn-secondary" wire:target='file'
                                wire:loading.class='disabled btn-warning' type="submit" id="button-addon2">
                                <span wire:loading.class="d-none" wire:target='file'>
                                    Upload
                                </span>
                                <small wire:loading wire:target='file' style="font-size: 1em;">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </small>
                            </button>
                        </div>
                    </div>
                </form>
                @error('file') <span class="text-danger">{{ $message }}</span> @enderror

                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <label for="" class="h3">Trainee Information</label>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Nationality</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($TraineeInfo == [])
                            <tr class="text-center">
                                <td colspan="2">-- Table Empty --</td>
                            </tr>
                            @else
                            @foreach ($TraineeInfo as $index => $item)
                            @if ($index == 0)

                            @else
                            <tr class="">
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['nationality'] }}</td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="executeUpdateBilling">Update</button>
            </div>
        </div>
    </div>

</section>