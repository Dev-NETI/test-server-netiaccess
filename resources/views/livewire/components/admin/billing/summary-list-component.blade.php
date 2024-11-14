<x-table-list wire:model.debounce.500ms="search" :data="$schedules" :total="$t_allschedules">
    <thead>
        <tr>
            <x-th scope="col">Course</x-th>
            <x-th scope="col">Training Date</x-th>
            <x-th scope="col">Company</x-th>
            @if ($billingstatusid > 1)
                <x-th scope="col">Serial Number</x-th>
            @endif
            @if (Auth::user()->u_type !== 3)
                <x-th>
                    Modified By
                </x-th>
                <x-th>
                    Date Modified
                </x-th>
                <x-th>
                    No. of Trainees
                </x-th>
            @endif
            <x-th scope="col">Action</x-th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($schedules))
            @foreach ($schedules as $schedule)
                @if ($schedule->deletedid == 0)
                    <tr>
                        <x-td>{{ $schedule->coursecode }}</x-td>
                        <x-td>{{ $this->formatTrainingDate($schedule->startdateformat, $schedule->enddateformat) }}
                        </x-td>
                        <x-td>{{ $schedule->company }}</x-td>
                        @if ($billingstatusid > 1)
                            <x-td>
                                @php
                                    $sN_data = $this->getSerialNumber($schedule->scheduleid, $schedule->companyid);
                                @endphp
                                @foreach ($sN_data as $data)
                                    <small class="text-info">{{ $data }}</small>
                                @endforeach
                            </x-td>
                        @endif
                        @if (Auth::user()->u_type !== 3)
                            <x-td>
                                {{ $schedule->billing_modified_by }}
                            </x-td>
                            <x-td>
                                {{ $schedule->billing_updated_at }}
                            </x-td>
                            <x-td>
                                {{ $this->countTrainee($schedule->scheduleid, $schedule->companyid) }}
                            </x-td>
                        @endif
                        <x-td>
                            <a wire:click="passSessionData({{ $schedule->scheduleid }},{{ $schedule->companyid }})"
                                class="btn btn-primary" title="View"><i class="bi bi-eye"></i></a>

                            @can('authorizeAdminComponents', 128)
                                @if ($billingstatusid < 3)
                                    <a wire:click="showModal({{ $schedule->scheduleid }},{{ $schedule->companyid }})"
                                        class="btn btn-success" title="View"><i class="bi bi-skip-forward-fill"></i></a>
                                @endif
                            @endcan

                            @can('authorizeAdminComponents', 142)
                                @if ($billingstatusid == 1)
                                    <button wire:click="archive({{ $schedule->scheduleid }},{{ $schedule->companyid }})"
                                        class="btn btn-danger"><i class="bi bi-archive"></i></button>
                                @endif
                            @endcan
                        </x-td>

                    </tr>
                @endif
            @endforeach

            <x-billingmodal id="forwardToModal" position="modal-dialog-centered" function="forwardSave"
                saveBtnTitle="Forward" width="modal-lg" modalTitle="Forward Billing"></x-billingmodal>
        @else
            <p>No schedules found.</p>
        @endif
    </tbody>

</x-table-list>
