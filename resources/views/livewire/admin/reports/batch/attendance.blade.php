<div class="card mt-3 ">
    <div class="card-header">
        <h4>ATTENDANCE OF TRAINEES</h4> <br>
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </svg>
            <div>
                Attendance records are updated with the following codes: <b> P (PRESENT),
                    @if ($schedule->course->type->coursetypeid != 1)
                    A (ABSENT),
                    @endif
                    @if ($schedule->course->type->coursetypeid == 1)
                    N (NO SHOW) @endif
                    and C (CANCELLED) </b>

                to indicate the participation status of trainees. To update attendance, simply tick the check box accordingly to apply in the attendance.
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-sm table-bordered border-dark text-nowrap mb-0">
                <thead>
                    <tr class="sticky-tr">
                        <th>FULL NAME</th>
                        @foreach ($days as $day)
                        <th class="text-center sticky-th">
                            {{ $day['date_day'] }} <br>
                            ({{ $day['date'] }})
                        </th>
                        @endforeach
                        <th class="text-center">ACTION</th>
                        <th class="text-center">REMARKS</th>
                    </tr>
                </thead>
                <tbody class="sticky-tbody">
                    <!-- @php
                    $attendanceOptions = [
                    1 => 'PRESENT',
                    4 => 'ABSENT',
                    5 => 'CANCELLED',
                    6 => 'NO SHOW',
                    ];
                    @endphp -->

                    @php
                    $attendanceOptions = [
                    1 => 'PRESENT_AM',
                    2 => 'PRESENT_PM',
                    3 => 'ABSENT_AM',
                    4 => 'ABSENT_PM',
                    5 => 'CANCELLED',
                    6 => 'NO SHOW',
                    ];
                    @endphp

                    @foreach ($attendees as $attendee)
                    <tr class="sticky-tr">
                        <td>
                            <div class="lh-1 text-uppercase">

                                {{ $attendee->trainee->certificate_name() }}
                            </div>

                        </td>
                        @foreach ($days as $day)
                        <td>
                            <div class="d-flex justify-content-center  align-items-center gap-2">

                                @if (Auth::user()->u_type == 1)
                                <table class="table table-sm table-bordered border-dark mt-2">
                                    <th class="text-center" style="background-color: lightgreen;">AM</th>
                                    <th class="text-center" style="background-color: lightgreen;">PM</th>
                                    <tbody>
                                        <tr>
                                            @foreach (['present_am' => 'present_am', 'present_pm' => 'present_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'green' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    PRESENT
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>

                                        @if ($schedule->course->coursetypeid == 1)
                                        <tr>
                                           @foreach (['noshow_am' => 'noshow_am', 'noshow_pm' => 'noshow_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'red' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    NO SHOW
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @else
                                        <tr>
                                            @foreach (['absent_am' => 'absent_am', 'absent_pm' => 'absent_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'red' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    ABSENT
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endif
                                        <tr>
                                            @foreach (['cancel_am' => 'cancel_am', 'cancel_pm' => 'cancel_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'gold' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    CANCELLED
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach (['drop_am' => 'drop_am', 'drop_pm' => 'drop_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'maroon' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    DROP
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                    </tbody>

                                </table>

                                @else
                                <table class="table table-sm table-bordered border-dark mt-2">
                                    <th class="text-center" style="background-color: lightgreen;">AM</th>
                                    <th class="text-center" style="background-color: lightgreen;">PM</th>
                                    <tbody>
                                        <tr>
                                            @foreach (['present_am' => 'present_am', 'present_pm' => 'present_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'green' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    PRESENT
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>

                                        @if ($schedule->course->coursetypeid == 1)
                                        <tr>
                                           @foreach (['noshow_am' => 'noshow_am', 'noshow_pm' => 'noshow_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'red' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    NO SHOW
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @else
                                        <tr>
                                            @foreach (['absent_am' => 'absent_am', 'absent_pm' => 'absent_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'red' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    ABSENT
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endif
                                        <tr>
                                            @foreach (['cancel_am' => 'cancel_am', 'cancel_pm' => 'cancel_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'gold' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    CANCELLED
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach (['drop_am' => 'drop_am', 'drop_pm' => 'drop_pm'] as $option => $key)
                                            <td style="background-color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'maroon' : 'white' }}; color: {{ isset($attendanceData[$attendee['traineeid']][$day['date']][$key]) && $attendanceData[$attendee['traineeid']][$day['date']][$key] == 1 ? 'white' : 'black' }}">
                                                <input type="checkbox" name="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Attendance" id="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}" value="1" wire:model="attendanceData.{{ $attendee['traineeid'] }}.{{ $day['date'] }}.{{ $key }}" wire:click="updateAttendance()">
                                                <label class="form-check-label" for="{{ $attendee['traineeid'] }}-{{ $day['date'] }}-{{ strtolower($option) }}-Checkbox}}">
                                                    DROP
                                                </label>
                                            </td>
                                            @endforeach
                                        </tr>
                                    </tbody>

                                </table>
                                @endif

                            </div>

                        </td>
                        @endforeach

                        <td class="text-center">
                        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                            @if ($attendee->passid != 1)
                            <button class="btn btn-sm btn-warning mt-1" data-bs-toggle="modal" wire:click="openModal({{ $attendee->traineeid }}, {{ $schedule->scheduleid }})" data-bs-target="#generateModal">REMEDIAL</button>
                            <br>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" wire:click="openModal({{ $attendee->traineeid }}, {{ $schedule->scheduleid }})" data-bs-target="#generateModalFailed">FAILED</button>
                            <br>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" wire:click="openModal({{ $attendee->traineeid }}, {{ $schedule->scheduleid }})" data-bs-target="#generateModalSuccess">PASSED</button>
                            @endif
                            </div>
                        
                        </td>
                        <td class="text-center">
                            @if ($attendee->passid == 1)
                            <h5 class="text-success">PASSED</h5>
                            @elseif ($attendee->passid == 2)
                            <h5 class="text-danger">FAILED</h5>
                            @elseif ($attendee->pendingid == 3)
                            <h5 class="text-warning">REMEDIAL</h5>
                            @else
                            <h5 class="text-info">TBA</h5>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>


            <div wire:ignore.self class="modal fade" id="generateModalSuccess" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Confirmation of Passing</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="setPassed" id="setPassed">
                                @csrf
                                <div class="row gx-3">
                                    <div class="alert alert-success d-flex align-items-center" role="alert">
                                        <div>
                                            <h5>NAME: {{$name}}</h5>

                                            Are you sure you want to mark the record as passed? Click "Proceed" to confirm and process the passed action.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="setPassed" class="btn btn-primary">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>


            <div wire:ignore.self class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Confirmation of Remedial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="setRemedial" id="setRemedial">
                                @csrf
                                <div class="row gx-3">
                                    <!-- Warning alert -->
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                        <div>
                                            <h5>NAME: {{$name}}</h5>

                                            Are you sure you want to mark the record as remedial? Click "Proceed" to confirm and process the remedial action.
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="setRemedial" class="btn btn-primary">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="generateModalFailed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Confirmation of Failed</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="setFailed" id="setFailed">
                                @csrf
                                <div class="row gx-3">
                                    <!-- Warning alert -->
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                        <div>
                                            <h5>NAME: {{$name}}</h5>

                                            Are you sure you want to mark the record as Failed? Click "Proceed" to confirm and process the Failed action.
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="setFailed" class="btn btn-primary">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>