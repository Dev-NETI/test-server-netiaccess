<div class="container-fluid p-4">
    <div class="row ">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> Landing Page Cover
                        </h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('a.maintenance') }}">Maintenance</a>
                                </li>

                                <li class="breadcrumb-item active" aria-current="page">
                                    Landing Page Cover Maintenance
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addcover"> Upload New Cover</a>
                    </div>

                    {{-- add cover modal --}}
                    <!-- Modal -->
                        <div wire:ignore.self class="modal fade" id="addcover" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">New Cover</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="addcoverpic" id="addcoverpic" enctype="multipart/form-data">
                                        @csrf
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" wire:model.defer="covertitle">
                                        <label for="">Choose File</label>
                                        <input type="file" class="form-control" wire:model.defer="coverfile">

                                    </form>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" form="addcoverpic" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4">

        @foreach ($landingpagelist as $landingpagelists)
        <div class="col mb-4">
            <!-- card -->
            <div class="card h-100">
                {{-- <div> --}}
                    {{-- <a href="product-single.html"> --}}
                     
                        {{-- <a href="{{ asset($landingpagelists->filepath) }}" class="glightboxGallery"
                            data-gallery="gallery1">
                            <img src="{{ asset($landingpagelists->filepath) }}" alt="image"
                                class="img-fluid rounded-3 w-100">
                        </a> --}}

                        {{-- <a href="/storage/uploads/landingcover/{{$landingpagelists->filepath}}" class="glightboxGallery"
                            data-gallery="gallery1">
                            <img src="/storage/uploads/landingcover/{{$landingpagelists->filepath}}" alt="image"
                                class="img-fluid rounded-3 w-100">
                        </a>
                     
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-primary">New</span>
                        </div>
                    </a>
                </div> --}}

                <div>
                    <a href="product-single.html">
                        <a href="{{ asset('storage/uploads/landingcover/'.$landingpagelists->filepath) }}" class="glightboxGallery"
                            data-gallery="gallery1">
                            {{-- <img src="{{ url('assets/images/oesximg/landingcover/'.$landingpagelists->filepath) }}" alt="image" --}}
                                {{-- class="img-fluid rounded-3 w-100"> --}}
                                {{-- <img src="{{ asset('storage/uploads/landingcover/'.$landingpagelists->filepath) }}" alt="image"
                                class="img-fluid rounded-3 w-100"> --}}
                                <img src="{{ asset('storage/uploads/landingcover/' . $landingpagelists->filepath) }}" alt="image"
                                class="img-fluid rounded-3 w-100">
                           
                        </a>
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-primary">New</span>
                        </div>
                    </a>
                </div>
                
                <!-- card body -->
                <div class="card-body">
                    <div class="mb-4">
                        <h4><a href="product-single.html" class="text-inherit">{{ $landingpagelists->title }}
                            </a></h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <!-- color -->
                        <div>
                            <a href="#" class="btn btn-danger"
                                wire:click="landingdelete({{ $landingpagelists->homapageid }})"><i
                                    class="fe fe-trash me-2"></i>Remove</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
