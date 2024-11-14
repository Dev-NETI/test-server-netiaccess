<div wire:ignore.self class="modal fade" id="ExportModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Export</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="export" wire:submit.prevent="export">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-12">
                            <label class="{{$errors->has('date_from') ? 'text-danger':''}}">Date from</label>
                            <input type="date" class="form-control {{$errors->has('date_from') ? 'is-invalid':''}}" wire:model="date_from">
                            @error('date_from') <small class="text-danger">{{$message}}</small>  @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="{{$errors->has('date_from') ? 'text-danger':''}}">Date from</label>
                            <input type="date" class="form-control {{$errors->has('date_to') ? 'is-invalid':''}}" wire:model="date_to">
                            @error('date_to') <small class="text-danger">{{$message}}</small>  @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="export" class="btn btn-success">Download</button>
            </div>
        </div>
    </div>
</div>
