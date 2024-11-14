<!-- Page Header -->
@include('livewire.components.data-privacy-modal')
<!-- Container fluid -->
<section class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-lg-flex justify-content-between align-items-center">
                <div class="mb-3 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">NETI ACCOUNT</span>
                        </div>
                        <div>
                            <span class="fe fe-shopping-bag fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ number_format($employee) }}
                    </h2>
                    <!-- <span class="text-success fw-semibold"><i class="fe fe-trending-up me-1"></i>+20.9$</span>
                    <span class="ms-1 fw-medium">Number of sales</span> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">Trainees</span>
                        </div>
                        <div>
                            <span class=" fe fe-users fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ number_format($trainees->count()) }}
                    </h2>
                    <!-- <span class="text-success fw-semibold"><i class="fe fe-trending-up me-1"></i>+1200</span>
                    <span class="ms-1 fw-medium">Students</span> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">Instructor</span>
                        </div>
                        <div>
                            <span class=" fe fe-user-check fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ number_format($instructor) }}
                    </h2>
                    <!-- <span class="text-success fw-semibold"><i class="fe fe-trending-up me-1"></i>+200</span>
                    <span class="ms-1 fw-medium">Instructor</span> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">Courses</span>
                        </div>
                        <div>
                            <span class=" fe fe-book-open fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ number_format($courses->count()) }}

                    </h2>
                    <!-- <span class="text-danger fw-semibold">120+</span>
                    <span class="ms-1 fw-medium">Number of pending</span> -->
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-xl-8 col-lg-12 col-md-12 col-12">
            <div class="card mb-4">
                <div
                    class="card-header align-items-center card-header-height d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">TRAINEE'S ENROLLED</h4>
                    </div>
                    <div>
                        <div class="dropdown dropstart">
                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button"
                                id="courseDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="courseDropdown1">
                                <span class="dropdown-header">Settings</span>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-external-link dropdown-item-icon "></i>Export</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-mail dropdown-item-icon "></i>Email
                                    Report</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-download dropdown-item-icon "></i>Download</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="trainee" class="apex-charts "></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12">
            <div class="card mb-4">
                <div
                    class="card-header align-items-center card-header-height  d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">COURSE TYPE</h4>
                    </div>
                    <div>
                        <div class="dropdown dropstart">
                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button"
                                id="courseDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="courseDropdown2">
                                <span class="dropdown-header">Settings</span>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-external-link dropdown-item-icon "></i>Export</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-mail dropdown-item-icon "></i>Email
                                    Report</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fe fe-download dropdown-item-icon "></i>Download</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="courseType" class="apex-charts d-flex justify-content-center"></div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal -->
    <div class="modal fade" id="companiesModal" tabindex="-1" role="dialog" aria-labelledby="companiesModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">All Companies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body p-3">
                    <ul class="list-unstyled card-columns mt-2" style="column-count: 2">
                        @php
                            $companyKeys = array_keys($companies->toArray());
                        @endphp

                        @foreach ($companyKeys as $index)
                            @php
                                $company = $companies[$index];
                            @endphp


                            <li class="mb-3 mr-3">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $index + 1 }}. {{ $company->company }}</div>
                                    Total Trainees: <span
                                        class="badge bg-primary rounded-pill">{{ number_format($company->record_count) }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">
            <!-- Card -->
            <div class="card h-100">
                <!-- Card header -->
                <div class="card-header d-flex align-items-center justify-content-between card-header-height">
                    <h4 class="mb-0">TOP COMPANY</h4>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#companiesModal">
                        View All
                    </button>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($companies->take(8) as $company)
                            <li class="list-group-item px-0 pt-2">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-md avatar-indicators avatar-offline">
                                            <img alt="avatar" src="../../assets/images/oesximg/company.jpg"
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col ms-n3">
                                        <h4 class="mb-0 h5">{{ $company->company }}</h4>
                                        <span class="me-2 fs-6">
                                            <span
                                                class="text-dark  me-1 fw-semibold">{{ number_format($company->record_count) }}</span>Trainees</span>
                                        {{-- <span class="fs-6">
                                            <span class="text-dark  me-1 fw-semibold">32,000</span> Reviews
                                        </span> --}}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">
            <!-- Card -->
            <div class="card h-100">
                <!-- Card header -->
                <div
                    class="card-header d-flex align-items-center justify-content-between card-header-height">
                    <h4 class="mb-0">RECENT LOGS</h4>
                    <a href="{{route('a.notification-history')}}" class="btn btn-outline-secondary btn-sm">View all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($logs as $log)
                            <li class="list-group-item px-0 pt-0">
                                <div class="row">
                                    <!-- Col -->
                                    <div class="col ps-0">
                                            <p class="text-primary-hover text-uppercase">
                                            <b>{{date('F j, Y', strtotime($log->created_at))}} </b>// <i> {{$log->details}}</i>
                                            </p>
                                        <div class="d-flex align-items-center">
                                            <img  src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt="" class="rounded-circle avatar-xs me-2">
                                            <span class="fs-6">{{$log->f_name}} {{$log->l_name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">
            <!-- Card -->
            <div class="card h-100">
                <!-- Card header -->
                <div class="card-header card-header-height d-flex align-items-center">
                    <h4 class="mb-0">LATEST ENROLLMENT
                    </h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- List group -->


                    <ul class="list-group list-group-flush list-timeline-activity">
                        @foreach ($latestEnrollments as $latestEnrollment)
                            <li class="list-group-item px-0 pt-0 border-0 mb-2">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-md avatar-indicators avatar-online">
                                            @if ($latestEnrollment->imagepath)
                                                <img src="{{asset('storage/traineepic/'. $latestEnrollment->imagepath)}}"
                                                    class="rounded-circle" alt="avatar">
                                            @else
                                                <img src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                                                    class="rounded-circle" alt="avatar">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col ms-n2">
                                        <h4 class="mb-0 h5">
                                            {{ ucwords(strtolower($latestEnrollment->l_name)) . ', ' . ucwords(strtolower($latestEnrollment->f_name)) }}
                                        </h4>
                                        <p class="mb-1">
                                            <span> has enrolled </span>
                                            <span class="text-primary">{{ $latestEnrollment->coursename }}</span>
                                        </p>
                                        <span
                                            class="fs-6 text-primary-emphasis">{{ \Carbon\Carbon::parse($latestEnrollment->dateconfirmed)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    //console.log(@json($companies));
    const data = @json($traineeAccounts);
    const coursesType = @json($courseType);
    console.log(coursesType);

    var options = {
        chart: {
            type: 'line',
            height: 300,
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: 4,
            curve: "smooth",
            colors: "#754ffe"
        },
        series: [{
            name: 'Trainees Enrolled',
            data: data
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                'Dec'
            ]
        },
        grid: {
            borderColor: "#e2e8f0",
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !0
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        legend: {
            position: "top",
            horizontalAlign: "right",
            offsetY: -50,
            fontSize: "16px",
            markers: {
                width: 10,
                height: 10,
                strokeWidth: 0,
                strokeColor: "#fff",
                fillColors: void 0,
                radius: 12,
                onClick: void 0,
                offsetX: 0,
                offsetY: 0
            },
            itemMargin: {
                horizontal: 0,
                vertical: 20
            }
        },
        tooltip: {
            theme: "light",
            marker: {
                show: !0
            },
            x: {
                show: !1
            }
        },
        responsive: [{
            breakpoint: 575,
            options: {
                legend: {
                    offsetY: -30
                }
            }
        }]

    }

    var options2 = {
        chart: {
            width: 392,
            type: "donut"
        },
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: "78%"
                }
            }
        },
        colors: ["#64748b", "#593cc1", "#ede9fe", "#6610f2", "#0d6efd", "#475569"],
        dataLabels: {
            enabled: true
        },
        stroke: {
            width: 4,
            curve: "smooth",

        },
        series: coursesType.map(item => item.count_per_type),
        labels: coursesType.map(item => item.coursetype),
        legend: {
            labels: {
                colors: ["#64748b", "#593cc1", "#ede9fe", "#6610f2", "#0d6efd", "#475569"],
                useSeriesColors: !1
            },
            position: "bottom",
            fontFamily: "inter",
            fontWeight: 500,
            fontSize: "14px",
            markers: {
                width: 8,
                height: 8,
                strokeWidth: 0,
                strokeColor: "#fff",
                fillColors: void 0,
                radius: 12,
                customHTML: void 0,
                onClick: void 0,
                offsetX: 0,
                offsetY: 0
            },
            itemMargin: {
                horizontal: 8,
                vertical: 0
            }
        },
        tooltip: {
            theme: "light",
            marker: {
                show: !0
            },
            x: {
                show: !1
            }
        },
        states: {
            hover: {
                filter: {
                    type: "none"
                }
            }
        },
        stroke: {
            show: !0,
            colors: "transparent",
        }

    }
    var chart = new ApexCharts(document.querySelector("#trainee"), options);
    var chart2 = new ApexCharts(document.querySelector("#courseType"), options2);

    chart.render();
    chart2.render();
</script>