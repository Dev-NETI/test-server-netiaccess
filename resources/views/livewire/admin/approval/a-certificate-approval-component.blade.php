<!-- Container fluid -->
<section class="container-fluid p-4">
    <div class="row">
        <div class="py-12">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-12 px-lg-10 mb-8">
                        <!-- text -->

                        <!-- heading -->

                        <h2 class="h1 fw-bold mt-3">Certificate Approval ({{$course_type->coursetype}})
                        </h2>
                        <h4>{{$formattedDate}}</h4>
                        <!-- text -->

                        <p class="mb-0 fs-4">Here you can monitor the status of Certificate .</p>

                    </div>
                </div>

                <div class="row row-cols-1 row-cols-4 g-4">
                    <div class="col-md-6">
                        <div class="alert alert-info shadow-xl" role="alert">
                            <h4 class="alert-heading">Certificate Approval Needed</h4>
                            <p>Please review the following list of batch numbers that are ready for certificate approval:</p>
                            @foreach($countPerWeek as $key => $data)
                            @if($data->count == 1)
                            <li>{{$data->batchno}} <i>({{$data->count}} Certificate)</i>
                            </li>
                            @else
                            <li>{{$data->batchno}} <i>({{$data->count}} Certificates)</i>
                            </li>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info shadow-xl" role="alert">
                            <h4 class="alert-heading">Certificate Releasing Needed</h4>
                            <p>Please review the following list of batch numbers that are ready for certificate releasing:</p>
                            @foreach($countPerWeekReleasing as $key => $data)
                            @if($data->count == 1)
                            <li>{{$data->batchno}} <i>({{$data->count}} Certificate)</i>
                            </li>
                            @else
                            <li>{{$data->batchno}} <i>({{$data->count}} Certificates)</i>
                            </li>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.cert-approval-view', ['course_type_id' => $this->course_type_id ]) }}" class="card-img-top"><img src="{{ asset('assets/images/oesximg/pdecover/assessment-blue.svg') }}" alt="" class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Step 1</span>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.cert-approval-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">For Approval Certificates</a> <button type="button" class="btn btn-info badge bg-info text-white" data-bs-toggle="tooltip" data-placement="top" title="Approval Certificate: {{$count_sched}}">
                                        {{$count_sched}}
                                    </button>
                                </h4>
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.cert-approval-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.cert-releasing-view', ['course_type_id' => $this->course_type_id ]) }}" class="card-img-top"><img src="{{ asset('assets/images/oesximg/pdecover/certificate-yellow.svg') }}" alt="" class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Step 2</span>
                                    <a href="{{ route('a.cert-releasing-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.cert-releasing-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">For Releasing Certificates</a> <button type="button" class="btn btn-info badge bg-info text-white" data-bs-toggle="tooltip" data-placement="top" title="Releasing Certificate: {{$count_releasing}}">
                                        {{$count_releasing}}
                                    </button>
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.cert-releasing-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.cert-invalid-view', ['course_type_id' => $this->course_type_id ]) }}" class="card-img-top"><img src="{{ asset('assets/images/oesximg/pdecover/history-green.svg') }}" alt="" class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Logs</span>
                                    <a href="{{ route('a.cert-invalid-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.cert-invalid-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">Certificate Invalid</a> <button type="button" class="btn btn-info badge bg-info text-white" data-bs-toggle="tooltip" data-placement="top" title="invalid Certificate: {{$count_invalid}}">
                                        {{$count_invalid}}
                                    </button>
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.cert-invalid-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.cert-released-view', ['course_type_id' => $this->course_type_id ]) }}" class="card-img-top"><img src="{{ asset('assets/images/oesximg/pdecover/history-green.svg') }}" alt="" class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Logs</span>
                                    <a href="{{ route('a.cert-released-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.cert-released-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">Certificate Released</a> <button type="button" class="btn btn-info badge bg-info text-white" data-bs-toggle="tooltip" data-placement="top" title="Released Certificate: {{$count_released}}">
                                        {{$count_released}}
                                    </button>
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.cert-released-view', ['course_type_id' => $this->course_type_id ]) }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>