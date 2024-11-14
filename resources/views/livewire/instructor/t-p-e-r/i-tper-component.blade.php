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
                            <h3>Trainee's Performance Evaluation Report</h2>
                            <h5>for 
                                {{ $enroled_data->trainee->rank->rankacronym }}
                                {{ $enroled_data->trainee->l_name }}, {{ $enroled_data->trainee->f_name }} {{ $enroled_data->trainee->m_name }} {{ $enroled_data->trainee->suffix }}
                            </h6>
                    </div>

                    <div class="container mt-5 row">
                        
                        <x-error-message />
                        
                            <div class="d-flex justify-content-end col-md-12">
                                <span class="badge text-bg-danger me-1">1 - Strongly Disagree</span>
                                <span class="badge text-bg-warning me-1">2 - Disagree</span>
                                <span class="badge text-bg-secondary me-1">3 - Neutral</span>
                                <span class="badge text-bg-info me-1">4 - Agree</span>
                                <span class="badge text-bg-primary">5 - Strongly Agree</span>
                            </div>

                            <div class="col-md-12 mt-2">
                                <ul class="list-group list-group-flush">
                                    <form wire:submit.prevent="submitForm" id="form_evaluation">
                                        @csrf
                                        @php $group_id = 1; @endphp
                                        @foreach ($tper_factor_data as $data)
                                            <li class="list-group-item">
                                                {{$data->name}}
                                                <div class="float-end">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="inlineCheckbox{{$group_id}}_{{$i}}" wire:model="selected_radio{{$group_id}}" value="{{$i}}">
                                                            <label class="form-check-label" for="inlineCheckbox{{$group_id}}_{{$i}}">{{$i}}</label>
                                                        </div>
                                                    @endfor
                                                    @error("selected_radio{$group_id}")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </li>
                                            @php $group_id++; @endphp
                                        @endforeach
                                    </form>
                                </ul>
        
                                <button type="submit" form="form_evaluation" class="btn btn-primary float-end" wire:loading.attr="disabled">Next</button>
                            </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

  


</section>