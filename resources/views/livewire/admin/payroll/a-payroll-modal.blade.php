<div wire:ignore.self class="modal fade" id="assigncourse" tabindex="-1" role="dialog" aria-labelledby="assigncourse" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalScrollableTitle">ASSIGN COURSE</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form wire:submit.prevent="saveAssignedCourse">
                        @csrf
                        <div class="mt-2 col-lg-12">
                            <label class="form-label" for="">Assign course <small><i>(Assign a course to apply it to the payroll.)</i></small></label><br>
                            <select class="col-lg-12 form-select" wire:model.defer="course_id" required>
                                <option value="">Select option</option>
                                @foreach ($course_data as $course)
                                <option value="{{ $course->courseid }}">{{ strtoupper($course->FullCourseName) }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" type="button" class="btn btn-info">Add</button>
            </div>
            </form>
        </div>
    </div>
</div>