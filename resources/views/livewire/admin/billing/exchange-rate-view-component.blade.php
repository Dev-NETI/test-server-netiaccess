<div class="container mt-5">
    
    <div class="card text-center">

        <div class="card-header">
            <h2 class="fw-bold">USD Exchange Rate</h2>
        </div>
        <div class="card-body">
            <x-request-message />
            <form wire:submit.prevent="update">
                @csrf
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>Dollar</th>
                            <th>Peso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" wire:model="dollar" disabled>
                            </td>
                            <td>
                                <input type="text" wire:model="peso"
                                    @if ($edit === 0) disabled @endif>
                                @error('peso')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if ($edit === 0)
                    <button class="btn btn-success float-end mt-2" form="none" wire:click="edit()">Edit</button>
                @else
                    <button class="btn btn-primary float-end mt-2" id="liveToastBtn">Save</button>
                @endif

            </form>
        </div>

    </div>
</div>
