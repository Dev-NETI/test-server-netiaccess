<section class="container-fluid p-4">
    <div class="row">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-history"></i> Textblast Logs</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.dashboard') }}">Dahboard</a>
                            </li>

                            <li class="breadcrumb-item" aria-current="page">
                               Logs
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header p-0">
                    <div>
                        <!-- Nav -->
                        <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses"
                                    role="tab" aria-controls="courses" aria-selected="true">All ({{ $count_logs }})</a>
                            </li>


                        </ul>
                    </div>

                    <div class="p-4 row">
                        <!-- Form -->
                        <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                            @csrf
                            <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                            <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                                placeholder="Search Name or Number ">
                        </form>
                    </div>

                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover text-sm">
                        <thead class="table-light">
                            <tr>

                               
                                    
                              
                                <th>No </th>
                                <th>Name</th>
                                <th>Mobile No.</th>
                                <th>Message</th>
                                <th>Date sent</th>
                              

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($textblastlogs as $logs )
                            <tr>
                                <td></td>
                                <td>{{ $logs->wholename }}</td>
                                <td>{{ $logs->mobilenumber }}</td>
                                <td>{{ $logs->message }}</td>
                                <td>{{ $logs->date_sent }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $textblastlogs->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</section>