@props(['model', 'model1', 'model2'])
<div {{$attributes}}>
    <div class="card-header p-1">
        <x-label class="h3 p-1 m-1">
            Foreign
        </x-label>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <div class="input-group input-group-lg">
                <input type="number" wire:model="{{$model}}" class="form-control" placeholder="Scan Barcode Here">
                <input type="number" wire:model="{{$model1}}" class="form-control" placeholder="Number of trainees">
            </div>
            <div class="d-grid gap-2 text-center">
                <button wire:click="{{$model2}}" class="btn btn-primary mt-2">Add</button>
            </div>
        </div>
    </div>

    {{$slot}}
</div>