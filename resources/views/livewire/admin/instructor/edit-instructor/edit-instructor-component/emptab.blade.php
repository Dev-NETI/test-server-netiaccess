<div wire:ignore.self class="tab-pane fade" id="emp" role="tabpanel" aria-labelledby="emp-tab">
                                <div class="row">
                                    <form id="addemploymentinfo" wire:submit.prevent="addemploymentinfo">
                                        @csrf
                                        <div class="row">
                                            <div class="col-4 mt-1">
                                                <label class="form-label" for="">Rank/Position Held</label>
                                                <input required type="text" class="form-control" wire:model.defer="rank">
                                            </div>
                                            <div class="col-4 mt-1">
                                                <label class="form-label" for="">Vessel Name/ Company</label>
                                                <input required type="text" class="form-control" wire:model.defer="vesselnc">
                                            </div>
                                            <div class="col-4 mt-1">
                                                <label class="form-label" for="">Vessel Type</label>
                                                <input required type="text" class="form-control" wire:model.defer="vesselt">
                                            </div>
                                            <div class="col-4 mt-1">
                                                <label class="form-label" for="">Inclusive Date</span></label>
                                                <input required type="text" class="form-control" wire:model.defer="inclusivedate">
                                            </div>
                                            <div class="col-12 d-grid">
                                                <button form="addemploymentinfo" type="submit" class="btn btn-primary mt-3">Add Information</button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr class="mt-3">
                                    <form id="updatedatetdg" wire:submit.prevent="datatoupdate">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6 mt-1">
                                                <label class="form-label" for="">Date Started w/ NYK-Fil/TDG</label>
                                                <input type="text" required class="form-control flatpickr" wire:model.defer="datestarted">
                                            </div>
                                            <div class="col-6 mt-1">
                                                <label class="form-label" for="">Award Received</label>
                                                <input type="text" class="form-control" wire:model.defer="award">
                                            </div>
                                            <div class="col-12 d-grid">
                                                <button class="btn btn-primary mt-3" form="updatedatetdg" type="submit">Update Information</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive mt-5">
                                        <table class="table table-sm text-nowrap border table-hover mb-0 table-centered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Rank/Position Held</th>
                                                    <th>Vessel Name/Company</th>
                                                    <th>Vessel Type</th>
                                                    <th>Inclusive Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="" style="font-size: 15px;">
                                                <tr class="text-center">
                                                    @if ($instructoremploymentinfo)
                                                        @foreach ($instructoremploymentinfo as $instructoremploymentinfos)
                                                            <tr>
                                                                <td><button class="btn btn-danger btn-sm" wire:click.prevent="confirmdelete({{$instructoremploymentinfos->employmentinformationid}})"><i class="bi bi-trash-fill"></i></button></td>
                                                                <td>{{$instructoremploymentinfos->rank}}</td>
                                                                <td>{{$instructoremploymentinfos->vessel}}</td>
                                                                <td>{{$instructoremploymentinfos->vesseltype}}</td>
                                                                <td>{{$instructoremploymentinfos->inclusivedate}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <td colspan="5">-----No Records Found-----</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="card-footer">
                                            <div class="row">


                                            </div>
                                        </div>
                                    </div>

                                </div>
                        </div>
