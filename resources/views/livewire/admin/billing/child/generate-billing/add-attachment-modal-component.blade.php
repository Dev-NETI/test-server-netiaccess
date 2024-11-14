<div wire:ignore.self class="modal fade" id="uploadAttachmentModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="upload">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12 mt-3">
                            <label class="float-start">Title</label>
                            <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                wire:model.defer="title">
                            @error('title')
                            <small class="text-danger float-start">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="float-start">Attachment type</label>
                            <select class="form-control {{ $errors->has('attachment_type') ? 'is-invalid' : '' }}"
                                wire:model="attachment_type">
                                <option value="">Select</option>
                                @foreach ($attachmenttype_data as $attachtype)
                                <option value="{{ $attachtype->id }}" {{-- {{$billingstatusid !=7 && $attachtype->id ===
                                    2 ? 'disabled':''}}
                                    {{$billingstatusid != 8 && $attachtype->id === 3 ? 'disabled':''}} --}}
                                    >
                                    {{ $attachtype->attachmenttype }}
                                </option>
                                @endforeach
                            </select>
                            @error('attachment_type')
                            <small class="text-danger float-start">{{ $message }}</small>
                            @enderror
                        </div>

                        @if ($is_OR_selected == 1)
                        <div class="col-md-12 mt-3">
                            <label class="float-start">O.R #</label>
                            <input type="text" class="form-control {{ $errors->has('OR_Number') ? 'is-invalid' : '' }}"
                                wire:model="OR_Number">
                        </div>
                        @error('OR_Number')
                        <small class="text-danger float-start">{{ $message }}</small>
                        @enderror
                        @endif

                        <div class="col-md-12 mt-3">
                            <label class="float-start">Choose file</label>
                            <input type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                                wire:model.defer="file">
                            @error('file')
                            <small class="text-danger float-start">{{ $message }}</small>
                            @enderror
                        </div>
                        <span wire:loading wire:target='file'><small class="text-success">Uploading...</small></span>
                        <div class="col-md-12 mt-3 ">
                            <button type="submit" wire:target="file" wire:loading.class="disabled"
                                class="btn btn-primary">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>