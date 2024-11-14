<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Instructor's Profile</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Instructor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.instructor')}}">Instructor List </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Instructor
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
                    <div class="card-body">
                        <div class="card-header text-center">
                            {{-- @dd($file); --}}
                            <img id="profile-image" src="{{ $file }}" class="avatar-xl rounded-circle" alt="">
                            <div class="ms-4">
                                <!-- text -->
                                <h3 class="mb-1 mt-1">{{$user->f_name}} {{$user->l_name}}</h3>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <form id="profile-image-form" wire:submit.prevent="profile"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="file" required wire:model.defer="profilepic"
                                                class="form-control" accept="image/png, image/jpg, image/jpeg"
                                                id="image-input">
                                            <button class="btn btn-secondary" form="profile-image-form" type="submit"
                                                id="upload-button"><i class="bi bi-upload"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            @if (Auth::user()->u_type == 3)
                            @if ($user->instructor->regularid == 1)

                            @else
                            <a class="btn btn-success m-1"
                                href="{{ route('c.information-sheets', ['hashid' => $user->hash_id]) }}"
                                target="_blank">{{$user->fulladdress}}F-NETI-019</a>
                            <a class="btn btn-info m-1" target="_blank">Y-BOD-12</a>
                            @endif
                            <a href="{{ route('c.edit-certificate', ['hashid' => $user->hash_id]) }}"
                                class="btn btn-primary m-1">Accreditation</a>
                            <a href="{{ route('c.edit-ocertificatelicenses', ['hashid' => $user->hash_id]) }}"
                                class="btn btn-warning m-1">Certificates & Licenses</a>
                            @else
                            @if ($user->instructor->regularid == 1)

                            @else
                            <a class="btn btn-success m-1"
                                href="{{ route('a.information-sheets', ['hashid' => $user->hash_id]) }}"
                                target="_blank">{{$user->fulladdress}}F-NETI-019</a>
                            <a class="btn btn-info m-1" target="_blank">Y-BOD-12</a>
                            @endif
                            <a href="{{ route('a.edit-certificate', ['hashid' => $user->hash_id]) }}"
                                class="btn btn-primary m-1">Accreditation</a>
                            <a href="{{ route('a.edit-ocertificatelicenses', ['hashid' => $user->hash_id]) }}"
                                class="btn btn-warning m-1">Certificates & Licenses</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            {{--
            <x-request-message /> --}}
            <div class="card">
                <div wire:ignore class="card-body pb-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Educ. Background</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab"
                                aria-controls="contact" aria-selected="false">Legal Dependents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="emp-tab" data-bs-toggle="tab" href="#emp" role="tab"
                                aria-controls="emp" aria-selected="false">Emp. Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ins-tab" data-bs-toggle="tab" href="#ins" role="tab"
                                aria-controls="ins" aria-selected="false">Instructor Course</a>
                        </li>
                    </ul>
                </div>
                <div wire:ignore.self class="card-body pt-1 pb-0">
                    <div class="tab-content" id="myTabContent">
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.hometab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.eductab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.legaldeptab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.emptab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.instcoursetab2')


                    </div>
                </div>
            </div>
        </div>
    </div>



</section>