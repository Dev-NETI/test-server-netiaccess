<div class="container-fluid mt-4">
    <div class="row gx-2">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3>Company</h3>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search company" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="SearchC" />
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $value)
                            <tr class="{{$value->id == session('activejisscompany') ? 'table-active' : ''}}">
                                <td>{{$value->company}}</td>
                                <td><button wire:click="selectCompany({{$value->id}})" class="btn btn-primary btn-sm {{$value->id == session('activejisscompany') ?
                                        'disabled' : ''}}">{{$value->id == session('activejisscompany') ?
                                        '------' : 'Select'}}</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $companies->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5 text-start">
                            <h3>Emails
                                @if (session('activejisscompany'))
                                <span class="h4"> - {{$company->company}}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="col-lg-7 text-end">
                            @if (session('activejisscompany'))
                            <button class="btn btn-md btn-info" wire:click="openModalAddEmail"><i
                                    class="bi bi-plus me-1"></i> Add</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($emails->count() > 0)
                            @foreach ($emails as $value)
                            <tr>
                                <td>{{$value->email}}</td>
                                <td><button wire:click="openModalUpdateEmail({{$value->id}})"
                                        class="btn btn-primary btn-sm"><i class="bi bi-pencil m-1"></i>
                                        Edit</button></td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="2">Nothing to show</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- Modals Here --}}
    <div wire:ignore.self class="modal fade" id="addEmailModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$emailEditID == null ? 'Add' : 'Update'}} Company
                        Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                @if ($emailEditID)
                <form action="" wire:submit.prevent='executeUpdateEmail'>
                    @else
                    <form action="" wire:submit.prevent='executeAddEmail'>
                        @endif
                        @csrf
                        <div class="modal-body">
                            <label for="" class="form-label h4">Email Address @error($emailtxt)
                                <span class="text-danger"><small>({{$message}})</small></span>
                                @enderror</label>
                            <input type="email" wire:model.lazy="emailtxt" class="form-control"
                                placeholder="Enter email here ..">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{$emailEditID == null ? 'Add' : 'Update'}}
                                Email</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>