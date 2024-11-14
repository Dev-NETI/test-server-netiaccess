<div class="container-fluid ml-2 mr-2 mt-5">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="card text-center mt-5">

        <div class="card-header">
            <h2 class="fw-bold display-3">Enrollment Statistics @if ($statistics_data != null)
                    for {{ $statistics_data[0]->trainee->company->company }}
                @endif
            </h2>
            @if ($statistics_data != null)
                <span class="badge bg-info-soft float-end">Range: {{ $dateFrom }} - {{ $dateTo }}</span>
            @endif
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-3">

                    <form wire:submit.prevent="show">
                        <div class="row">
                            <div class="col-md-6 text-start">
                                <label>Date From</label>
                                <input type="date" class="form-control" wire:model="dateFrom">
                                @error('dateFrom')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 text-start">
                                <label>Date To</label>
                                <input type="date" class="form-control" wire:model="dateTo">
                                @error('dateTo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 text-start">
                                <label>Company</label>
                                <select class="form-control" wire:model="company">
                                    <option value="">Select</option>
                                    @foreach ($company_data as $item)
                                        <option value="{{ $item->companyid }}">{{ $item->company }}</option>
                                    @endforeach
                                </select>
                                @error('company')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-2">
                                <button class="btn btn-primary float-end" type="submit">Generate</button>
                            </div>
                        </div>
                    </form>

                </div>

                {{-- STATISTICS --}}
                @if ($statistics_data != null)
                    <div class="col-md-9">

                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-12 col-12 offset-xl-9">
                                <!-- Card -->
                                <div class="card mb-4">
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <span class="fs-6 text-uppercase fw-semibold ls-md">Trainees</span>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <div class="lh-1">
                                                <h2 class="h1 fw-bold mb-1">{{ count($statistics_data) }}</h2>
                                                <span>Total Count</span>
                                            </div>
                                            <div>
                                                <span
                                                    class="bg-light-success icon-shape icon-xl rounded-3 text-dark-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-users">
                                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="9" cy="7" r="4"></circle>
                                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Training Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($statistics_data as $item)
                                        <tr>
                                            <td>
                                                <h5 class="mb-0">{{ $item->trainee->rank->rankacronym }}
                                                    {{ $item->trainee->name_for_meal }}</h5>
                                            </td>
                                            <td>{{ $item->schedule->course->full_course_name }}</td>
                                            <td>{{ $item->schedule->training_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>
