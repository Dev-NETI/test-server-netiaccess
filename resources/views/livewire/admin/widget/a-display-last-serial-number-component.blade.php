<div class="row">
    <div class="col-lg-12 col-md-12 col-12 mt-3">
        <div class="card">
            <!-- Card header -->
            <div class="card-header card-header-height d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">LAST SERIAL NUMBER HISTORY</h4>
                </div>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <!-- List group -->
                <ul class="list-group list-group-flush list-timeline-activity">
                    @foreach($history_data as $data)
                    <li class="list-group-item px-0 pt-0 border-0 pb-6">
                        <div class="row position-relative">
                            <div class="col-auto">
                                <div class="icon-shape icon-md bg-light-dark text-danger rounded-circle">
                                    <i class="fe fe-check"></i>
                                </div>
                            </div>
                            <div class="col ms-n3">
                                <h4 class="mb-0 h5">{{$data->course->coursecode}} - {{$data->course->coursename}}</h4>
                                <p class="mb-0 text-body"><b>Full Name:</b> {{$data->enrolled->trainee->certificate_name()}}<br><b>Certificate Number</b> : {{$data->certificatenumber}}<br><b>Registration Number </b> : {{$data->registrationnumber}}<br><b>Serial Number</b> : {{$data->serial_name}} - {{$data->controlnumber}}</p>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted fs-6">{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>