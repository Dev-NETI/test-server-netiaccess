@props(['id', 'position', 'width', 'modalTitle', 'closeBtn', 'saveBtnTitle', 'function'])

<div wire:ignore.self class="modal fade" id="{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog {{$position}} {{$width}}" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">{{ $modalTitle }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <div class="modal-body">
                <x-input model="forwardBillingSerialNumber" wire:model.lazy="forwardBillingSerialNumber" label="Serial Number" name="serialnumber" class="form-control is_invalid" type="text" placeholder="Input serial number"/>
      
                <x-input model="forwardBillingAttachment" wire:model="forwardBillingAttachment" label="Upload Billing Statement" name="billingattachment" class="form-control" type="file" accept="application/pdf" />

                <x-label class="form-label mt-2" for="board_type">
                    Forward to Board:
                </x-label>
                <select wire:model="forwardBillingBoardSelector" name="" class="form-select" id="board_type">
                    <option selected value="6">Client Confirmation Board</option>
                    <option value="10">Transaction Closed</option>
                </select>

        </div>
        <div class="modal-footer">
          <button type="submit"  wire:click="{{ $function }}" form="myform" class="btn btn-primary">{{ $saveBtnTitle }}</button>
        </div>
      </div>
    </div>
  </div>