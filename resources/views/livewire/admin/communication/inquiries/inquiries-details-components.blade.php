<section class="container-fluid p-4">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-12">
      <div class="border-bottom pb-3 mb-3">
        <div class="mb-3 mb-lg-0">
          <h1 class="mb-0 h2 fw-bold">View Email Inquiry</h1>

        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
      <!-- Card -->
      <div class="card">
        <!-- Card body -->
        <div class="row g-0">

          <div class="col-xxl-12 col-xl-12 col-12">
            <div>
              <!-- card header -->
              <div class="card-header">
                <div class="d-md-flex justify-content-between
                                        align-items-center">
                  <div class="d-flex mb-3 mb-md-0">
                    <div>
                      <a href="{{ Route('a.inquiries') }}" class="btn btn-outline-secondary btn-sm fs-5"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Back to inbox">
                        <i class=" fe fe-arrow-left "></i></a>
                    </div>
                  </div>
               
                </div>
              </div>
              <!-- card body -->
              <div class="card-body">
                <div class="d-xl-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center mb-3 mb-xl-0">
                    <!-- img -->
                    <div>
                      <img src="{{ asset('../../assets/images/avatar/avatar.jpg') }}" alt=""
                        class="rounded-circle avatar-md">
                    </div>
                    <!-- sidebar -->
                    <div class="ms-3 lh-1">
                      <h5 class="mb-1">{{ $emailinquiry->name }}</h5>
                      <p class="mb-0 fs-6">{{ $emailinquiry->email }} | {{ $emailinquiry->mobile }}</p>
                    </div>

                  </div>
                  <!-- text -->
                  <div class="d-flex align-items-center">
                    <div>
                      <small class="text-muted">
                        {{ \Carbon\Carbon::parse($emailinquiry->created_at)->timezone('Asia/Manila')->format('F j, Y
                        \a\t h:i A') }}
                        ({{
                        \Carbon\Carbon::parse($emailinquiry->created_at)->timezone('Asia/Manila')->diffForHumans(\Carbon\Carbon::now())
                        }})
                      </small>
                    </div>

                  </div>
                </div>
                <!-- text -->
                <div class="mt-6">

                  <p>{{ $emailinquiry->inquiry_text }}</p>
                </div>

              </div>
            </div>
            <!-- card footer -->
            <div class="card-footer py-4">
              <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $emailinquiry->email }}&cc=bod@neti.com.ph,prpd@neti.com.ph,noc@neti.com.ph"
                target="_blank" class="btn btn-outline-secondary btn-sm fs-5 me-2 mb-2 mb-md-0"><i class="mdi
                       mdi-reply-outline me-2"></i>Reply</a>

              <a href="https://mail.google.com/mail/?view=cm&fs=1&cc=bod@neti.com.ph,prpd@neti.com.ph,noc@neti.com.ph&body={{ $emailinquiry->inquiry_text }}"
                target="_blank" class="btn btn-outline-secondary btn-sm fs-5 me-2 mb-2 mb-md-0"><i class="mdi
              mdi-arrow-right-bold-outline me-2"></i>Forward</a>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>