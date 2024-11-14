<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-5">
                <h4>NYKSM Companies</h4>
            </div>
            <div class="col-lg-7">
                <button class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#modalAddC">
                    <span class="h4 text-white"><i class="bi bi-plus"></i></span>
                    Add
                    Company</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Company</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $index => $data)
                    @php
                    $index++;
                    @endphp
                    <tr>
                        <td>{{ $index }}</td>
                        <td>{{ $data->companyinfo->company }}</td>
                        <td>
                            <button wire:click="removeC({{$data->companyid}})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $companies->links('livewire.components.customized-pagination-link2', ['pagename' => 'companiesPage']) }}
    </div>

    <div wire:ignore.self class="modal fade" id="modalAddC" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl    modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Available Companies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table table-hover border">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($companiesFA->count() == 0)
                            <tr>
                                <td class="text-center" colspan="2">No available company</td>
                            </tr>
                            @else
                            @foreach ($companiesFA as $index => $data)
                            <tr>
                                <td>{{$data->company}}</td>
                                <td><button wire:click="addC({{$data->companyid}})" class="btn btn-sm btn-primary"><i
                                            class="bi bi-plus"></i></button></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    {{ $companiesFA->links('livewire.components.customized-pagination-link2', ['pagename' =>
                    'companiesFAPage']) }}
                </div>
            </div>
        </div>
    </div>
</div>