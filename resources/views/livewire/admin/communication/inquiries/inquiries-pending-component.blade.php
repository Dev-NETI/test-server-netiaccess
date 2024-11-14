<section class="container-fluid p-4">
    <div class="row">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                <div class="">
                    <h1 class="mb-1 h2 fw-bold">Inquiries</h1>
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
                    <span class="fs-6 text-uppercase fw-semibold">TOTAL INQUIRIES</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{ $email_allCount}}</h2>

                        </div>
                        <div>
                            <span class="bg-light-primary icon-shape icon-xl rounded-3 text-dark-primary">
                                <i class="mdi mdi-text-box-multiple mdi-24px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold">OPEN INQUIRIES</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{ $email_OpenCount}}</h2>
                            <span>NEW INQUIRIES</span>
                        </div>
                        <div>
                            <span class="bg-light-warning icon-shape icon-xl rounded-3 text-dark-warning">
                                <i class="mdi mdi-comment mdi-24px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold">CLOSED INQUIRIES</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{$email_ClosedCount}}</h2>
                            <span>REPLIED INQUIRIES</span>
                        </div>
                        <div>
                            <span class="bg-light-success icon-shape icon-xl rounded-3 text-dark-success">
                                <i class="mdi mdi-comment-check-outline mdi-24px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold">Comments</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">120</h2>
                            <span>20+ Comments</span>
                        </div>
                        <div>
                            <span class="bg-light-info icon-shape icon-xl rounded-3 text-dark-info">
                                <i class="mdi mdi-comment-text mdi-24px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card rounded-3">
                <!-- Card header -->
                <div class="card-header p-0">
                    <div>
                        <!-- Nav -->
                        <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab"
                                    aria-controls="courses" aria-selected="true">All</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="open-tab" data-bs-toggle="pill" href="#open" role="tab"
                                    aria-controls="open" aria-selected="false">Open</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="closed-tab" data-bs-toggle="pill" href="#closed" role="tab"
                                    aria-controls="closed" aria-selected="false">Closed
                                </a>
                            </li> --}}

                        </ul>
                    </div>
                </div>

                <div>
                    <!-- Table -->
                    <div class="tab-content" id="tabContent">
                        <!--Tab pane -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">

                            <div class="p-4 row">
                                <!-- Form -->
                                <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                                    <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                                    <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                                        placeholder="Search Inquiry">
                                </form>
                            </div>
                            <div class="table-responsive border-0 overflow-y-hidden">
                                <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                                    <label class="form-check-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th>NAME</th>
                                            <th>INQUIRY MESSAGE</th>
                                            <th>DATE UPDATED</th>
                                            <th>STATUS</th>
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($email_inquiry as $email_inquiries)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="categoryCheck11">
                                                    <label class="form-check-label" for="categoryCheck11"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <h4 class="mb-0"> {{$email_inquiries->name}}</h4>
                                                <span class="text-muted"> <i
                                                        class="mdi mdi-email-open-outline"></i>{{$email_inquiries->email}}</span>
                                                <p>
                                                    <span class="text-muted"><i
                                                            class="mdi mdi-cellphone-basic"></i>{{$email_inquiries->mobile}}</span>

                                            </td>

                                            <td>
                                                {{$email_inquiries->inquirytype}}
                                                <textarea class="form-control" id="textarea-input" rows="2"
                                                    disabled>{{$email_inquiries->inquiry_text}}</textarea>
                                            </td>
                                            <td> {{ \Carbon\Carbon::parse($email_inquiries->created_at)->format('F j,
                                                Y') }}
                                            </td>
                                            <td>
                                                @if ($email_inquiries->is_answered == 0)
                                                <span
                                                    class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                                Open
                                                @else
                                                <span
                                                    class="badge-dot bg-primary me-1 d-inline-block align-middle"></span>
                                                Closed
                                                @endif
                                            </td>
                                            <td>
                                                <span class="dropdown dropstart">
                                                    <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                        role="button" id="courseDropdown11" data-bs-toggle="dropdown"
                                                        data-bs-offset="-20,20" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <span class="dropdown-menu" aria-labelledby="courseDropdown11">
                                                        <span class="dropdown-header">Action</span>

                                                        @if($email_inquiries->hash_id)
                                                        <a class="dropdown-item"
                                                            href="{{ route('a.inquiry-view', ['hash_id' => $email_inquiries->hash_id]) }}">
                                                            <i class="fe fe-inbox dropdown-item-icon"></i> View
                                                        </a>
                                                        @else
                                                        <p class="text-danger">No hash_id available for this inquiry.
                                                        </p>
                                                        <!-- Or you can provide an alternative action, such as a button or link to go back -->
                                                        <!-- <a href="{{ route('a.inquiry-view') }}" class="btn btn-primary">Go Back</a> -->
                                                        @endif


                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#composeMailModal"><i
                                                                class="fe fe-send dropdown-item-icon"></i>
                                                            Reply
                                                        </a>
                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updatestatus" wire:click="statusedit({{ $email_inquiries->emailinquiryid }})">
                                                            <i class="fe fe-trash dropdown-item-icon"></i>Update Status
                                                        </a>
                                                        <a class="dropdown-item" href="#"><i class="fe fe-trash dropdown-item-icon"></i>Delete
                                                        </a>
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="card-footer">
                                    <div class="row">
                                        {{ $email_inquiry->appends(['search' =>
                                        $search])->links('livewire.components.customized-pagination-link') }}
                                        {{-- {{ $email_inquiry->appends(['search' =>
                                        $search])->links('livewire.components.customized-pagination-link') }} --}}
                                        {{-- {{ $email_inquiry->links('livewire.components.customized-pagination-link')
                                        }} --}}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!--Tab pane -->
                        <div class="tab-pane fade" id="open" role="tabpanel" aria-labelledby="open-tab">
                            {{-- @livewire('open-lists-tab-components') --}}
                            @include('livewire.admin.communication.inquiries.open-lists-tab-components')
                        </div>






                        <!--Tab pane -->
                        <div class="tab-pane fade" id="closed" role="tabpanel" aria-labelledby="closed-tab">
                            <div class="table-responsive border-0 overflow-y-hidden">
                                <div class="table-responsive border-0 overflow-y-hidden">
                                    <table
                                        class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox">
                                        <thead class="table-light">
                                            <tr>
                                                <th>

                                                </th>
                                                <th>NAME</th>
                                                <th>TYPE</th>
                                                <th>INQUIRY MESSAGE</th>
                                                <th></th>
                                                <th>DATE UPDATED</th>
                                                <th>STATUS</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>



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
                </div>
            </div>

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade compose-mail" id="composeMailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form>
                        <div class="border-bottom">
                            <input class="form-control border-0 shadow-none" type="email" placeholder="To">
                        </div>
                        <div class="border-bottom">
                            <input class="form-control border-0 shadow-none" type="email" placeholder="Subject">
                        </div>
                        <div>
                            <div id="editor" class="rounded-0">
                                <p>Type something here</p>
                                <br>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <a href="#" class="btn btn-primary">Send</a>

                        <span class="ms-4 text-muted compose-img-upload cursor-pointer">
                            <label for="file-input">
                                <i class="fe fe-paperclip"></i>
                            </label>

                            <input id="file-input" type="file">
                        </span>
                        <span class="ms-3 text-muted compose-img-upload cursor-pointer">
                            <label for="file-input-second">
                                <i class="fe fe-image"></i>
                            </label>

                            <input id="file-input-second" type="file">
                        </span>
                        <a href="#" class="ms-3 text-muted">
                            <i class="fe fe-link"></i>
                        </a>
                    </div>
                    <div>
                        <a href="#" class="text-muted">
                            <i class="fe fe-more-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="More Actions"></i>
                        </a>
                        <a href="#" class="text-muted ms-2">
                            <i class="fe fe-trash-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Delete"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- UPDATE STATUS -->
<div wire:ignore.self class="modal fade" id="updatestatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updatestatusForm" wire:submit.prevent="updatestatus">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label class="form-label">Status</label>
                            <select class="form-select text-black" data-width="100%" wire:model.defer="selectstatus" required>
                                <option value="">-------Select-----------</option>
                                <option value="1">Resolved</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" wire:click="updatestatus">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>


</section>