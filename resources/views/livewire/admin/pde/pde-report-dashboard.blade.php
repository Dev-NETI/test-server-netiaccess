<!-- Container fluid -->
<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE REPORT </h1>
                   
                </div>
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="py-12">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-12 px-lg-10 mb-8">
                      <!-- text -->
    
                      <!-- heading -->
    
                      <h2 class="h1 fw-bold mt-3">PDE Report Monitoring
                      </h2>
                      <!-- text -->
    
                      <p class="mb-0 fs-4">Here you can monitor the status of PDE .</p>
    
                    </div>
                  </div>

                <div class="row row-cols-1 row-cols-4 g-4">

                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.pdereportassessment') }}" class="card-img-top"><img
                                    src="{{ asset('assets/images/oesximg/pdecover/assessment-blue.svg') }}" alt=""
                                    class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Step 1</span>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.pdereportassessment') }}"
                                        class="text-inherit">Assessment </a></h4>    
                            </div>
                             <!-- Card Footer -->
                             <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.pdereportassessment') }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.pdereportcertificate') }}" class="card-img-top"><img
                                    src="{{ asset('assets/images/oesximg/pdecover/certificate-yellow.svg') }}" alt=""
                                    class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Step 2</span>
                                    <a href="{{ route('a.pdereportcertificate') }}" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.pdereportcertificate') }}"
                                        class="text-inherit">Certificates </a></h4>    
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.pdereportcertificate') }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.pdereporthistory') }}" class="card-img-top"><img
                                    src="{{ asset('assets/images/oesximg/pdecover/history-green.svg') }}" alt=""
                                    class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Logs</span>
                                    <a href="{{ route('a.pdereporthistory') }}" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href="{{ route('a.pdereporthistory') }}"
                                        class="text-inherit">History </a></h4>    
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.pdereporthistory') }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Card -->
                        <div class="card card-hover">
                            <a href="{{ route('a.pdereports') }}" class="card-img-top"><img
                                    src="{{ asset('assets/images/oesximg/pdecover/reports-red.svg') }}" alt=""
                                    class="rounded-top-md card-img-top"></a>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info-soft">Generate logs</span>
                                    <a href="" class="text-muted fs-5"><i class="fe fe-heart align-middle"></i></a>
                                </div>
                                <h4 class="mb-2 text-truncate-line-2 "><a href=""
                                        class="text-inherit">Reports </a></h4>    
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h5 class="mb-0"></h5>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ route('a.pdereports') }}" class="text-inherit">
                                            <i class="fe fe-arrow-right text-primary align-middle me-2"></i>View More 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>