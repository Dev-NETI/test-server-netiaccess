<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalIframePDF" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Billing Statement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <iframe style="height: 80vh;" src="{{$this->fileURL}}" frameborder="0" width="100%"></iframe>
                </div>
                <div class="modal-footer p-2 ms-1">
                    <div class="col-lg-12 d-grid p-2">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>