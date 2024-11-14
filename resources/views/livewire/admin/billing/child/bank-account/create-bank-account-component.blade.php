<div wire:ignore class="modal fade" id="AddBankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Add Bank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
                <form id="saveBank" wire:submit.prevent="store">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-12 mt-3">
                            <label>Account</label>
                            <input type="text" form="saveBank" class="form-control {{$errors->has('account') ? 'is-invalid' : '' }} " wire:model="account" >
                            @error('account') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>Account Name</label>
                            <input type="text" form="saveBank" class="form-control {{$errors->has('account_name') ? 'is-invalid' : '' }} " wire:model="account_name" >
                            @error('account_name') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>Account Number</label>
                            <input type="text" form="saveBank" class="form-control {{$errors->has('account_number') ? 'is-invalid' : '' }} " wire:model="account_number" >
                            @error('account_number') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>Bank</label>
                            <input type="text" form="saveBank" class="form-control {{$errors->has('bank_name') ? 'is-invalid' : '' }} " wire:model="bank_name" >
                            @error('bank_name') <small class="text-danger">{{$message}}</small> @enderror
                         </div>
                         <div class="col-md-12 mt-3">
                            <button type="submit" form="saveBank" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                         </div>
                    </div>
                </form>
        </div>
        <div class="modal-footer">
        </div>
    </div>
    </div>
</div>