<div wire:ignore.self class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <h3>Educational Background</h3>
        <form wire:submit.prevent="educationalbackground">
            @csrf
            <div class="row">
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Highest License</label>
                    <input type="text" wire:model.defer="license" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Date Issued</label>
                    <input type="text" wire:model.defer="licensedateissued" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Issued By</label>
                    <input type="text" wire:model.defer="licenseissuedby" placeholder="--Not Specified--" class="form-control">
                </div>
                <hr class="my-3">
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Course/Degree</span></label>
                    <input type="text" wire:model.defer="degree" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">School</label>
                    <input type="text" wire:model.defer="school" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Date Graduated</label>
                    <input type="text" wire:model.defer="dategraduated" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Awards Received</label>
                    <input type="text" wire:model.defer="awardsreceived" placeholder="--Not Specified--" class="form-control">
                </div>
                <div class="col-12 d-grid">
                    <button class="btn btn-primary mt-3">Update Informations</button>
                </div>
            </div>
        </form>
</div>
