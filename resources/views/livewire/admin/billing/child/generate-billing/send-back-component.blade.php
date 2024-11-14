<li class="list-group-item">
    <a class="d-flex justify-content-between align-items-center text-inherit text-decoration-none"
        data-bs-toggle="modal" data-bs-target="#revisionModal">
        <div class="text-truncate">
            <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                    class="bi bi-arrow-left"></i></span>
            <span>{{ $title }}</span>
        </div>
    </a>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Send Back</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sendBack" wire:submit.prevent="sendBack">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label class="float-start">Revisions</label>
                            </div>
                            <div class="col-md-12" style="{{ $errors->has('revision') ? 'border: 1px solid red;' : '' }}">
                                <div wire:ignore>
                                    <textarea class="form-control text-start" id="description" wire:model="revision"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @error('revision')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="sendBack" class="btn btn-primary">Send Back</button>
                </div>
            </div>
        </div>
    </div>

</li>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                height: 300, // Set your preferred height
                callbacks: {
                    // Update Livewire property when Summernote content changes
                    onChange: function(contents) {
                        @this.set('revision', contents);
                    }
                }
            });
        });
    </script>
@endpush
