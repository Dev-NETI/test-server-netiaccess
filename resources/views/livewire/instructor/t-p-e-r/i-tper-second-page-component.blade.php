<section class="container-fluid p-4">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        My class
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('i.dashboard') }}">Instructor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">TPER</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $enroled_data->schedule->course->coursename }}
                                
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="float-left"><i> SCHEDULE # : {{$enroled_data->schedule->scheduleid}}</i></h5>
                    <div class="text-center">
                            <h2>Trainee's Performance Evaluation Report</h2>
                            <h6>for 
                                {{ $enroled_data->trainee->rank->rankacronym }}
                                {{ $enroled_data->trainee->l_name }}, {{ $enroled_data->trainee->f_name }} {{ $enroled_data->trainee->m_name }} {{ $enroled_data->trainee->suffix }}
                            </h6>
                    </div>

                    <div class="container mt-5 row">
                                <x-error-message />
                                <form wire:submit.prevent="store">
                                        <div class="col-md-6 offset-md-3">
                                                <label class="form-label">Trainee's Weak Points</label>
                                                <textarea class="form-control {{$errors->has('trainee_weak_points') ? 'is-invalid' : ''}}" wire:model="trainee_weak_points" cols="30" rows="10"></textarea>
                                                @error('trainee_weak_points') <span class="error text-danger">{{$message}}</span> @enderror
                                        </div>
                                        <div class="col-md-6 offset-md-3 mt-3">
                                                <label class="form-label">General Comments</label>
                                                <textarea class="form-control {{$errors->has('general_comments') ? 'is-invalid' : ''}}" wire:model="general_comments" cols="30" rows="10"></textarea>
                                                @error('general_comments') <span class="error text-danger">{{$message}}</span> @enderror
                                        </div>
                                        <div class="col-md-6 offset-md-3 mt-3 d-flex align-items-center">
                                            <label class="form-label me-5 mb-0">Required for re-training</label>
                                            <select class="form-control {{$errors->has('re_training') ? 'is-invalid' : ''}}" wire:model="re_training">
                                                <option >Select</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                            @error('re_training') <span class="error text-danger">{{$message}}</span>@enderror
                                        </div>
                                        <div class="col-md-6 offset-md-3 mt-3">
                                                <button type="submit" class="btn btn-primary float-end">Save</button>
                                        </div>
                                </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

  


</section>