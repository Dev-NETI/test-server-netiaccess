<div class="col-md-12 mt-3">
    <label class="float-start">Template</label>
    <select class="form-control" wire:model="billing_statement_template">
            <option value="1">Peso</option>
            <option value="2">USD</option>
            <option value="3">USD w/ VAT</option>
    </select>
    <small class="text-success">{{$update_message}}</small>
</div>
