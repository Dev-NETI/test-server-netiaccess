<main>
    <section class="py-md-20 py-12 position-relative overflow-hidden"
        style="background-image: url('{{ asset('assets/images/oesximg/registration.svg') }}'); background-size: cover;">
        <div class="gradient-background1" style="z-index: -1;"></div> <!-- Gradient background here -->
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>

        {{-- <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4 col-lg-2 col-6 mb-4 mb-lg-0">
                    <img src="../assets/images/oesximg/logo.png" alt="logo">
                </div>
                <div class="col-md-8 col-lg-10">
                    <h1 class="display-3 ls-sm text-white">Online Enrollment System</h1>
                </div>
            </div>
        </div> --}}
        <div class="container">
            <!-- Hero Section -->


            <div class="row align-items-center ">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="mb-4 mb-xl-0 text-center text-md-start">
                        <!-- Caption -->
                        <h1 class="display-2 fw-bold mb-2 ls-sm text-white ">Create Your Training Account </h1>
                        <p class="mb-4 lead text-white">
                            To start your training journey, please create your account.
                        </p>

                        <a href="{{ route('t.login') }}" class="btn btn-info">Already have an account? Login here.</a>
                    </div>
                </div>
                <div class="offset-xl-1 col-xl-5 col-lg-6 col-md-12">
                    <!-- Card -->
                    <div class="card smooth-shadow-md" style="z-index: 1;">
                        <!-- Card body -->
                        <div class="card-body p-6">
                            {{-- <div class="row"><img src="../assets/images/oesximg/NETI.png" class="pt-2" alt="logo"
                                    width="300px"></a></div> --}}
                            <h1 class="mb-1 fw-bold">Sign up</h1>

                            <hr>
                            <div class="mb-4">
                                <span>Already have an account?
                                    <a href="{{ Route('t.login') }} " class="ms-1">Sign in</a></span>
                            </div>
                            <!-- Form -->
                            <form wire:submit.prevent="sendOtp" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Email Address -->
                                    <div class="mb-3 col-md-12">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" id="email" wire:model.defer="email"
                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            name="email" placeholder="Enter active email address ..">
                                        @error('email')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <!-- contact number -->
                                    <div class="mb-3 col-md-12">
                                        <label for="contact" class="form-label">Contact Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select wire:model="d_code"
                                                    class="form-control {{ $errors->has('d_code') ? 'is-invalid' : '' }}">
                                                    <option value="">Select Dialing Code</option>
                                                    @foreach ($dialing_code_data as $data)
                                                    <option value="{{$data->id}}">{{$data->country_code}}
                                                        (+{{$data->dialing_code}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="text" id="contact" wire:model.defer="p_number"
                                                class="form-control {{ $errors->has('p_number') ? 'is-invalid' : '' }}"
                                                name="p_number" placeholder="Enter phone number ..">
                                        </div>
                                        @error('d_code') <p class="text-danger">{{$message}}</p> @enderror
                                        @error('p_number') <p class="text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div>
                                    <hr>
                                    <!-- Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            Register Account
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer px-6 py-4">

                        </div>
                    </div>
                    <!-- Pattern -->
                    <div class="position-relative">
                        <div
                            class="position-absolute bottom-0 end-0 me-md-n3 mb-md-n6 me-lg-n4 mb-lg-n4 me-xl-n6 mb-xl-n8 d-none d-md-block ">
                            <img src="../assets/images/pattern/dots-pattern.svg" alt="" class="opacity-25">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>