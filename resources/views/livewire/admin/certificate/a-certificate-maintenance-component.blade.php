<section class="container-fluid p-4">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Certificate Maintenance
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Maintenance<a href="#"></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Certificate Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-12">
            <div class="card">
                <!-- table  -->
                <div class="card-body">
                    <div class="table-card">
                        <table id="dataTableBasic" class="table table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>COURSE TYPE</th>
                                    <th>COURSE CODE</th>
                                    <th>COURSE NAME</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td class="text-uppercase">{{ $course->type->coursetype }}</td>
                                        <td class="text-uppercase">{{ $course->coursecode }}</td>
                                        <td class="fst-italic">{{ $course->coursename }}</td>
                                        <td>
                                            <a href="{{ route('a.certificatesmainshow', ['course_id' => $course->courseid]) }}"
                                                class="btn btn-sm btn-warning text-wrap">Edit Certificate</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
                $('#dataTableBasic').DataTable();
        });
    </script>
@endpush
