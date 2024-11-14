<div class="card m-2">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <h1>Edit Company Profile</h1>
                <form wire:submit.prevent="saveProfile">
                    @csrf
                    <div class="mb-3">
                        <label for="company">Company Name</label>
                        <input type="text" class="form-control" id="company" name="company"
                            wire:model.defer="company">
                    </div>
                    <div class="mb-3">
                        <label for="designation">Designation</label>
                        <input type="text" class="form-control" wire:model.defer="designation" id="designation">
                    </div>
                    <div class="mb-3">
                        <label for="addressline1">Address Line 1</label>
                        <input type="text" class="form-control" wire:model.defer="addressline1" id="addressline1">
                    </div>
                    <div class="mb-3">
                        <label for="addressline2">Address Line 2</label>
                        <input type="text" class="form-control" wire:model.defer="addressline2" id="addressline2">
                    </div>
                    <div class="mb-3">
                        <label for="addressline3">Address Line 3</label>
                        <input type="text" class="form-control" wire:model.defer="addressline3" id="addressline3">
                    </div>
                    <div class="mb-3">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" wire:model.defer="position" id="position">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
