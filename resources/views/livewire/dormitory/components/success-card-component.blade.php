<div class="col-md-8 offset-md-2">
    <!-- Card -->
    <div class="card mb-4">
        <!-- Card body -->
        <div class="card-body">
            <div class="text-end">
                <span class="badge rounded-pill bg-success">Check Out success!</span>
            </div>
            <div class="text-center">
                <img src="{{ $enroled_data->trainee->public_image_path }}" class="rounded-circle avatar-xl mb-3"
                    alt="{{ $enroled_data->trainee->public_image_path }}">
                <h4 class="mb-0">{{ $enroled_data->trainee->name_for_meal }}</h4>
                <p class="mb-0">{{ $enroled_data->trainee->rank->rankacronym }}</p>
            </div>
            <div class="d-flex justify-content-between border-bottom py-2 mt-4">
                <span>Course</span>
                <span class="text-dark">{{ $enroled_data->schedule->course->full_course_name }}</span>
            </div>
            <div class="d-flex justify-content-between border-bottom py-2">
                <span>Training Date</span>
                <span class="text-warning">
                    {{ $enroled_data->schedule->training_date }}
                </span>
            </div>
            <div class="d-flex justify-content-between pt-2">
                <span>Company</span>
                <span class="text-dark">{{ $enroled_data->trainee->company->company }}</span>
            </div>
        </div>
    </div>
</div>
