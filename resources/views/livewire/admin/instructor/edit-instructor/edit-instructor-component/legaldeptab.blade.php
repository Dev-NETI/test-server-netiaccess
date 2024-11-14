<div wire:ignore.self class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <form id="adddependents" wire:submit.prevent="adddependents">
            @csrf
            <div class="row">
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Dependent's Fullname</label>
                    <input required type="text" wire:model.defer="depfname" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Relationship</label>
                    <input required type="text" wire:model.defer="deprelate" class="form-control">
                </div>
                <div class="col-4 mt-1">
                    <label class="form-label" for="">Birthdate</label>
                    <input required type="date" wire:model.defer="depdob" class="form-control flatpickr">
                </div>
                <div class="col-12 mt-1">
                    <label class="form-label" for="">Address <span class="text-danger fs-6">(City, Province)</span></label>
                    <input required type="text" wire:model.defer="depadd" class="form-control">
                </div>
                <div class="col-12 d-grid">
                    <button type="submit" form="adddependents" class="btn btn-primary mt-2">Add Dependents</button>
                </div>
            </div>
        </form>
        <div class="table-responsive mt-5">
            <table class="table table-sm text-nowrap border table-hover mb-0 table-centered text-center" width="100%">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Relationship</th>
                        <th>Birth Date</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody class="" style="font-size: 15px;">
                        @foreach ($instructordependents as $instructordependents)
                                <tr>
                                    <td>
                                        <button class="btn btn-warning btn-sm" wire:click.prevent="editdep({{$instructordependents->instructordependentsid}})" data-bs-toggle="modal" data-bs-target="#editmodal">Edit</button>
                                        <button class="btn btn-danger btn-sm" wire:click.prevent="deleteconfirmdep({{$instructordependents->instructordependentsid}})">Delete</button>
                                    </td>
                                    <td>{{++$counter}}</td>
                                    <td>{{$instructordependents->dependentfullname}}</td>
                                    <td>{{$instructordependents->dependentrelationship}}</td>
                                    <td>{{$instructordependents->dependentbirthdate}}</td>
                                    <td>{{$instructordependents->dependentaddress}}</td>
                                </tr>
                        @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                <div class="row">


                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade gd-example-modal-lg text-start" tabindex="-1" role="dialog" id="editmodal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalToggleLabel">Edit Dependents</h3>
                    </div>
                    <div class="modal-body p-1">
                        <form id="updateform" wire:submit.prevent="depupdate">
                            @csrf
                            <label class="form-label" for="">Dependents Fullname</label>
                            <input class="form-control" wire:model.defer="editdepfullname" type="text">
                            <input hidden wire:model.defer="editdepid" type="text">
                            <label class="form-label mt-1" for="">Relationship</label>
                            <input class="form-control" wire:model.defer="editdeprelationship" type="text">
                            <label class="form-label mt-1" for="">Birthdate</label>
                            <input class="form-control flatpickr" wire:model.defer="editdepbirthdate" type="text">
                            <label class="form-label mt-1" for="">Address</label>
                            <input type="text" class="form-control" wire:model.defer="editdepaddress">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger"
                            data-bs-dismiss="modal">Close</button>
                        <button form="updateform" class="btn btn-info" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </div>
</div>
