<div wire:ignore.self class="modal fade assign" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Assign Instructor, Assessor and Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="update_training" class="row">
                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign instructor:</label>
                        <select class="form-control" wire:model.defer="selected_instructor">
                            @if ($s_course && $s_course->type)
                            @if ($s_course->type->coursetypeid == 1)
                            <option value="">Select Instructor</option>
                            @if ($instructors_man)
                            @foreach ($instructors_man as $ins)
                            @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow)
                                <option value="{{$ins->instructorlicense}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>

                                @endif
                                @endforeach
                                @endif

                                @else
                                <option value="">Select Instructor</option>
                                @foreach ($instructors as $ins)
                                @if ($ins->user)
                                <option value="{{$ins->user->user_id}}">{{$ins->user->formal_name()}} </option>
                                @endif
                                @endforeach
                                @endif
                                @endif
                        </select>
                        @error('selected_instructor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign alternate instructor:</label>
                        <select class="form-control" wire:model.defer="selected_a_instructor">
                            @if ($s_course && $s_course->type)
                            @if ($s_course->type->coursetypeid == 1)
                            <option value="">Select Instructor</option>
                            @if ($instructors_man)
                            @foreach ($instructors_man as $ins)
                            @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow)
                                <option value="{{$ins->instructor->user->user_id}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                                @endif
                                @endforeach
                                @endif

                                @else
                                <option value="">Select Instructor</option>
                                @foreach ($instructors as $ins)
                                @if ($ins->user)
                                <option value="{{$ins->user->user_id}}">{{$ins->user->formal_name()}} </option>
                                @endif
                                @endforeach
                                @endif
                                @endif
                        </select>
                        @error('selected_instructor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    @if ($s_course && $s_course->type)
                    @if ($s_course->type->coursetypeid == 1)

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign assessor:</label>
                        <select class="form-control" wire:model.defer="selected_assessor">
                            <option value="">Select assessor</option>
                            @if ($assessor_man)
                            @foreach ($assessor_man as $ins)
                            @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow )
                                <option value="{{$ins->instructorlicense}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                                @endif
                                @endforeach
                                @endif
                        </select>
                        @error('selected_assessor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign alternate assessor:</label>
                        <select class="form-control" wire:model.defer="selected_a_assessor">
                            <option value="">Select assessor</option>
                            @if ($assessor_man)
                            @foreach ($assessor_man as $ins)
                            @if ($ins && $ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow)
                                <option value="{{$ins->instructor->user->user_id}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                                @endif
                                @endforeach
                                @endif
                        </select>
                        @error('selected_assessor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    @endif
                    @endif

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign room:</label>
                        <select class="form-control" wire:model.defer="selected_room">
                            @foreach ($rooms as $room)
                            <option value="{{$room->roomid}}">{{$room->room}}</option>
                            @endforeach
                        </select>
                        @error('selected_room')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                    <div class=" col-12 col-md-12">
                        <div class="float-end">
                            <button class="btn btn-success">Update the schedule</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>