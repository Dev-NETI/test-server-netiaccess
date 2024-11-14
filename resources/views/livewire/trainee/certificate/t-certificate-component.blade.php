<section class="container p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3">
                <div class="mb-3 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Certificate History</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Certificate History
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="card">
                <div class="row g-0">
                    <div class="col-xxl-2 col-xl-3 border-end">
                        <nav class="navbar navbar-expand p-4 navbar-mail">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav flex-column w-100">
                                    <li class="d-grid mb-4">
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page">
                                            <span class="d-flex align-items-center justify-content-between">
                                                <span class="d-flex align-items-center" style="font-size: 12px;"><i class="fe fe-inbox me-2"></i>All certificates
                                                </span>
                                                @if (Auth::guard('trainee')->user()->company->companyid == 1 || Auth::guard('trainee')->user()->company->companyid == 3 || Auth::guard('trainee')->user()->company->companyid == 89 || Auth::guard('trainee')->user()->company->companyid == 115 || Auth::guard('trainee')->user()->company->companyid == 262)
                                                @if ($certificates->count() == 0)
                                                <span style="font-size: 12px;" class="text-danger">None</span>
                                                @else
                                                <span style="font-size: 12px;">{{ $certificates->count() }}</span>
                                                @endif
                                                @endif
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-12">
                        <div class="card-header p-4">
                            <div class="d-md-flex justify-content-between align-items-center">
                                <div class="d-flex flex-wrap gap-2 mb-2 mb-md-0">
                                </div>
                                <div>
                                    <form>
                                        <select class="form-select mt-3" wire:model="selected_course">
                                            <option value="">Select a course</option>
                                            @foreach ($courses as $course)
                                            <option value="{{$course->courseid}}">{{$course->coursecode}} - {{$course->coursename}}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    @if (Auth::guard('trainee')->user()->company->companyid == 1 || Auth::guard('trainee')->user()->company->companyid == 3 || Auth::guard('trainee')->user()->company->companyid == 89 || Auth::guard('trainee')->user()->company->companyid == 115 || Auth::guard('trainee')->user()->company->companyid == 262)
                                    @foreach ($certificates as $certificate)
                                    <tr>
                                        <td>{{$certificate->course->coursecode}} - {{$certificate->course->coursename}} </td>
                                        <td>
                                            <p class="mb-0 list-text">
                                                <i>{{$certificate->enrolled->schedule->batchno}} ({{ date('F j, Y', strtotime($certificate->enrolled->schedule->startdateformat)) }} - {{ date('F j, Y', strtotime($certificate->enrolled->schedule->enddateformat)) }})</i>
                                            </p>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" wire:click="redirectToCertHistoryDetails({{$certificate->certificatehistoryid}})">
                                                <i class="fe fe-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="View certificate">
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                        <!-- <div>
                            <div class="list-group list-group-flush list-group-mail">

                                @if (Auth::guard('trainee')->user()->company->companyid == 1 || Auth::guard('trainee')->user()->company->companyid == 3 || Auth::guard('trainee')->user()->company->companyid == 89 || Auth::guard('trainee')->user()->company->companyid == 115 || Auth::guard('trainee')->user()->company->companyid == 262)

                                @foreach ($certificates as $certificate)

                                <div class="list-group-item list-group-item-action px-4 list-mail bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="listTwo">
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="list-title">
                                                <span class="fw-bold">{{$certificate->course->coursecode}} - {{$certificate->course->coursename}} </span>
                                            </div>
                                            <div class="me-6 w-xxl-100 w-lg-50 w-md-50 w-5">
                                                <button type="button" class="btn btn-link" wire:click="redirectToCertHistoryDetails({{$certificate->certificatehistoryid}})">
                                                    <p class="mb-0 list-text">
                                                        This certificates are printed {{date("F j, Y", strtotime($certificate->dateprinted))}} | <span class="text-uppercase"> <i>{{$certificate->trainee->formal_name()}}</i> </span>
                                                    </p>
                                                </button>
                                            </div>

                                            <div class="list-time">
                                                <p class="mb-0">{{date("h:i A", strtotime($certificate->dateprinted))}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="actions-mail">
                                        <button type="button" class="btn btn-primary" wire:click="redirectToCertHistoryDetails({{$certificate->certificatehistoryid}})">
                                            <i class="fe fe-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="View certificate">
                                            </i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div> -->
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-between">
                                {{ $certificates->links('livewire.components.customized-pagination-link')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br>
        </div>
    </div>
</section>