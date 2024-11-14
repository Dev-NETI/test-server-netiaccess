<div wire:ignore.self class="modal fade" id="ViewAttachmentModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">View Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Attachment type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attachments as $attachment)
                            <tr>
                                <td>
                                    {{ $attachment->title }}<br/>
                                    @if ($attachment->attachmenttypeid == 3) <span class="badge text-bg-success">O.R. # {{$attachment->OR_Number}}</span> @endif
                                </td>
                                <td>{{ $attachment->attachmenttype->attachmenttype }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-6 m-1">
                                            <a wire:click="view('storage/uploads/billingAttachment/{{ $attachment->filepath }}')"
                                                {{-- href="{{asset('storage/uploads/billingAttachment/'.$attachment->filepath)}}"  --}} class="btn btn-primary"><i
                                                    class="bi bi-cloud-download-fill"></i></a>
                                        </div>
                                        @can('authorizeAdminComponents', 119)
                                            <div class="col-lg-6 m-1">
                                                <button class="btn btn-danger" wire:click="confirmDelete({{$attachment->id}})" title="delete"><i class="bi bi-trash-fill"></i></button>
                                            </div>
                                        @endcan
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="downloadAllAttachment()" class="btn btn-info" data-bs-dismiss="modal">Download All</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
