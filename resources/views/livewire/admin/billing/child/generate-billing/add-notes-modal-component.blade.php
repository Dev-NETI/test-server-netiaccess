<div wire:ignore.self class="modal fade" id="AddNotesModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add your Comment/Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <label for="commentTxt" class="form-label text-left">Enter here</label>
                <textarea type="text" name="" id="commentTxt" wire:model.lazy="commentTxt" class="form-control"
                    placeholder="Enter your comment/notes here.."></textarea>
            </div>
            <div class="modal-footer p-0">
                <div class="col-lg-12 gap-2 d-grid p-2 m-0">
                    @if (!empty($existedNote))
                    <button type="button" wire:click="removeComments" class="btn btn-sm btn-danger">Remove Existing
                        Comments</button>
                    @endif
                    <button type="button" wire:click="saveComments" class="btn btn-sm btn-primary">Add</button>
                </div>

            </div>
        </div>
    </div>
</div>