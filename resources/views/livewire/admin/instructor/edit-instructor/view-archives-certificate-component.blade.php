<main>
    <section class="container-fluid p-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-0 h2 fw-bold">Archives</h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('a.instructor') }}">Instructor</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Archives
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- card -->
                <div class="mb-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <p class="h3">Archived Licenses<span class="fw-normal">
                                            ({{ $archiveslicense->total() }})</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- table  -->
                            <div class="card-body">
                                <div class="table-card table-responsive">
                                    <table wire:ignore.self class="table table-hover" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Action</th>
                                                <th>Accreditation Number</th>
                                                <th>Accreditation</th>
                                                <th>Accreditation Type</th>
                                                <th>Issuing Authority</th>
                                                <th>Date Issued</th>
                                                <th>Expiration Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($archiveslicenseall != 0)
                                                @foreach ($archiveslicense as $archiveslicenses)
                                                    <tr>
                                                        <td>
                                                            @if ($archiveslicenses->licensepath != NULL)
                                                                <a class="btn btn-info" href="/storage/uploads/instructorlicenses/{{$archiveslicenses->licensepath}}" target="_blank">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                            @else
                                                                <a class="btn btn-info" href="/storage/uploads/instructorlicenses/{{$archiveslicenses->d_name}}" target="_blank">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        @if ($archiveslicenses->licensenumber)
                                                            <td>{{ $archiveslicenses->licensenumber }}</td>
                                                        @else
                                                            <td class="text-danger">Not Specified</td>
                                                        @endif
                                                        <td>{{ $archiveslicenses->license }}</td>
                                                        <td>{{ $archiveslicenses->instructorlicensetype->licensetype }}
                                                        </td>
                                                        <td>{{ $archiveslicenses->issuingauthority }}</td>
                                                        <td>{{ $archiveslicenses->dateofissue }}</td>
                                                        <td>{{ $archiveslicenses->expirationdate }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if ($archiveslicenseall != 0)
                            <div class="card-footer">
                                <div class="row">
                                    {{ $archiveslicense->links('livewire.components.customized-pagination-link') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
