<div wire:ignore.self class="modal right fade" id="settings_modal_atd" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">SETTINGS</h2>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save_settings">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <label for="" class="form-label">ADDRESS TO:</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="input_address">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="" class="form-label mt-2">DEPARTMENT:</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="input_department">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="" class="form-label mt-2">COMPANY:</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="input_company">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="" class="form-label mt-2">Location (line 1):</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="input_location1">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="" class="form-label mt-2">Location (line 2):</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="input_location2">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="" class="form-label mt-2">MAXIMUM LINE FOR BODY:</label>
                            <div class="input-group me-3">
                                <input class="form-control active"
                                    type="text" placeholder="input the here"
                                    aria-describedby="basic-addon2" wire:model.defer="rowstart">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success">SAVE</button>
            </div>
        </div>
    </div>
    </form>
</div>
