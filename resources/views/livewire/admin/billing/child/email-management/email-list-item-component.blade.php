<tr>
    <td>
        @if ($is_edit == $data->id)
            <input wire:model="data.email">
            @error('data.email')
                <small class="text-danger">{{$message}}</small>
            @enderror
        @else
            {{$data->email}}
        @endif
    </td>
    <td>
        @if ($is_edit == $data->id)
            <button class="btn btn-sm btn-primary" wire:click="store()">Save</button>
            <button class="btn btn-sm btn-danger" wire:click="close()">Close</button>
        @else
            <button class="btn btn-sm btn-success" wire:click="edit({{$data->id}})">Edit</button>
        @endif
        
    </td>
</tr>