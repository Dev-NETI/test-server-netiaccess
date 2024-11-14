<div wire:ignore.self class="modal fade" id="enrollCrew" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">JISS/PJMCC Trainee List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-hover table-lg">
                    <thead>
                        <tr>
                            <th>FullName</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trainees as $item)
                        <tr>
                            <td>{{$item->l_name}} {{$item->f_name}}</td>
                            <td>{{$item->company->company}}</td>
                            <td><button class="btn btn-sm btn-info">Enroll</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                {{ $trainees->links('livewire.components.customized-pagination-link2', ['pagename' => 'trainees'])}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>