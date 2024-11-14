<section>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-12 mb-5 mt-5">
                <div class="card">
                    <!-- card header  -->
                    <div class="card-header">
                        <h4 class="mb-1">Authenticated Certificate</h4>
                    </div>
                    <!-- table  -->
                    <div class="table-responsive">
                        <table class="table table-borderless text-nowrap mb- table-centered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Certificate No.</th>
                                    <th>Registration No.</th>
                                    <th>Course Name</th>
                                    <th>Issued Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class=" lh-1">
                                                <h5 class="mb-1"> {{$this->cert_history->trainee->certificate_name()}} <img src="{{ asset('assets/images/oesximg/verified.png') }}" width="15" alt=""> </h5>
                                            </div>
                                        </div>
                                    </td>

                                    <td>{{$this->cert_history->certificatenumber}}</td>
                                    <td>{{$this->cert_history->cln_type}} - {{$this->cert_history->registrationnumber}}</td>
                                    <td>{{$this->cert_history->course->full_course_name}}</td>
                                    <td>{{$this->cert_history->enrolled->schedule->enddateformat}}</td>
                                    <td><span class="badge bg-success">Verified</span></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</section>