<div class="col-md-4 offset-md-8 text-start">
    <x-request-message />
    <form wire:submit.prevent="store">
        @csrf

            <label>Enter Email</label>
            <div class="input-group">
                <div class="input-group-append">
                    <input type="text" wire:model="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                </div>
                <button class="btn btn-primary float-end">Save</button>
            </div>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            
    </form>

</div>
