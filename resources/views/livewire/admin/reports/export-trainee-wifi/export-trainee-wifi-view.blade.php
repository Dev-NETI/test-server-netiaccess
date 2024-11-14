<x-card-layout cardTitle="Wi-Fi Password" cardDescription="Export Trainee's WiFi password here.">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="offset-md-4 col-md-4">
                <label>Select Schedule</label>
                <select class="form-control" wire:model="scheduleDropdown">
                    <option value="">Select</option>
                    @foreach ($scheduleData as $item)
                         <option value="{{ $item->batchno }}">{{ $item->batchno }}</option>
                    @endforeach 
                </select>
        </div>

        <div class="col-md-12 row">
                @if (count($traineeData) > 0)
                    <div class="col-md-12 mt-5 table-responsive">
                            <button class="btn btn-success float-end mb-2" wire:click="download">Export</button>
                            <x-table>
                                    <thead>
                                            <x-th>Course</x-th>
                                            <x-th>Trainee</x-th>
                                            <x-th>Company</x-th>
                                            <x-th>Contact</x-th>
                                            <x-th>Username</x-th>
                                            <x-th>Password</x-th>
                                            <x-th>Expiration</x-th>
                                    </thead>
                                    <tbody>
                                            @foreach ($traineeData as $data)
                                                <tr>
                                                    <x-td>{{ $data->schedule->course->coursecode }}</x-td>
                                                    <x-td>{{ $data->trainee->full_name }}</x-td>
                                                    <x-td>{{ $data->trainee->company->company }}</x-td>
                                                    <x-td>
                                                        {{ $data->trainee->mobile_number }}</br>
                                                        {{ $data->trainee->email }}
                                                    </x-td>
                                                    <x-td>{{ $data->wifi_username }}</x-td>
                                                    <x-td>{{ $data->wifi_password }}</x-td>
                                                    <x-td>{{ $data->wifi_expiration }}</x-td>
                                                </tr>
                                            @endforeach
                                    </tbody>
                            </x-table>
                    </div>
                @endif
        </div>
    </div>
</x-card-layout>
