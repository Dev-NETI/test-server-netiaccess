<div wire:ignore.self class="tab-pane fade" id="ins" role="tabpanel" aria-labelledby="ins-tab">
    <div class="row">


                    <div class="col-lg-4 pb-4">
                        <form id="courseadd" wire:submit.prevent="coursesadd">
                            @csrf
                        <!-- Accordion Example -->
                        <div class="row">
                            <div class="col-3 pt-2">
                            <label for="" class="form-label">
                                <h3>Select Courses:</h3>
                            </label>
                            </div>
                            <div class="col-9">
                                <button class="btn btn-primary mt-3" type="submit">Save</button>
                            </div>
                        </div>
                        <div class="accordion pt-2" id="accordionExample">
                            @foreach ($tblcoursetype as $tblcoursetypes)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$tblcoursetypes->coursetypeid}}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#accordion{{$tblcoursetypes->coursetypeid}}" aria-expanded="false" aria-controls="acc{{$tblcoursetypes->coursetypeid}}">
                                        <strong class="text-black">{{$tblcoursetypes->coursetype}}</strong>
                                    </button>
                                </h2>
                                <div id="accordion{{$tblcoursetypes->coursetypeid}}" class="accordion-collapse collapse" aria-labelledby="heading{{$tblcoursetypes->coursetypeid}}"
                                    data-bs-parent="#accordionExample">

                                        <div class="accordion-body">
                                            @foreach ($tblcoursetypes->courses as $course)
                                                <div class="form-check">
                                                    <input class="form-check-input" wire:model.defer="coursesid.{{ $course->courseid }}" type="checkbox" id="{{$course->courseid}}">
                                                    <label class="form-check-label" for="{{$course->courseid}}">
                                                        {{$course->coursecode}} {{$course->coursename}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        </div>
                        </form>
                    </div>

                                            {{-- <form id="courseadd" wire:submit.prevent="coursesadd">
                                            <div class="row">
                                                @foreach ($tblcourses as $tblcourse)
                                                    <div class="col-6 mt-2">
                                                        <!-- Checks -->
                                                        <div class="form-check">
                                                            <input class="form-check-input" wire:model.defer="coursesid.{{ $tblcourse->courseid }}" type="checkbox" id="{{$tblcourse->courseid}}">
                                                            <label class="form-check-label" for="{{$tblcourse->courseid}}">
                                                                {{$tblcourse->coursecode}} {{$tblcourse->coursename}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </form> --}}
        <div class="table-responsive col-lg-8 mt-5">
            <table class="table table-sm text-nowrap border table-hover mb-0 table-centered" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3">List of Instructor's Accredited Courses</th>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <th>Course Code</th>
                        <th class="text-center">Checkbox</th>
                    </tr>
                </thead>
                <form wire:submit.prevent="removecourse">
                    @csrf
                    <tbody class="" style="font-size: 15px;" width="100%">
                            @foreach ($instructorcourses as $instructorcourse)
                                <tr>
                                    {{-- <td><button class="btn btn-sm btn-danger" wire:click="removecourse({{$instructorcourse->instructorcoursesid}})">Remove</button></td> --}}
                                    <td>{{$instructorcourse->courses->coursename}}</td>
                                    <td>{{$instructorcourse->courses->coursecode}}</td>
                                    <td class="text-center"><input id="deletecourse" wire:model.defer="removecourseid.{{$instructorcourse->instructorcoursesid}}" type="checkbox" class="form-check-input"></td>
                                </tr>
                            @endforeach
                        {{-- <tr class="text-center">
                            <td colspan="3">-----No Records Found-----</td>
                        </tr> --}}
                    </tbody>
                </table>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-12 text-end ">
                            <button class="btn btn-sm btn-danger" type="submit">Remove</button>
                        </div>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>
