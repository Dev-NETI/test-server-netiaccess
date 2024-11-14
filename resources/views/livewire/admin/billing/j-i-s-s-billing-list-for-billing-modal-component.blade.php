<div wire:ignore.self id="addBillingModal" class="modal fade gd-example-modal-xl" tabindex="-1" role="dialog"
    aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light-info">
                <h3 class="modal-title" id="exampleModalToggleLabel">Add Billing Statements</h3>
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
                        <input type="text" class="form-control" wire:model="CourseTitle"
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
                        <input type="text" class="form-control" wire:model="MonthCovered"
                            placeholder="e.g. January 2024">
                        @error('MonthCovered')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-3">
                        <label for="">Serial Number <small class="text-danger">(Optional)</small></label>
                        <input type="text" class="form-control" wire:model="serialNumber" placeholder="e.g. 2407-01J">
                        @error('serialNumber')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-3">
                        <label for="">Type <span><small><a
                                        href="{{asset('billingtemplates/JISS/Upload_trainee_tmp.xlsx')}}">(download
                                        template here)</a></small></span></label>
                        <div class="form-check form-switch  mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" wire:model="ToggleType"
                                id="flexSwitchVat">
                            <label class="form-check-label" for="flexSwitchVat">Upload Trainee Data From
                                Excel</label>
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="flexSwitchCheckChecked">
                            Is it Vat or Service Charge?
                        </label>
                        <div class="form-check form-switch  mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" wire:model="vatOrSCModel"
                                id="flexSwitchCheckChecked">
                            <label class="form-check-label" for="flexSwitchCheckChecked">{{ $vatOrSC }}</label>
                        </div>
                    </div>

                    <div class="col-2 mt-3 text-center">
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
                            <input type="number" wire:model="dorm_expenses" class="form-control"
                                placeholder="Enter amount">
                        </div>
                        <div class="col-4">
                            <label for="" class="">Meal Expenses <small>(if any)</small></label>
                            <input type="number" wire:model="meal_expenses" class="form-control"
                                placeholder="Enter amount">
                        </div>
                        <div class="col-4">
                            <label for="" class="">Transportation Expenses <small>(if any)</small></label>
                            <input type="number" wire:model="transpo_expenses" class="form-control"
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

                @for ($x = 0; $x < $TraineeNumber; $x++) <div class="row mt-2">
                    <div class="col-6">
                        <label for="">Trainee Name</label>
                        <input type="text" class="form-control" wire:model="TraineeInfo.{{$x}}.name"
                            placeholder="Enter trainee name">
                    </div>
                    <div class="col-6">
                        <label for="">Nationality</label>
                        <select class="form-select" name="" id="" wire:model="TraineeInfo.{{$x}}.nationality">
                            @foreach ($nationality as $item)
                            <option value="{{ $item->nationality }}">{{ $item->nationality }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" wire:model="TraineeInfo.{{$x}}.nationality"
                            placeholder="Enter nationality"> --}}
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

                        <button class="btn btn-secondary" wire:target='file' wire:loading.class='disabled btn-warning'
                            type="submit" id="button-addon2">
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
            <button class="btn btn-primary" wire:click="submitTraineeInfo()">Add</button>
        </div>
    </div>
</div>
</div>