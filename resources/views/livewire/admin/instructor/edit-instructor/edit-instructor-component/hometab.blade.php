<div wire:ignore.self class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <h3>Personal Info</h3>
    <form wire:submit.prevent="updateinstructor">
        @csrf
        <div class="row">
            <div class="col-4 mt-1">
                <label class="form-label" for="">Lastname</label>
                <input type="text" class="form-control" wire:model.defer="l_name">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Firtname</label>
                <input type="text" class="form-control" wire:model.defer="f_name">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Middlename</label>
                <input type="text" class="form-control" wire:model.defer="m_name">
            </div>
            <div class="col-2 mt-1">
                <label class="form-label" for="">Suffix <span class="text-danger" style="font-size: 10px;">(Leave blank
                        if not applicable)</span></label>
                <input type="text" class="form-control" wire:model.defer="suffix" placeholder="--Not Set--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Nickname</label>
                <input type="text" class="form-control" placeholder="--Not Set--" wire:model.defer="nickname">
            </div>
            <div class="col-2 mt-1">
                <label class="form-label" for="">Gender</label>
                <label class="form-label">Select Option </label>
                <select class="form-select" wire:model.defer="genderid" data-width="100%">
                    @foreach ($tblgender as $tblgender)
                    <option value="{{$tblgender->genderid}}">{{$tblgender->gender}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Rank</label>
                <select class="form-select" wire:model.defer="rankid">
                    @foreach ($tblrank as $tblranks)
                    <option value="{{ $tblranks->rankid }}">
                        {{ $tblranks->rank }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Date of Birth</label>
                <input type="text" id="birthday" class="form-control flatpickr" wire:model.defer="dob"
                    placeholder="--Not Set--">
            </div>
            <div class="col-1 mt-1">
                <label class="form-label" for="">Age</label>
                <input type="text" readonly class="form-control" wire:model.defer="age" placeholder="--Not Set--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Place of Birth <span class="text-danger"
                        style="font-size: 10px;">(City, Province)</span></label>
                <input type="text" class="form-control" wire:model.defer="pob" placeholder="--Not Set--">
            </div>
            <div class="col-2 mt-1">
                <label class="form-label" for="">Mobile Number</label>
                <input type="text" class="form-control" wire:model.defer="phone" placeholder="--Not Set--">
            </div>
            <div class="col-2 mt-1">
                <label class="form-label" for="">Viber Number</label>
                <input type="text" class="form-control" wire:model.defer="viberno" placeholder="--Not Set--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Email Address</label>
                <input type="text" class="form-control" wire:model.defer="email" placeholder="--Not Set--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Tel. Number <span class="text-danger" style="font-size: 10px;">(Type
                        NONE if not applicable)</span></label>
                <input type="text" class="form-control" wire:model.defer="telphone" placeholder="--Not Set--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Civil Status</label>
                <select class="form-select" wire:model.defer="civilstat" data-width="100%">
                    @foreach ($tblcivilstatus as $tblcivilstatus)
                    <option value="{{$tblcivilstatus->civilstatusid}}">{{$tblcivilstatus->civilstatus}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Citizenship</label>
                <input type="text" class="form-control" placeholder="--Not Set--" wire:model.defer="citizenship">
            </div>


            <hr class="mt-3">
            <div class="row" id="addressdiv">
                <h3>Address <span class="text-danger" style="font-size: 13px;"><i>(Please
                            complete the address to update the address information by selecting from
                            menu)</i></span><br><span class="text-success pt-0 mt-0"
                        style="font-size: 13px;"><i>(current: {{$fulladdress}})</i></span></h3>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Region <span class="text-danger">*</span></label>
                    <select id="regionselect" wire:model="region" class="form-select">
                        <option value="">--Select Region--</option>
                        @foreach ($refregion as $refregion)
                        <option value="{{$refregion->id}}">{{$refregion->regDesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Province <span class="text-danger">*</span></label>
                    <select wire:model="province" class="form-select">
                        @if ($refprovince)
                        <option value="">--Select Province--</option>
                        @foreach ($refprovince as $refprovince)
                        <option value="{{$refprovince->id}}">{{$refprovince->provDesc}}</option>
                        @endforeach
                        @else
                        <option value="">--Select Region First--</option>
                        @endif
                    </select>
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Municipality <span class="text-danger">*</span></label>
                    <select wire:model="municipality" class="form-select">
                        @if ($refmunicipality)
                        <option value="">--Select Municipality--</option>
                        @foreach ($refmunicipality as $refmunicipality)
                        <option value="{{$refmunicipality->id}}">{{$refmunicipality->citymunDesc}}</option>
                        @endforeach
                        @else
                        <option value="">--Select Province First--</option>
                        @endif
                    </select>
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Barangay <span class="text-danger">*</span></label>
                    <select wire:model="barangay" class="form-select">
                        @if ($refbrgy)
                        <option value="">--Select Barangay--</option>
                        @foreach ($refbrgy as $refbrgy)
                        <option value="{{$refbrgy->id}}">{{$refbrgy->brgyDesc}}</option>
                        @endforeach
                        @else
                        <option value="">--Select Municipality First--</option>
                        @endif
                    </select>
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Street</label>
                    <input type="text" wire:model.defer="street" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Zip Code</label>
                    <input type="text" wire:model.defer="postal" placeholder="--Not Specified--" class="form-control">
                </div>
            </div>

            <hr class="mt-3">
            <h3>Person to Contact</h3>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Contact Person</label>
                <input type="text" wire:model.defer="contactperson" class="form-control"
                    placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Relationship w/ Contact Person</label>
                <input type="text" class="form-control" wire:model.defer="relateconperson"
                    placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Contact Person Mobile Number</label>
                <input type="text" class="form-control" wire:model.defer="connumber" placeholder="--Not Specified--">
            </div>

            <hr class="mt-3">
            <h3>Government ID's</h3>
            <div class="col-4 mt-1">
                <label class="form-label" for="">SSS NO.</label>
                <input type="text" class="form-control" wire:model.defer="sss" placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">TIN NO.</label>
                <input type="text" class="form-control" wire:model.defer="tin" placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">PAG-IBIG NO.</label>
                <input type="text" class="form-control" wire:model.defer="pino" placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">PhilHealth No.</label>
                <input type="text" class="form-control" wire:model.defer="phno" placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Passport No.</label>
                <input type="text" class="form-control" wire:model.defer="passno" placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Passport Date Issued</label>
                <input type="date" class="form-control flatpickr" wire:model.defer="passissued"
                    placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Passport Place Issued</label>
                <input type="text" class="form-control" wire:model.defer="passplaceissued"
                    placeholder="--Not Specified--">
            </div>
            <div class="col-4 mt-1">
                <label class="form-label" for="">Passport Expiration Date</label>
                <input type="text" class="form-control flatpickr" wire:model.defer="passexpire"
                    placeholder="--Not Specified--">
            </div>

            <hr class="mt-3">
            <h3>Bank Details</h3>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Bank Name</label>
                <input type="text" class="form-control" wire:model.defer="bankname" placeholder="--Not Specified--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Account Name</label>
                <input type="text" class="form-control" wire:model.defer="accname" placeholder="--Not Specified--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Account Number</label>
                <input type="text" class="form-control" wire:model.defer="accno" placeholder="--Not Specified--">
            </div>
            <div class="col-3 mt-1">
                <label class="form-label" for="">Date Started in NETI</label>
                <input type="date" class="form-control flatpickr" wire:model.defer="datestartatneti"
                    placeholder="--Not Specified--">
            </div>

            <div class="col-12 d-grid">
                <button type="submit" class="btn btn-primary mt-3">Update Informations</button>
            </div>
        </div>
    </form>
</div>