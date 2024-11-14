<section>
    <div class="container">

        <div class="card text-center mt-5">

            <div class="card-header">
                <h1 class="card-title">{{ $course->coursename }}</h1>
                <p class="card-text">Display all schedules that contains trainee</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 offset-md-8">
                        <input type="search" class="form-control" placeholder="Search..."
                            wire:model.debounce.500ms="search">
                    </div>

                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Batch No.</th>
                                    <th scope="col">Training schedule</th>
                                    <th scope="col"># of Trainee</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @if (!empty($schedules))
                                @foreach ($schedules as $schedule)
                                <tr>
                                    <td class="text-start"><small>{{ $schedule->scheduleid }}</small></td>
                                    <td class="text-start"><small>{{ $schedule->batchno }}</small></td>
                                    <td><small> {{ $schedule->startdateformat }} - {{ $schedule->enddateformat }}</small></td>
                                    <td>{{$schedule->enrolled_count}}</td>
                                    <td>
                                        <a wire:click="passData({{$schedule->scheduleid}})"
                                            class="btn btn-primary btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <p>No schedules found.</p>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>