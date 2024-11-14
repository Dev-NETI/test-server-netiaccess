<div wire:ignore.self class="modal fade" id="movetomodal" tabindex="-1" role="dialog" aria-labelledby="movetomodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalScrollableTitle">MOVE TO OTHER PAYROLL</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form wire:submit.prevent="saveSchedule">
                        @csrf
                        <div class="mt-2 col-lg-12">
                            <label class="form-label">SELECT A WEEK: <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="selectedWeek">
                                <option value="">----- Select Week -----</option>
                                @foreach($this->getWeekOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
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