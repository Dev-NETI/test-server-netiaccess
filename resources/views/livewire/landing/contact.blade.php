<main class="bg-dark">
  <div class="contact-section py-lg-13">

    <div class="container">
      <div class="row align-items-center">
        <!-- User info -->
        <span wire:loading>
          <livewire:components.loading-screen-component />
        </span>
        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-12">
          <div class="text-center mb-6 px-md-6">

            <h1 class="text-white display-3 fw-semibold">
              Contact Us
            </h1>
            <p class="text-white lead mb-8">
              Feel free to reach out to us with any questions, comments, or suggestions. Our dedicated team is here to
              assist you. We look forward to hearing from you.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="mt-n8">
    <!-- container  -->
    <div class="container">
      <div class="card rounded-3 shadow-sm">
        <div class="row align-center">
          <div class="col-md-4  col-12 border-end-md ">
            <!-- features  -->
            <div class=" border-bottom border-bottom-md-0 mb-3 mb-lg-0">
              <div class="p-5 text-center">
                <div class="mb-4">

                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-info">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                    </path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>
                </div>
                <!-- heading  -->
                <h3 class="fw-semibold"><a href="help-center-faq.html" class="text-inherit">Phone</a></h3>
                <!-- para  -->
                <p>Manila Line: (632) 908-4900 <br>
                  Canlubang Line: (6349) 508-8600</p>

              </div>
            </div>
          </div>
          <div class="col-md-4  col-12  border-end-md">
            <!-- features  -->
            <div class="border-bottom border-bottom-md-0 mb-3 mb-lg-0">
              <div class="p-5  text-center">
                <div class="mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-info">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
                </div>
                <!-- heading  -->
                <h3 class="fw-semibold"><a href="#" class="text-inherit">Email</a>
                </h3>
                <!-- para  -->
                <p>inquiry@neti.com.ph</p>
              </div>
            </div>
          </div>
          <div class="col-md-4  col-12">
            <div>
              <div class="p-5  text-center">
                <div class="mb-4">
                  <!-- features  -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-info">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
                </div>
                <!-- heading  -->
                <h3 class="fw-semibold"><a href="help-center-support.html" class="text-inherit">Address</a></h3>
                <!-- para  -->
                <p>NYK-TDG I.T. Park,
                  Knowledge Avenue, Carmeltown, Canlubang Calamba City 4027, Laguna, Philippines</p>
                <!-- button  -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="bg-white py-lg-3 py-12 bg-cover ">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row">
        <div class="col-lg-6 col-12">
          <div>
            <div class=" text-center text-md-start ">
              <div class="card mb-4">
                <!-- Card Header -->
                <div class="card-header">
                  <h3 class="mb-0">Inquire</h3>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <form wire:submit.prevent="addinquiry" class="row mt-2">
                    @csrf
                    <!-- form group -->
                    <div class="mb-3 col-12 col-md-12">
                      <label class="form-label" for="name"> Name:<span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="name" placeholder="Let us know your name" wire:model.defer="name" required />
                    </div>

                    <!-- form group -->
                    <div class="mb-3 col-12 col-md-6">
                      <label class="form-label" for="email">Email:<span class="text-danger">*</span></label>
                      <input class="form-control" type="email" name="email" placeholder="example@email.com" wire:model.defer="email" required />
                    </div>
                    <!-- form group -->
                    <div class="mb-3 col-12 col-md-6">
                      <label class="form-label" for="phone">Phone Number:<span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="phone" placeholder="Phone" wire:model.defer="mobile" required />
                    </div>
                    <div class="mb-3 col-12 col-md-12">
                      <label class="form-label" for="company">Company<span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="company" placeholder="Company" wire:model.defer="company" required />
                    </div>
                    <!-- form group -->
                    <div class="mb-3 col-12">
                      <label class="text-dark form-label"> Inquiry Type:<span class="text-danger">*</span></label>
                      <select class="form-select" data-width="100%" wire:model.defer="selectedInquiryType" required>
                        <option value="" selected>--Select option--</option>
                        @foreach ($inquirytype as $inquirytypes)
                        <option value="{{$inquirytypes->inquirytypeid}}">{{$inquirytypes->inquirytype}}</option>
                        @endforeach
                      </select>

                    </div>
                    <!-- form group -->
                    <div class="mb-3 col-12">
                      <label class="text-dark form-label" for="messages">Message:</label>
                      <textarea class="form-control" rows="3" placeholder="Messages" wire:model.defer="message" required></textarea>
                    </div>
                    <!-- button -->
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary btn-block">
                        Send Inquiry
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-12">
          <div class="card-body">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3867.8367934610724!2d121.07681127507509!3d14.204332786236384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd63354b7a93eb%3A0xcd3e62ad1faa5570!2sNYK-FIL%20Maritime%20E-Training%2C%20inc.!5e0!3m2!1sen!2sph!4v1693119978662!5m2!1sen!2sph" class="embed-responsive-item" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="border:0; height: 60vh; width: 100%;"></iframe>

          </div>
        </div>
      </div>
    </div>
  </div>
</main>