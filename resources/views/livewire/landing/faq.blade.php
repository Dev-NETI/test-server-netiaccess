<section class="py-8 bg-dark">
  <div class="container">
    <div class="row">
      <div class="offset-md-2 col-md-8 col-12">
        <!-- caption-->
        <h1 class="fw-bold mb-1 display-4 text-white">Frequently Asked Questions (FAQs)</h1>
      </div>
    </div>
  </div>
</section>
 <!-- container  -->

<section class="py-lg-10 py-7 bg-white">
  <div class="container">
    <div class="row">
      <div class="offset-md-2 col-md-8 col-12">
        <div class="mb-4">
          <h2 class="mb-0 fw-semibold">General inquiries</h2>
        </div>
         <!-- accordion  -->
        <div class="accordion accordion-flush" id="accordionExample2">
          @foreach ( $faq_content as $faq_contents)

          <div class="border p-3 rounded-3 mb-2" id="headingEight">
            <h3 class="mb-0 fs-4">
              <a href="#" class="d-flex align-items-center text-inherit
                text-decoration-none"data-bs-toggle="collapse" data-bs-target="#{{ $faq_contents->faqid}}" aria-expanded="false" aria-controls="{{ $faq_contents->faqid}}">
                <span class="me-auto">
                  {{ $faq_contents->question}}
                </span>
                <span class="collapse-toggle ms-4">
                  <i class="fe fe-chevron-down text-muted"></i>
                </span>
              </a>
            </h3>
            <div id="{{ $faq_contents->faqid}}" class="collapse"aria-labelledby="headingEight" data-bs-parent="#accordionExample2">
              <div class="pt-2">
                {!! $faq_contents->answer !!}
                {{-- {{ $faq_contents->answer}} --}}
              </div>
            </div>
          </div>
          <!-- Card  -->
            
          @endforeach
        
         
        </div>
      </div>
    </div>
   
  </div>
</section>
