<div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-6  col-12 ">
            <!-- caption-->
            <h1 class="fw-bold mb-1 display-3">Account Created Successfully! </h1>
            <!-- para -->
            <p class="mb-5 text-dark ">Please click the 'Sign In' button to proceed.</p>
            <div class="pe-md-6">
                <!-- input  -->
                <form class="d-flex align-items-center">
                    @csrf
                    <span class="position-absolute ps-3">
                        <i class="fe fe-search text-muted"></i>
                    </span>
                    <!-- input  -->
                    <a href="/trainee/login" class="btn btn-primary">Proceed to Sign in</a>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="d-flex align-items-center justify-content-end">
                <!-- img  -->
                <img src="../assets/images/svg/3d-girl-seeting.svg" alt="girlsetting" class="text-center img-fluid">
            </div>
        </div>
    </div>
</div>