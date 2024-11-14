<div class="container-fluid mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0">Certificate Release</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary" wire:click.prevent="performAction">
                <i class="fe fe-send me-2"></i>Release Selected
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Issued By</th>
                            <th>Approved By</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrolled as $enrol)
                        <tr wire:key="enrol-{{ $enrol->enroledid }}">
                            <td>
                                @if(optional($enrol->course->type)->coursetypeid === 3 || optional($enrol->course->type)->coursetypeid === 4)
                                @if($enrol->passid == 1 && $enrol->certificate_history && optional($enrol->certificate_history)->is_approve === 1 )
                                <button class="btn btn-sm btn-outline-primary" wire:click='certificate({{$enrol->enroledid}})'>
                                    <i class="fe fe-file-text me-1"></i>View
                                </button>
                                @endif
                                @endif
                            </td>
                            <td><small class="text-muted">#{{ optional($enrol->certificate_history)->certificatehistoryid ?? '---' }}</small></td>
                            <td>{{ optional($enrol->trainee)->certificate_name() }}</td>
                            <td>{{ optional($enrol->certificate_history)->issued_by ?? 'N/A' }}</td>
                            <td>{{ optional($enrol->certificate_history)->approved_by ?? 'N/A' }}</td>
                            <td>
                                @if (optional($enrol->certificate_history)->registrationnumber)
                                @if (optional($enrol)->passid == 1)
                                <span class="badge bg-success">Passed</span>
                                @elseif(optional($enrol)->passid == 2)
                                <span class="badge bg-danger">Failed</span>
                                @elseif(optional($enrol)->pendingid == 3)
                                <span class="badge bg-warning">Remedial</span>
                                @else
                                <span class="badge bg-info">On-going</span>
                                @endif
                                @else
                                <span class="badge bg-secondary">No Record</span>
                                @endif
                            </td>
                            <td>
                                @if (optional($enrol->certificate_history)->registrationnumber)
                                @if (optional($enrol->certificate_history)->is_approve == 1 && optional($enrol->certificate_history)->is_released == 1 )
                                <span class="badge bg-success">Released ({{$enrol->certificate_history->is_released_date}})</span>
                                @elseif(optional($enrol->certificate_history)->is_approve == 1 && optional($enrol->certificate_history)->is_released == 0)
                                <span class="badge bg-danger">Not Released</span>
                                @else
                                <span class="badge bg-warning">Needs Approval</span>
                                @endif
                                @else
                                <span class="badge bg-secondary">No Record</span>
                                @endif
                            </td>
                            <td>
                                @if($enrol->certificate_history->invalid_comment)
                                <span class="text-danger">{{$enrol->certificate_history->invalid_comment}}</span>
                                @else
                                <textarea class="form-control form-control-sm" rows="2" wire:model.defer="comments.{{$enrol->certificate_history->certificatehistoryid}}" placeholder="Enter comment..."></textarea>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (optional($enrol->certificate_history)->certificatehistoryid)
                                @can('authorizeAdminComponents', 112)
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-success" wire:click="released_cert({{$enrol->certificate_history->certificatehistoryid}})">
                                        <i class="fe fe-check me-1"></i>Release
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="dis_cert({{$enrol->certificate_history->certificatehistoryid}})" @if($enrol->certificate_history->invalid_comment) disabled @endif>
                                        <i class="fe fe-x me-1"></i>Invalidate
                                    </button>
                                </div>
                                @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            @if ($enrolled->hasPages())
            <nav aria-label="Page navigation">
                {{ $enrolled->links('livewire.components.customized-pagination-link') }}
            </nav>
            @endif
        </div>
    </div>
</div>