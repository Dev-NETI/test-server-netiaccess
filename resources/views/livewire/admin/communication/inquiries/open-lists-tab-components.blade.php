
<div class="table-responsive border-0 overflow-y-hidden">
    <table class="table mb-0 text-nowrap table-centered table-hover">
        <thead class="table-light">
            <tr>
                <th>STATUS</th>
                <th>NAME</th>
                <th>INQUIRY MESSAGE</th>
                <th>DATE UPDATED</th>
               

            </tr>
        </thead>
        <tbody>
            @foreach ( $Openinquirylist as $Openinquirylists )
            <tr>
                <td>
                    <span class="badge rounded-pill bg-danger">Open</span>
                      <!-- Button Nesting -->
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                         
                            <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="{{ route('a.inquiry-reply', $Openinquirylists->emailinquiryid) }}"><i class="fe fe-send dropdown-item-icon"></i> Reply</a>
                                <a class="dropdown-item" href="#"><i class="fe fe-trash dropdown-item-icon"></i>Delete</a>
                            </div>
                            </div>
                        </div>
                        {{-- <span class="dropdown dropstart">
                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button"
                                id="courseDropdown11" data-bs-toggle="dropdown" data-bs-offset="-20,20"
                                aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <span class="dropdown-menu" aria-labelledby="courseDropdown11">
                                <span class="dropdown-header">Action</span>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#composeMailModal"><i class="fe fe-send dropdown-item-icon"></i> Reply
                                </a>
                                <a class="dropdown-item" href="#"><i class="fe fe-inbox dropdown-item-icon"></i>Moved
                                    Draft</a>
                                <a class="dropdown-item" href="#"><i class="fe fe-trash dropdown-item-icon"></i>Delete</a>
                            </span>
                        </span> --}}
                </td>
                <td>
                    <h4 class="mb-0"> {{$Openinquirylists->name}}</h4>
                    <span class="text-muted"> <i
                            class="mdi mdi-email-open-outline"></i>{{$Openinquirylists->email}}</span>
                    <p>
                        <span class="text-muted"><i
                                class="mdi mdi-cellphone-basic"></i>{{$Openinquirylists->mobile}}</span>
                </td>
                <td>
                    {{$Openinquirylists->inquirytype}}
                    <textarea class="form-control" id="textarea-input"
                        rows="2" disabled>{{$Openinquirylists->inquiry_text}}</textarea>
                </td>
              

                <td>
                  
                    {{ \Carbon\Carbon::parse($email_inquiries->created_at)->format('F j,
                    Y') }}
 
 
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <div class="row">
            {{ $email_inquiry->appends(['search' => $search])->links('livewire.components.customized-pagination-link')
            }}

        </div>
    </div>
</div>