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
                                        <img src="/storage/uploads/traineepic/{{Auth::guard('trainee')->user()->imagepath}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @else
                                        <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @endif
                                </div>
                                <div class="lh-1">
                                    <h2 class="mb-0">{{Auth::guard('trainee')->user()->formal_name()}}
                                    </h2>
                                    <p class=" mb-0 d-block"><i>RANK: {{Auth::guard('trainee')->user()->rank->rank}} - {{Auth::guard('trainee')->user()->rank->rankacronym}}</i> </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{route('t.editprofile')}}" class="btn btn-primary btn-sm d-none d-md-block">Account
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
            <div class="row gx-3">

                {{-- overview --}}
                <div class="col-xl-4 col-md-4 col-6 mb-3 ">
                    <!-- card card-borderd  -->
                    <a wire:click.prevent="link('t.lms-courseinfo')" class="card h-100 card-hover border" style="background-image: url('{{asset('assets/images/oesximg/widget.jpg')}}');
                        background-size: cover;
                        background-position: center;">
                        <!-- card body  -->
                        <div class="card-body">
                            <i class="bi bi-calendar-range-fill fs-1 mb-3 text-white"></i>
                            <br>
                            <br>
                            <small class="text-white"></small>
                            <h3 class="text-white">Course Information</h3>
                            <p class="mb-0 text-white">Access detailed course descriptions to make informed decisions about your maritime training.</p>
                        </div>
        
                    </a>
                </div>


                {{-- zoom --}}
                <div class="col-xl-4 col-md-4 col-6 mb-3 ">
                    <!-- card card-borderd  -->
                    <a data-bs-toggle="modal" data-bs-target="#zoomModal" class="card h-100 card-hover border" 
                    style="background-image: url('{{asset('assets/images/oesximg/widget.jpg')}}');
                        background-size: cover;
                        background-position: center;">
                        <!-- card body  -->
                        <div class="card-body">
                            <i class="bi bi-calendar-range-fill fs-1 mb-3 text-white"></i>
                            <br>
                            <br>
                            <small class="text-white"></small>
                            <h3 class="text-white">Zoom meeting credentials</h3>
                            <p class="mb-0 text-white">Zoom meeting credentials provide you with the necessary access codes and links to join virtual training sessions.</p>
                        </div>
        
                    </a>
                </div>


                {{-- Syllabus --}}
                <div class="col-xl-4 col-md-4 col-6 mb-3 ">
                    <!-- card card-borderd  -->
                    <a wire:click.prevent="link('t.lms-syllabus')" class="card h-100 card-hover border" style="background-image: url('{{asset('assets/images/oesximg/widget.jpg')}}');
                        background-size: cover;
                        background-position: center;">
                        <!-- card body  -->
                        <div class="card-body">
                            <i class="bi bi-calendar-range-fill fs-1 mb-3 text-white"></i>
                            <br>
                            <br>
                            <small class="text-white"></small>
                            <h3 class="text-white">Syllabus</h3>
                            <p class="mb-0 text-white">The Syllabus section offers a comprehensive outline of the course structure, topics, and learning objectives, ensuring you have a clear roadmap for your maritime education.</p>
                        </div>
        
                    </a>
                </div>

                {{-- people --}}
                <div class="col-xl-4 col-md-4 col-6 mb-3 ">
                    <!-- card card-borderd  -->
                    <a wire:click.prevent="link('t.lms-people')" class="card h-100 card-hover border" style="background-image: url('{{asset('assets/images/oesximg/widget.jpg')}}');
                        background-size: cover;
                        background-position: center;">
                        <!-- card body  -->
                        <div class="card-body">
                            <i class="bi bi-calendar-range-fill fs-1 mb-3 text-white"></i>
                            <br>
                            <br>
                            <small class="text-white"></small>
                            <h3 class="text-white">People</h3>
                            <p class="mb-0 text-white">Connect with your fellow maritime learners.</p>
                        </div>
        
                    </a>
                </div>

                
            </div>
        </div>



        {{-- zoom modal --}}
        <div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Zoom meeting credentials</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php echo $courses->course->meetingcredentials; @endphp
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>


    </section>
</main>