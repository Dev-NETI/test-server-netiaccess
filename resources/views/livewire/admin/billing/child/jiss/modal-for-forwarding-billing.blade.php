<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalForwarding" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Forward a Manual Billing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formSub" action="" wire:submit.prevent='formSub' enctype="multipart/form-data">
                        @csrf
                        <input type="file" accept="application/pdf" wire:model="jissFile" class="form-control">
                        @error($jissFile)
                        <span class="text-danger">{{$message}}</span>
                        @enderror

                        <select name="" id="" wire:model="status" class="form-select mt-3">
                            <option value="">Select Board</option>
                            <option value="4">Client Confirmation Board</option>
                            <option value="8">Transaction Close Board</option>
                        </select>
                        @error($status)
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer p-2 ms-1">
                    <div class="col-lg-12 d-grid p-2">
                        <button form="formSub" class="btn btn-primary" type="submit">Forward</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>