<tr>
    <td>{{ $index }}</td>
    <td>{{ $trainee->scheduleid }}</td>
    <td>{{ $trainee->remedial_sched == NULL ? '-' :
        $trainee->remedial_sched}}</td>
    <td>{{ $trainee->traineeid }}</td>
    <td>{{ $trainee->enroledid }}</td>
    <td>{{ $trainee->trainee->l_name }}, {{ $trainee->trainee->f_name }} {{ $trainee->trainee->m_name }}
        {{ $trainee->trainee->suffix }}</td>
    <td>
        {{-- <input type="text" wire:model="vessel"> --}}
        <select wire:model="selectedvessel">
            <option value="">Select Vessel</option>
            @foreach ($loadvessels as $item)
            <option value="{{ $item->id }}">{{ $item->vesselname }}</option>
            @endforeach
        </select>
        <small class="text-success">{{ $request_message }}</small>
    </td>
    <td>{{ $trainee->trainee->rank->rankacronym }}</td>
    <td>{{ $trainee->companyid != NULL ? $trainee->enroledcompany->company : $trainee->trainee->company->company }}</td>
    <td>{{ $trainee->trainee->nationality ? $trainee->trainee->nationality->nationality : 'Not Specified' }}</td>
    {{-- @dd($trainee) --}}
    @if ($trainee->istransferedbilling)
    <td style="min-width: 12em;">{{ $trainee->transfer->serialnumber }}</td>
    {{-- <td style="min-width: 12em;">{{ $trainee->billingserialnumber }}</td> --}}
    @else
    <td style="min-width: 12em;">{{ $trainee->billingserialnumber }}</td>
    @endif
    <td>
        {!!$trainee->enrollment_status!!}
    </td>
    @if ($trainee->courseid == 92)
    <td>
        <input type="checkbox" name="" {{ $trainee->discount ? 'checked': '' }}
        wire:click="discounted({{$trainee->enroledid}}, {{$trainee->discount ? 0: 1}})" class="form-check-input"
        id="">
    </td>
    @endif
    <td id="hoverA">
        {!!$trainee->download_dorm_reg_form!!}
        @can('authorizeAdminComponents', 126)
        <a class="h5" href="#" wire:click="markAsBilled({{$trainee->enroledid}})" title="Mark as billed"><i
                class="bi bi-bookmark-check-fill"></i></a>
        @endcan
        @can('authorizeAdminComponents', 127)
        <a href="#" class="h5" wire:click="seeData({{$trainee->enroledid}})" title="See Details"><i
                class="bi bi-eye-fill"></i></a>
        @endcan
        @if ($trainee->istransferedbilling == 0 && $trainee->billingstatusid == 2)
        @can('authorizeAdminComponents', 158)
        <a href="#" class="h5" wire:click="openModalTransfer({{$trainee->enroledid}})" title="Transfer billing"><i
                class="bi bi-arrow-up-right-square-fill"></i></a>
        @endcan
        @endif

    </td>
</tr>