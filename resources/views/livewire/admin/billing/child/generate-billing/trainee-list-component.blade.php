<table wire:ignore.self class="table table-hover table-striped border rounded-end" style="font-size: 10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>SchedID</th>
            <th>RemedialSchedID</th>
            <th>TraineeID</th>
            <th>EnroledID</th>
            <th>Name</th>
            <th>Vessel</th>
            <th>Rank</th>
            <th>Company</th>
            <th>Nationality</th>
            <th>Serial Number</th>
            <th>Status</th>
            @if ($course == 92)
            <th>Discount</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <style>
            #hoverA a:hover {
                color: #0477bf;
                text-decoration: none;
            }
        </style>
        @foreach ($trainees as $index => $trainee)
        @php
        $index++;
        @endphp
        <livewire:admin.billing.child.generate-billing.trainee-list-item-component :index="$index"
            :trainee="$trainee" />
        @endforeach
    </tbody>
</table>

<div wire:ignore.self class="modal fade" id="transfermodal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transfer Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="transferForm" action="" wire:submit.prevent="transferBilling">
                    @csrf
                    <select required name="" id="" class="form-select" wire:model.defer="companyTransfer">
                        <option value="">Select company</option>
                        @foreach ($companyList as $item)
                        <option value="{{$item->companyid}}">{{ $item->company }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="transferForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>