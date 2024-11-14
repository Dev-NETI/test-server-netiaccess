<tr>
    <td>
        @if ($client->label == 0)
        {!! $client->client_information !!}
        @else
        @php
        $clientinfo = json_decode($client->client_information);
        // dd($clientinfo);
        @endphp
        <span class="h6">{{$clientinfo->recipient}}</span> <br>
        {{$clientinfo->designation}} <br>
        <span class="h6">{{$clientinfo->companyname}}</span> <br>
        {{$clientinfo->addressline1}} <br>
        {{$clientinfo->addressline2}} <br>
        @endif
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-success" wire:click="edit({{ $client->id }})">
            Edit
        </button>
        <button type="button" class="btn btn-sm btn-success" wire:click="delete({{ $client->id }})">
            Delete
        </button>
    </td>
</tr>
@push('scripts')
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
@endpush