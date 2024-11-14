<div wire:ignore.self class="modal fade" id="assigndescription" tabindex="-1" role="dialog" aria-labelledby="assigndescription" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalScrollableTitle">ASSIGN DESCRIPTION</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form wire:submit.prevent="saveAssignedDescription">
                        @csrf
                        <div class="mt-2 col-lg-12">
                            <label class="form-label" for="">Assign description <small><i>(Assign a description to apply it to the payroll.)</i></small></label><br>
                            <select class="col-lg-12 form-select" wire:model.defer="description_id">
                                <option value="">Select option</option>
                                @foreach ($description_data as $data)
                                <option value="{{ $data->id }}">{{ strtoupper($data->description) }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" type="button" class="btn btn-info">Add</button>
            </div>
            </form>
        </div>
    </div>
</div>