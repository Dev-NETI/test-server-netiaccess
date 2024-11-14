<div>
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-5">
                            <h2 class="fw-bold">Enrollment Application</h2>
                            <p class="mb-0">View all enrollment application</p>
                        </div>
                    </div>
                    <div class="col-12 table-responsive">
                        <!-- striped rows -->
                        <table class="table table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="input-group mb-1">
                                            <label class="input-group-text" for="inputGroupSelect01">Row Count</label>
                                            <select wire:model="rowcount" class="form-select" id="inputGroupSelect01">
                                                <option selected>5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="70">70</option>
                                                <option value="100">100</option>
                                            </select>
                                            </div>
                                        </div>
                                        @if ($user->user_id != 214 && $user->user_id != 215)
                                            <div class="col-sm-2">
                                                <select wire:model="filterfleet" class="form-select" id="inputGroupSelect01">
                                                    <option selected value="">Filter By Fleet</option>
                                                    @if ($user->u_type == 5)
                                                        <option value="NTMA">NTMA</option>
                                                        <option value="Technical (DRY)">TECHNICAL (DRY)</option>
                                                        <option value="Technical (LIQUID)">TECHNICAL (LIQUID)</option>
                                                    @elseif ($user->u_type == 4)
                                                        <option value="NTMA">NTMA</option>
                                                        <option value="NTMA-NETI">NTMA-NETI</option>
                                                    @endif
                                                </select>
                                            </div>   
                                        @endif                                  
                                        <div class="col-sm-3 ms-auto">
                                            <div class="input-group mb-1 float-right">
                                            <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-search"></i></label>
                                            <input type="text" class="form-control" wire:model.debounce.1000ms="searchtext" placeholder="Search from First, Last, Middle Name">
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                <tr>
                                    @if ($bybatchcheckbox)
                                        <div class="col-sm-2 mb-1">
                                            <button class="btn btn-sm btn-danger" wire:click="togglebatch">Untoggle by Batch</button>
                                        </div>
                                    @else
                                        <div class="col-sm-2 mb-1">
                                            <button class="btn btn-sm btn-primary" wire:click="togglebatch">Toggle by Batch</button>
                                        </div>
                                    @endif 
                                </tr>
                            </thead>
                            <thead class="border shadow">
                            <tr>
                                @if ($bybatchcheckbox)
                                <th scope="col"><input type="checkbox" class="form-check-input" wire:model="checkall"></th>
                                @endif
                                <th scope="col">Enroled ID</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Fleet</th>
                                <th scope="col">Enrolled In</th>
                                <th scope="col">Start</th>
                                <th scope="col">End</th>
                                <th scope="col">Date Applied</th>
                                <th scope="col">Status</th>
                                @if ($bybatchcheckbox == false)
                                <th scope="col">Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="border shadow">
                                <form id="formcheckbox" wire:submit.prevent="formcheckbox('{{ $btnsubmit }}')" action="">
                                @csrf
                                @foreach ($trainee as $data)
                                    <tr>
                                        @if ($bybatchcheckbox)
                                        <td scope="col"><input id="checkbox{{$data->enroledid}}" type="checkbox" class="form-check-input" @if ($data->pendingid != 1) disabled @endif wire:model.defer="checkboxtd.{{$data->enroledid}}"></td>
                                        @endif
                                        <th scope="row">{{ $data->enroledid }}</th>
                                        <td>{{ optional($data->trainee)->l_name }}</td>
                                        <td>{{ optional($data->trainee)->f_name }}</td>
                                        <td>{{ optional($data->trainee)->m_name }}</td>
                                        <td>{{ $data->fleet }}</td>
                                        <td>{{ $data->course->coursecode }}</td>
                                        <td>{{ $data->schedule->startdateformat }}</td>
                                        <td>{{ $data->schedule->enddateformat }}</td>
                                        <td>{{ $data->dateapplied }}</td>
                                        @if ($data->pendingid == 1)
                                            <td class="text-warning"> Pending </td>
                                        @else
                                            <td class="text-success"> Approved </td>
                                        @endif
                                        @if ($bybatchcheckbox == false)
                                        <td>
                                            <button class="btn btn-sm mt-1 @if ($data->pendingid != 1) disabled btn-secondary @else btn-success @endif" wire:click.prevent="approve({{$data->enroledid}})"><i class="bi bi-check-circle-fill"></i></button>
                                            <button class="btn btn-sm mt-1 @if ($data->pendingid != 1) disabled btn-secondary @else btn-danger @endif" wire:click.prevent="reject({{$data->enroledid}})"><i class="bi bi-x-circle-fill"></i></button>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </form>
                            </tbody>
                        </table>
                        @if ($bybatchcheckbox)
                        <button class="btn btn-sm btn-success" type="submit" wire:click="approvebtn">Approved</button>
                        <button class="btn btn-sm btn-danger" type="submit" wire:click="rejectbtn">Reject</button>
                        @endif
                        {{-- <livewire:technical.dashboard.tech-enroll-table /> --}}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-5" style="padding-bottom: 6.5em;">
                    {{ $trainee->links('livewire.components.customized-pagination-link')}}
                </div>
            </div>
        </div>
    </div>
</div>