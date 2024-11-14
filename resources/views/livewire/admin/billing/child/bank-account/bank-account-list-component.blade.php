<tr>
    <td>
        @if ($isEdit == $bankaccount->billingaccountid)
            <input type="text" wire:model="bankaccount.billingaccount">
            @error('bankaccount.billingaccount') <small class="text-danger">{{$message}}</small> @enderror
        @else
            {{ $bankaccount->billingaccount }}
        @endif
    </td>

    <td>
        @if ($isEdit == $bankaccount->billingaccountid)
            <input type="text" wire:model="bankaccount.accountname">
            @error('bankaccount.accountname') <small class="text-danger">{{$message}}</small> @enderror
        @else
            {{ $bankaccount->accountname }}
        @endif
    </td>

    <td>
        @if ($isEdit == $bankaccount->billingaccountid)
            <input type="text" wire:model="bankaccount.accountnumber">
            @error('bankaccount.accountnumber') <small class="text-danger">{{$message}}</small> @enderror
        @else
            {{ $bankaccount->accountnumber }}
        @endif
    </td>

    <td>
        @if ($isEdit == $bankaccount->billingaccountid)
            <input type="text" wire:model="bankaccount.bankname">
            @error('bankaccount.bankname') <small class="text-danger">{{$message}}</small> @enderror
        @else
            {{ $bankaccount->bankname }}
        @endif
    </td>

    <td>
        @if ($isEdit == $bankaccount->billingaccountid)
            <button type="button" class="btn btn-sm btn-primary" wire:click="update({{$bankaccount->billingaccountid}})">
                Save
            </button>
            <button type="button" class="btn btn-sm btn-danger" wire:click="close()">
                Close
            </button>
        @else
            <button type="button" class="btn btn-sm btn-success" wire:click="edit({{ $bankaccount->billingaccountid }})">
                Edit
            </button>
        @endif
    </td>

</tr>