<tr @if($data->regular < 8 && $data->regular != null) style="background-color: rgba(253, 203, 110,1.0); color: black;" @endif>
        <x-td>
            <div class="btn-group" role="group">
                <button id="" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu" aria-labelledby="">
                    <button class="dropdown-item" wire:click="show_modal({{ $data->id }})" data-bs-toggle="modal" data-bs-target="#assigncourse"><i class="bi bi-plus" style="font-size: 1em;"></i>&nbsp;Assign Course</button>
                    <button class="dropdown-item" wire:click="show_modal_attendance({{ $data->id }})" data-bs-toggle="modal" data-bs-target="#edit_attendance"><i class="bi bi-gear" style="font-size: 1em;"></i>&nbsp;Edit Attendance</button>
                    <button class="dropdown-item" wire:click="clear_timeout({{$data->id}})"><i class="bi bi-backspace-fill" style="font-size: 1em;"></i>&nbsp;Clear time out</button>
                    <button class="dropdown-item" wire:click="delete_attendance({{ $data->id }})"><i class="bi bi-trash" style="font-size: 1em;"></i>&nbsp;Delete Attendance</button>
                </div>
            </div>
        </x-td>
        <x-td>{{ $data->created_date }}</x-td>
        <x-td>{{ \Carbon\Carbon::parse($data->created_date)->format('l') }}</x-td>
        <x-td>{{ $data->user->user_id }}</x-td>
        <x-td>{{ strtoupper($data->user->instructor_name) }}</x-td>
        <x-td>{{ optional($data->course)->FullCourseName ? $data->course->FullCourseName : 'No Assignment Course' }}</x-td>
        @if($data->time_in)
        <x-td>{{ $data->time_in }}</x-td>
        @else
        <x-td class="text-danger">--NO TIME IN--</x-td>
        @endif
        @if($data->time_out)
        <x-td>{{ $data->time_out }}</x-td>
        @else
        <x-td class="text-danger">--NO TIME OUT--</x-td>
        @endif
        <x-td>{{ $data->time_type }}</x-td>
        <x-td>{{ $data->regular }}</x-td>
        <x-td>{{ $data->late }}</x-td>
        <x-td>{{ $data->undertime }}</x-td>
        <x-td>{{ $data->overtime }}</x-td>
        <x-td>{{ $data->log_status }}</x-td>
        <x-td>{{ $data->modified_by }}</x-td>
        <x-td>{{ $data->created_at }}</x-td>
</tr>