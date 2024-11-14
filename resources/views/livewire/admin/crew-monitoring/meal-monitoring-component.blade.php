<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Meal Monitoring
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item-active">
                                <a href="#">Monitoring</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <button wire:click="changeForm('local')"
                        class="btn btn-sm {{$local ? 'btn-info disabled' : 'btn-outline-info'}}">Local</button>
                    <button wire:click="changeForm('foreign')"
                        class="btn btn-sm {{$foreign ? 'btn-success disabled' : 'btn-outline-success'}}">Foreign</button>
                </div>
                @if ($local)
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" id="inputGroup-sizing-lg"><i
                                        class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control" wire:model.debounce.900ms="enroledid" autofocus
                                    placeholder="Local Scan Barcode Here">
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-1">
                            <x-request-message-sm />
                        </div>
                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" id="inputGroup-sizing-lg">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" class="form-control" wire:model.debounce.900ms="enroledidForeign"
                                    autofocus placeholder="Foreign Scan/Type Barcode Here">
                                <select class="form-select" wire:model="mealtype">
                                    <option value="0">Select meal type</option>
                                    <option value="1">Breakfast</option>
                                    <option value="2">Lunch</option>
                                    <option value="3">Dinner</option>
                                </select>
                                <input type="date" class="form-control flatpickr" wire:model="foreignDate">
                                <button class="btn btn-primary" wire:click="saveForeignMeal">Save</button>
                            </div>
                            @error('enroledidForeign')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror
                            @error('mealtype')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror
                            @error('foreignDate')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>
                        <div class="col-md-4 offset-md-1">
                            <x-request-message-sm />
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Scanned Info List</h3>
                </div>
                <div class="card-body row">

                    <div class="col-md-12">
                        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal"
                            data-bs-target="#ExportModal" title="Export">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>

                    <div class="col-md-3 offset-md-8">
                        <input wire:model.debounce.500ms="search" class="form-control" placeholder="Search name">
                    </div>

                    <div class="col-md-10 offset-md-1 table-responsive">

                        <table class="table table-hover table-striped text-nowrap mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th hidden>RecordID</th>
                                    <th hidden>EnroledID</th>
                                    <th>Fullname</th>
                                    <th>Phone Number</th>
                                    <th>Training</th>
                                    <th>Meal Type</th>
                                    <th>Date Scanned</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $x = 1;
                                @endphp
                                @if (count($mealrecords) > 0)
                                @foreach ($mealrecords as $data)
                                <tr>
                                    <td>{{ $x }}</td>
                                    <td hidden>{{ optional($data)->id ?? '' }}</td>
                                    <td hidden>{{ optional($data)->enroledid ?? '' }}</td>
                                    <td>{{ optional($data)->enrolinfo->trainee->name_for_meal ?? ''}}
                                    </td>
                                    <td>{{ optional($data)->enrolinfo->trainee->mobile_number ?? '' }}</td>
                                    <td>{{ optional($data)->enrolinfo->course->full_course_name ?? '' }}
                                    </td>
                                    <td>{{ optional($data)->meal_type_desc ?? '' }}</td>
                                    <td>{{ optional($data)->created_date ?? '' }}</td>
                                    <td>{{ optional($data)->created_at ?? '' }}</td>
                                </tr>
                                @php
                                $x++;
                                @endphp
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td colspan="7">No Records Found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row mt-5" style="padding-bottom: 6.5em;">
                        {{ $mealrecords->links('livewire.components.customized-pagination-link') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:admin.crew-monitoring.meal-components.export-component />
</section>