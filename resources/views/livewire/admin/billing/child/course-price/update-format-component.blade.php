<div class="col-md-12 mt-3">
    <label class="float-start">Format</label>
    <select class="form-control" wire:model="billing_statement_format">
            <option value="1">Single billing Statement</option>
            <option value="2">Individual Billing Statement</option>
            <option value="3">Individual Billing Statements with Vessel</option>
    </select>
    <small class="text-success">{{$update_message}}</small>
</div>
