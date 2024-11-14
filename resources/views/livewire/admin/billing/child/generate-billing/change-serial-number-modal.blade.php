<div wire:ignore class="modal fade" id="EditSerialNumber" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Serial Number:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="changeserial" wire:submit.prevent='changeserial()'>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon3">@php echo date('ym'); @endphp</span>
                        <input type="text" wire:model.defer='billingserialnumber' class="form-control" id="basic-url" aria-describedby="basic-addon3">
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="changeserial" class="btn btn-info" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
