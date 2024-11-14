<main>
    <section class="py-md-20 py-12 h-90"
        style="position: relative; overflow: hidden; background: linear-gradient(to right, #030d1b, transparent), rgba(0, 0, 0, 0.3);">
        <div class="landing-page-picture"
            style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, #030d1b, transparent), rgba(0, 0, 0, 0.3);">
            <img src="assets/videos/landing_page_poster-dark.jpg" alt="Responsive Image"
                style="width: 100%; height: 100%; object-fit: cover; background-color: rgba(0, 0, 0, 0.6);">
        </div>

        <div class="video-container">
            <div class="landing-page-video">
                <video poster="{{asset('assets/videos/landing_page_poster.jpg')}}" autoplay="true" width="100%" loop muted >
                    <source src="{{asset('assets/videos/landing_page_video.mp4')}}" type="video/mp4">
                </video>
            </div>
        </div>

        <div class="container align-items-center z-30">
            <div class="row">
                <div class="col-xxl-10 col-xl-6 col-lg-6 col-12 bg-transparent"
                    style="background-color: rgba(0, 0, 0, 0.6);">
                    <div class="animated-text">
                        <h1 class="display-1 fw-bold mb-3 text-white" style="font-size: 80px;">Preferred Provider of
                            <br>
                            <u class="text-warning"><span class="text-info mt-2" style="font-size: 70px;">Quality
                                    Maritime Training</span></u>
                        </h1>
                        <a href="{{ route('t.login') }}" class="btn btn-info btn-lg mt-2">Enroll Now</a>
                    </div>
                </div>
                <div class="col-xxl-6 offset-xxl-1 col-xl-6 col-lg-6 col-12 d-lg-flex justify-content-end">
                    <div></div>
                </div>
            </div>
        </div>
    </section>


    <section id="courses" class="py-4 py-lg-12 bg-primary">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-3 fw-bold text-white py-2">Training Courses</h1>
                </div>
            </div>
            <div class="row row-cols-lg-12 row-cols-1">
                @if($Coursetype->count())
                @foreach ($Coursetype as $Coursetypes)
                <div class="col-md-4 mb-2 mt-4">
                    <a href="{{ route('courseslist', ['hashid' => $Coursetypes->hash_id]) }}"
                        class="card h-100 card-hover border"
                        style="background-image: url({{ $Coursetypes->image }}); background-size: cover; background-position: center;"
                        loading="lazy">
                        <div class="card-body">
                            <br><br>
                            <h2 class="text-white">{{ $Coursetypes->coursetype }}</h2>
                            <p class="mb-0 text-white">
                                @if ($Coursetypes->description)
                                {{ substr($Coursetypes->description, 0, 24) }}<br>
                                {{ substr($Coursetypes->description, 24) }}
                                @else
                                <!-- Handle the case where description is null -->
                                No description available.
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
                @else
                <!-- Handle the case where there are no CourseTypes -->
                @endif
            </div>

        </div>
    </section>
    <style>
        @media (max-width: 1400px) {
           /* Hide the video container */
           .video-container {
               display: none;
           }
       
           /* Show the picture instead */
           .landing-page-picture {
               display: block !important; /* Add !important to override any conflicting styles */
           }
       
           /* Adjust text size */
           .display-1 {
               font-size: 30px !important;
           }
       
           .text-info {
               font-size: 35px !important;
           }
           /* Adjust button size */
           .btn-info {
               font-size: 15px !important;
           }
       }
       </style>
</main>
