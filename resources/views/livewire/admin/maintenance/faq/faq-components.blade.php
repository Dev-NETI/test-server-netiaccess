<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> FAQ Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.maintenance') }}">Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                FAQ Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add New
                        FAQ</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                        @csrf
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search FAQs">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover">
                        <thead class="table-light">
                            <tr>

                                <th>NO.</th>
                                <th>QUESTIONS</th>
                                <th>STATUS</th>
                                <th>ACTION</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faq_maintenance as $faq_maint)
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <h5 class="mb-0 text-primary-hover">{{ $loop->index + 1}}</h5>
                                        </a>
                                    </td>
                        
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <h5 class="mb-0 text-primary-hover">{{ $faq_maint->question }}</h5>
                                        </a>
                                    </td>
                        
                                    <td>
                                        @if ($faq_maint->statusid == 0)
                                            <span class="badge bg-success">Publish</span>
                                        @else
                                            <span class="badge bg-danger">Unpublished</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span> Setting
                                            </button>
                                            <ul class="dropdown-menu">
                                                <span class="dropdown-header">Action</span>
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal"
                                                            wire:click="faqupdate({{ $faq_maint->faqid}})">
                                                        <i class="fe fe-edit dropdown-item-icon"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" wire:click="deleteConfirmation({{ $faq_maint->faqid }})">
                                                        <i class="fe fe-trash dropdown-item-icon"></i> Delete
                                                    </a>
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit FAQ Modal -->
    <div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Edit Question
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="faqupdate1">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Question<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="question" required>
                        </div>

                        <div class="mb-3 mb-3">
                            <label class="form-label">Answer</label>
                            {{-- <div id="editor"> --}}
                                <textarea class="form-control" wire:model="answer"></textarea>
                                {{-- <textarea class="form-control" id="editor" rows="5" wire:model="answer"></textarea>
                                --}}
                                {{--
                            </div> --}}

                            {{-- <div wire:ignore>
                                <textarea class="form-control" id="answerdescription" wire:model.defer="answer"></textarea>
                              </div> --}}
                            
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Update Question</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit FAQ Modal -->


    <!-- ADD FAQ Modal -->
    <div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add New FAQ
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit="faqadd">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Question<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="addquestion" required>


                        </div>

                        <div class="mb-3 mb-3">
                            <label class="form-label">Answer</label>
                            {{-- <div id="editor"> --}}
                                <textarea class="form-control" wire:model.defer="addanswer"></textarea>
                                {{-- <textarea class="form-control" id="editor" rows="5" wire:model="answer"></textarea>
                                --}}
                                {{--
                            </div> --}}
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Add new FAQ</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD FAQ Modal -->



</section>
@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        $('#answerdescription').summernote({
            height: 300, // Set your preferred height
            callbacks: {
                // Update Livewire property when Summernote content changes
                onChange: function (contents) {
                    @this.set('answer', contents);
                }
            }
        });
    });
</script>
@endpush