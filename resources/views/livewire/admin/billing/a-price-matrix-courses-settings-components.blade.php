{{-- <div>

  <div class="py-lg-14 bg-light pt-8 pb-10">

    <div class="container"> --}}

      <div class="card text-center">

        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <h2 class="h1 fw-bold mt-3">Price Matrix for {{ $company_data->company }}
              </h2>
              <p class="mb-0 fs-4">Here you can manage pricing for {{ $company_data->company }}.</p>
            </div>
          </div>
        </div>
        <div class="card-body row">
          <x-request-message />

          @if ($companyid != 89 && $companyid != 262)
          <div class="12">
            <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addmodal">
              <i class="mdi mdi-library-plus"></i> Add Matrix
            </a>
          </div>
          @else
          <div class="12">
            <a href="#" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#addforeignmodal">
              <i class="mdi mdi-library-plus"></i> Add Matrix
            </a>
          </div>
          @endif

          <div class="col-md-12 table-responsive mt-5">

            <table class="table table-hover table-striped text-sm">
              <thead>
                @if ($companyid == 262 || $companyid == 89)
                <tr>
                  <th scope="col">Course </th>
                  <th scope="col">Course Rate</th>
                  <th scope="col">Check In AM Charge</th>
                  <th scope="col">Check In PM Charge</th>
                  <th scope="col">Check Out AM Charge</th>
                  <th scope="col">Check Out PM Charge</th>
                  <th scope="col">Breakfast Rate</th>
                  <th scope="col">Lunch Rate</th>
                  <th scope="col">Dinner Rate</th>
                  <th scope="col">Dorm Rate</th>
                  <th scope="col">Meal Rate</th>
                  <th scope="col">Transpo Fee</th>
                  <th scope="col" style="min-width: 200px">Format</th>
                  <th scope="col" style="min-width: 200px">Template</th>
                  <th scope="col">Bank Charge</th>
                  <th scope="col">Action</th>
                </tr>
                @else
                <tr>
                  <th scope="col">Course</th>
                  <th scope="col">TFee in ₱</th>
                  <th scope="col">TFee in $</th>
                  <th scope="col">Meals in ₱</th>
                  <th scope="col">Meals in $</th>
                  <th scope="col">Dorm in ₱</th>
                  <th scope="col">Dorm in $</th>
                  <th scope="col">Dorm 2s in ₱</th>
                  <th scope="col">Dorm 2s in $</th>
                  <th scope="col">Dorm 4s in ₱</th>
                  <th scope="col">Dorm 4s in $</th>
                  <th scope="col">Transpo in ₱</th>
                  <th scope="col">Transpo in $</th>
                  <th scope="col" style="min-width: 200px">Format</th>
                  <th scope="col" style="min-width: 200px">Template</th>
                  <th scope="col">Bank Charge</th>
                  <th scope="col">wDMT (<span><strong class="text-danger">For JISS</strong></span>)</th>
                  <th scope="col">Action</th>
                </tr>
                @endif
              </thead>
              <tbody>
                @if (in_array($companyid, $nykComps))
                @foreach ($courses as $course)
                <livewire:admin.billing.child.course-price.foreign-course-price-list-component :course="$course"
                  wire:key="{{rand(0,99999)}}" />
                @endforeach
                @else
                @foreach ($courses as $course)
                <livewire:admin.billing.child.course-price.course-price-list-component :course="$course"
                  wire:key="{{$course->companycourseid}}" />
                @endforeach
                @endif
              </tbody>
            </table>
          </div>

        </div>

        <div class="card-footer text-body-secondary">
        </div>
        <!--Add Price Modal-->
        <livewire:admin.billing.child.course-price.create-price-component />

        {{-- Add Price Modal Foreign --}}
        <livewire:admin.billing.child.course-price.create-foreign-price-matrix :companyid="$companyid" />
        {{-- Settings Modal --}}
        <livewire:admin.billing.child.course-price.settings-modal-component :companyid="$companyid" />

      </div>

      {{--
    </div>

  </div>

</div> --}}