<x-table-list title="Scanned Info List" :data="$busData" wire:model="search">
    <thead class="table-light hover">
        <tr>
            <x-th>#</x-th>
            <x-th>Fullname</x-th>
            <x-th>Phone Number</x-th>
            <x-th>Training</x-th>
            <x-th>Chance Passenger?</x-th>
            <x-th>Passenger Count</x-th>
            <x-th>Scanned By</x-th>
            <x-th>Date Created</x-th>
        </tr>
    </thead>
    <tbody>
        @php
        $x = 1;
        @endphp
        @if (count($busData) > 0)
        @foreach ($busData as $data)
        <tr>
            <x-td>{{ $x }}</x-td>
            <x-td>{{ $data->enroled->trainee->name_for_meal}}</x-td>
            <x-td>{{ $data->enroled->trainee->mobile_number }}</x-td>
            <x-td>{{ $data->enroled->course->full_course_name }}
            </x-td>
            <x-td>{{$data->chance_passenger_desc}}</x-td>
            <x-td>{{$data->divideto}}</x-td>
            <x-td>{{$data->scanned_by}}</x-td>
            <x-td>{{ $data->created_at }}</x-td>
        </tr>
        @php
        $x++;
        @endphp
        @endforeach
        @else
        <tr class="text-center">
            <x-td colspan="6">No Records Found</x-td>
        </tr>
        @endif
    </tbody>
</x-table-list>