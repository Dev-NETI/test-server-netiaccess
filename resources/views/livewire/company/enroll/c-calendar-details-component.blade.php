<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        {{$schedule->course->coursecode}} - {{$schedule->course->coursename}}
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/company/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Training Schedule Details<a href=""></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{$schedule->course->coursecode}} - {{$schedule->course->coursename}}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 col-12 mb-3">

        <!-- <button type="button" class="btn btn-info" wire:click="">
            <i class="bi bi-calendar-week-fill"></i> Export training calendar
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".createtraining">
            <i class="bi bi-clipboard-plus-fill"></i> Create Training Calendar
        </button> -->

    </div>
    <div class="row">
        <!-- basic table -->
        <div class="col-md-12  mb-5">
            <div class="card h-100">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Trainees</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search id or training date.." wire:model="search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>FULL NAME</th>
                                <th>RANK</th>
                                <th>FLEET #</th>
                                <th>COMPANY</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($trainees->count())
                            @foreach ($trainees as $trainee)
                            <!-- inactive if 1 -->
                            <tr>
                                <td>
                                    {{$trainee->traineeid}}
                                </td>
                                <td>
                                    {{ strtoupper($trainee->formal_name()) }}
                                </td>
                                <td>
                                    {{$trainee->rank->rank}}
                                </td>
                                @if (optional(optional($trainee)->fleet)->fleet)
                                <td>
                                    {{$trainee->fleet->fleet}}
                                </td>
                                @else
                                <td>
                                    -----------
                                </td>
                                @endif
                                <td>
                                    {{ optional($trainee->company)->company }}
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" title="Assign" wire:click="loadtrainee({{ $trainee->traineeid }})" data-bs-toggle="modal" data-bs-target="#exampleModal-2"><i class="bi fs-4 bi-plus"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $trainees->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-12 mb-5">
            <div class="card mt-3 h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Enrolled Trainees</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                        </div>
                        <div class="col-lg-4 float-end">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>FULL NAME</th>
                                <th>RANK</th>
                                <th>FLEET #</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($e_trainees->count())
                            @foreach ($e_trainees as $trainee)
                            <!-- inactive if 1 -->
                            <tr>
                                <td>
                                    {{$trainee->traineeid}}
                                </td>
                                <td>
                                    {{$trainee->trainee->formal_name()}}
                                </td>
                                <td>
                                    {{$trainee->trainee->rank->rank}}
                                </td>
                                @if (optional(optional($trainee)->fleet)->fleet)
                                <td>
                                    {{$trainee->trainee->fleet->fleet}}
                                </td>
                                @else
                                <td>
                                    -----------
                                </td>
                                @endif
                                <td>
                                    @if ($trainee->pendingid == 0)
                                    Enrolled
                                    @else
                                    Pending
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.company.enroll.c-enroll-modal')

</section>