<div class="container-fluid p-4">
    <div class="row ">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 ">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold"> <i class="mdi mdi-cellphone"></i>  Text Blast </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Text Blast </a>
                            </li>
                            
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row">

        <div class="col-lg-7 col-12">
            <!-- card -->
            <div class="card mb-4">
                <!-- card body -->
                <div class="card-body">
                    <form id="traineeadd" wire:submit.prevent="traineeadd">
                        <div>
                            <div class="row">
                                <!-- input -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Select Batch</label>
                                    <select class="form-select " wire:model="selected_batch"
                                        placeholder="Click to Batch Week">
                                        <option value="">Select Batch</option>
                                        @foreach ($batchWeeks as $week)
                                        <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Select Course </label>
                                    <select class="form-select " wire:model="selectcourse"
                                        placeholder="Click to Select Course">
                                        <option value="">Select Course</option>
                                        @foreach ($query_courses as $courses )
                                        <option value="{{$courses->courseid}}">{{$courses->coursename}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- input -->
                            <div class="table-responsive">
                                <label class="form-label">List of Trainees</label>
                                <table
                                    class="table mb-0 text-nowrap table-hover table-centered table-with-checkbox table-sm">
                                    <!-- Table Head -->
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">

                                                    <label class="form-check-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th>TRAINEE NAME</th>
                                            <th>MOBILE</th>
                                            <th>COURSE</th>
                                            <th>BATCH NO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table body -->
                                        @foreach ($query as $enroll )
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        wire:model.defer="recipientid.{{ $enroll->trainee->traineeid }}"
                                                        type="checkbox" id="{{ $enroll->trainee->traineeid }}">
                                                    <label class="form-check-label" for="returnOne"></label>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">{{$enroll->trainee->formal_name()}}</a>
                                            </td>
                                            <td>{{ $enroll->trainee->contact_num }} </td>
                                            <td>{{ $enroll->course->coursename }}</td>
                                            <td>{{$enroll->schedule->batchno}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-grid mb-2">
                                    <button class="btn btn-primary btn-sm mt-2" type="submit"> <i class="fe fe-plus" aria-hidden="true"></i>  Add Selected</button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-5 col-12">
            <!-- card -->
            <div class="card">
                <!-- card body -->

                <div class="card-body">
                    <!-- input -->
                    <form id="addnewreciptient" wire:submit.prevent="addnewreciptient">
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" wire:model.defer="addwholename"
                                        placeholder="Enter sender name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Number</label>
                                    <input type="text" class="form-control" wire:model.defer="addmobilenumber"
                                        placeholder="09xxxxxxxxx" required>
                                </div>
                                <div class="cpl-md-6">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <button class="btn btn-primary"> <i class="fe fe-plus" aria-hidden="true"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
              
                </div>
            </div>
            <!-- card -->
            <div class="card mt-4">
                <!-- card body -->
                <div class="card-body">
                    <!-- select -->
                
                    <form wire:submit.prevent="sendMessage">
                        <div>
                            <x-error-message />
                            <x-success-message />
                            <label class="form-label">Message</label>
                            <textarea class="form-control" placeholder="Enter your message" wire:model.defer="addmessage" required></textarea>
                        </div>
                        <table class="table table-responsive">
                            <!-- Table Head -->
                            <label class="form-label mt-2">Recipients</label>
                            <thead class="table-light">
                                <tr>
                                    <th>ACTION</th>
                                    <th>TRAINEE NAME</th>
                                    <th>MOBILE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body -->
                                @foreach ($recipients as $recipient)
                                <tr>
                                    <td> <a href="#" class="btn btn-danger btn-sm"
                                            wire:click="deleteRecipient({{ $recipient->recipientid }})">
                                            <i class="fe fe-trash" aria-hidden="true"></i>
                                        </a> </td>
                                    <td> {{ $recipient->wholename }}</td>
                                    <td> {{ $recipient->mobilenumber }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-info">
                                <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
                




            </div>
            <!-- button -->

        </div>


    </div>

</div>

<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to send message?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
</div>