<div class="mt-5 mb-5 container card text-center">

    <div class="card-header">
        <h2 class="card-title">Update your Profile</h2>
        <p class="card-text">Please update your profile as it will be used for your training certificates and enrollment.
        </p>
    </div>

    <div class="card-body row">
        <form wire:submit.prevent="update">
            @csrf
            <div class="col-md-12">
                <x-request-message />
                <h3 class="fw-bold float-start">Personal</h3>
                <br>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <label class="float-start">Firstname</label>
                        <input class="form-control {{$errors->has('f_name') ? 'is-invalid':''}}" wire:model="f_name">
                        @error('f_name') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="float-start">Middlename</label>
                        <input class="form-control {{$errors->has('m_name') ? 'is-invalid':''}}" wire:model="m_name">
                        @error('m_name') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="float-start">Lastname</label>
                        <input class="form-control {{$errors->has('l_name') ? 'is-invalid':''}}" wire:model="l_name">
                        @error('l_name') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="float-start">Suffix</label>
                        <input class="form-control {{$errors->has('suffix') ? 'is-invalid':''}}" wire:model="suffix">
                        @error('suffix') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h3 class="fw-bold float-start">Contact</h3>
                <br>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <label class="float-start">Email</label>
                        <input class="form-control {{$errors->has('email') ? 'is-invalid':''}}" wire:model="email">
                        @error('email') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="float-start">Contact #</label>
                        <div class="input-group">
                                <div class="input-group-append">
                                    <select class="form-control" wire:model="dialing_code_id">
                                            @foreach ($dialing_code as $item)
                                                    <option value="{{$item->id}}">{{$item->country_code}}(+{{$item->dialing_code}})</option>
                                            @endforeach
                                    </select>
                                </div>
                                <input class="form-control {{$errors->has('contact_num') ? 'is-invalid':''}}" wire:model="contact_num">
                        </div>
                        @error('contact_num') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h3 class="fw-bold float-start">Employment</h3>
                <br>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <label class="float-start"><text style="color:red">*</text> Status</label>
                        <select class="form-control" wire:model="status_id" required>
                            <option value="">Select status</option>
                                @foreach ($status as $item)
                                    <option value="{{$item->id}}">{{$item->status}}</option>
                                @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="float-start"><text style="color:red">*</text> Company</label>
                        <select class="form-control @error('company_id') is-invalid @enderror" wire:model="company_id" required>
                            <option value="">Select company</option>
                            @foreach ($companys as $company)
                                <option value="{{ $company->companyid }}">{{ $company->company }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    
                    <div class="col-md-4">
                        @if ($company_id == 1)
                            <label class="float-start"><text style="color:red">*</text> Fleet</label>
                            <select class="form-control @error('fleet_id') is-invalid @enderror" wire:model="fleet_id">
                                <option value="">Select Fleet</option>
                                @foreach ($fleets as $fleet)
                                    <option value="{{$fleet->fleetid}}"> {{$fleet->fleet}}</option>
                                @endforeach
                            </select>
                            @error('fleet_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        @endif
                    </div>
                    
                  
                    {{-- <div class="col-md-4 mt-2">
                        <label class="float-start"><text style="color:red">*</text> Vessel</label>
                        <select class="form-control @error('vessel') is-invalid @enderror" wire:model="vessel" required>
                            <option value="" >Select Vessel</option>
                            @foreach ($vessels as $vessel)
                                <option value="{{ $vessel->id }}">{{ $vessel->vesselname }}</option>
                            @endforeach
                        </select>
                        @error('vessel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    

                    <div class="col-md-4 mt-2">
                        <label class="float-start"><text style="color:red">*</text> SRN</label>
                        <input class="form-control {{$errors->has('srn_num') ? 'is-invalid':''}}" wire:model="srn_num">
                        @error('srn_num') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="col-md-4 mt-2">
                        <label class="float-start"><text style="color:red">*</text> TIN Number</label>
                        <input class="form-control {{$errors->has('tin_num') ? 'is-invalid':''}}" wire:model="tin_num">
                        @error('tin_num') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                  
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary float-end mt-2">Save</button>
            </div>
        </form>
    </div>

    <div class="card-footer text-danger fw-bold">
        Updated {{ $trainee_data->last_updated }}
    </div>

</div>
