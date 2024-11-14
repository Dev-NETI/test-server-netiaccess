<div wire:ignore.self class="tab-pane fade pb-3" id="ins" role="tabpanel" aria-labelledby="ins-tab">
    <div class="row">
        <div class="row mb-2">
            <div class="col-lg-12 mt-2">
                <label for="">
                    <h4>Active Licenses: </h4>
                </label>
                @foreach ($user->instructor->instructorlicense as $data)
                @if ($data->expirationdate > now())
                <button class="btn btn-sm btn-success">
                    {{$data->instructorlicensetype->licensetype}}
                </button>
                @endif
                @endforeach
            </div>
        </div>
        <hr>
        <div class="table-responsive col-lg-12 mt-2">
            <table class="table table-sm text-nowrap border table-hover mb-0 table-centered" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3">List of Instructor's Accredited Courses</th>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <th>Course Code</th>
                    </tr>
                </thead>
                <tbody class="" style="font-size: 15px;" width="100%">
                    @foreach ($instructorcourses as $instructorcourse)
                    <tr>
                        <td>{{$instructorcourse->courses->coursename}}</td>
                        <td>{{$instructorcourse->courses->coursecode}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>