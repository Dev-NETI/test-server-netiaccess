<div class="container mt-5">
    <div class="card text-center">

        <div class="card-header">
            <h1 class="card-title">Client Information</h1>
        </div>

        <div class="card-body">
            {{-- <form wire:submit.prevent="{{ $is_edit == 1 ? 'update' : 'store' }}"> --}}
                {{-- @csrf --}}
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <label class="float-center h2" for="title">Enter Client Information</label>
                    </div>
                    <div class="col-md-6 offset-md-3 text-start row">
                        {{-- <textarea id="billingReceiver" wire:model="client_info"></textarea> --}}
                        <form id="submitform" action="" wire:submit.prevent="{{$is_edit == 1 ? 'update' : 'forminfo'}}">
                            @csrf
                            <div class="col-12 mt-2">
                                <label for="">Recipient Name</label>
                                <input type="text" wire:model.debounce.250ms="recN" class="form-control" required>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Designation</label>
                                <input type="text" wire:model.debounce.250ms="desig" class="form-control" required>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Company Name</label>
                                <input type="text" wire:model.debounce.250ms="compName" class="form-control" required>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Address Line 1</label>
                                <input type="text" wire:model.debounce.250ms="addl1" class="form-control" required>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Address Line 2</label>
                                <input type="text" wire:model.debounce.250ms="addl2" class="form-control" required>
                            </div>
                        </form>
                    </div>

                    @if ($is_edit == 1)
                    <div class="col-md-6 offset-md-3 mt-3">
                        <label class="float-start">Select Email Recipient</label><br />
                        <div class="row">
                            @foreach ($company_email_data as $data)
                            <livewire:admin.billing.child.clientinfo.select-email-component :email="$data->email"
                                email_id="{{ $data->id }}" client_info_id="{{Session::get('clientInfoId')}}" />
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6 offset-md-3">
                        @error('client_info')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <button type="submit" form="submitform" class="btn btn-primary mt-3 float-end">Save</button>
                    </div>
                </div>
                {{--
            </form> --}}
        </div>

        <div class="card-footer text-body-secondary">
        </div>
    </div>
</div>

{{-- @push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
            $(document).ready(function() {
                $('#billingReceiver').summernote({
                    height: 300, // Set your preferred height
                    callbacks: {
                        // Update Livewire property when Summernote content changes
                        onChange: function(contents) {
                            @this.set('client_info', contents);
                        }
                    }
                });
            });
        })
</script>
@endpush --}}