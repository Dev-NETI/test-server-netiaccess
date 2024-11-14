<div class="col-xl-3 col-lg-6 col-md-6 col-12 mt-5" wire:click="passSessionData({{$billingstatusid}})">
    <!-- card -->

    <div class="card border-top border-muted border-4 card-hover-with-icon card-img-top">

        <div class="card-header text-center bg-primary">
            <span class="rounded-top-md card-mg-top mt-5">
                <i class="{{$icon}}" style="color: antiquewhite;font-size:70px;"></i>
            </span>
            <h4 class="mt-2 text-white">
                {{$process}}
            </h4>
        </div>

        <!-- card body -->
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-info-soft">
                    {{$step}}
                </span>

            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-muted">
                    <h2>{{count($trainee_data)}}</h2>
                    </p>
                </div>

                <!-- arrow -->
                <a class="text-inherit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                        </path>
                    </svg>
                </a>

            </div>

        </div>

    </div>

</div>