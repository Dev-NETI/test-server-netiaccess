<main>
    <section class="pt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <!-- Bg -->
                    <div class=" pt-16 rounded-top-md " style="
                    background: url(../assets/images/background/profile-bg.jpg) no-repeat;
                    background-size: cover;">
                    </div>
                    <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                        <div class="d-flex align-items-end justify-content-between  ">
                            <div class="d-flex align-items-center">
                                <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                    @if (Auth::guard('trainee')->user()->imagepath)
                                    <img src="/storage/uploads/traineepic/{{Auth::guard('trainee')->user()->imagepath}}"
                                        class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @else
                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}"
                                        class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @endif
                                </div>
                                <div class="lh-1">
                                    <h2 class="mb-0">{{Auth::guard('trainee')->user()->formal_name()}}
                                    </h2>
                                    <p class=" mb-0 d-block"><i>RANK: {{Auth::guard('trainee')->user()->rank->rank}} -
                                            {{Auth::guard('trainee')->user()->rank->rankacronym}}</i> </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{route('t.editprofile')}}"
                                    class="btn btn-primary btn-sm d-none d-md-block">Account
                                    Setting</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Content -->
    <section class="pb-5 py-md-5">
        <div class="container">
            <div class="row bg-white">

                <div class="col-md-12">
                    <p class="h1 text-center">{{ $courses->course->coursename }}</p>
                    <p class="h5">About this course</p>
                    <p class="h5">@php echo $courses->course->aboutcourse; @endphp</p>
                </div>


                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-hover ">
                        <tbody>
                            @foreach ($coursematrix as $matrix)
                            <tr>
                                <td class="text-primary">{{ $matrix->title }}</td>
                                <td class="text-justify">{{ $matrix->content }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Course outline --}}
                <div class="col-md-12 mb-2">
                    <p class="h5">Course Outline</p>

                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        @php $accordCount = 1; @endphp
                        @foreach($courseoutline as $outline)


                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapse{{$accordCount}}" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseOne">
                                    {{ $outline->day }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapse{{$accordCount}}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <?php echo $outline->content; ?>
                                </div>
                            </div>
                        </div>

                        @php $accordCount++; @endphp

                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </section>
</main>