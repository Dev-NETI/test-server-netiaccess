<section class="container-fluid p-4">
  <div class="row">
      <span wire:loading>
          <livewire:components.loading-screen-component />
      </span>
      <!-- Page Header -->
      <div class="col-lg-12 col-md-12 col-12">
          <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
              <div class="mb-3 mb-md-0">
                  <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> PDE Certificate Maintenance </h1>
                  <!-- Breadcrumb -->
                  <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item">
                              <a href="{{ route('a.maintenance') }}">Maintenance</a>
                          </li>

                          <li class="breadcrumb-item active" aria-current="page">
                              PDE List
                          </li>
                      </ol>
                  </nav>
              </div>
              {{-- <div>
                  <a href="#" class="btn btn-primary me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" >Add New Courses</a>
              </div> --}}
          </div>
      </div>
  </div>

  <div class="row">
      <div class="col-lg-12 col-md-12 col-12">
          <!-- Card -->
          <div class="card mb-4">
              <!-- Card header -->
              <div class="card-header p-0">
                  <div>
                      <!-- Nav -->
                      <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                          <li class="nav-item">
                              <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses" role="tab" aria-controls="courses" aria-selected="true">All()</a>
                          </li>
                          

                      </ul>
                  </div>

                  <div class="p-4 row">
                      <!-- Form -->
                      <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                          <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                          <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                              placeholder="Search Rank">
                      </form>
                  </div>

              </div>
              <!-- Table -->

              <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                  <table class="table mb-0 text-nowrap table-centered table-hover" >
                      <thead class="table-light">
                          <tr>
                             
                              <th>No</th>
                              <th>Action</th>
                              <th>Rank Code</th>                      
                              <th>Rank </th>
                           
                          </tr>
                      </thead>
                      <tbody>
                    
                              <tr>
                                 <td></td>  
                                 <td></td>                        
                                 <td></td>          
                                 <td></td> 
                                                 
                                 
                              </tr>
                            
                      </tbody>
                      
                  </table>
                  <div class="card-footer">
                      <div class="row">
                          {{-- {{ $retrievecourses->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}} --}}

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



  <!-- Offcanvas -->

 <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" style="width: 600px;"> 
      <div class="offcanvas-body" data-simplebar>
        <div class="offcanvas-header px-2 pt-0">
          <h3 class="offcanvas-title" id="offcanvasExampleLabel">Add New Course</h3>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- card body -->
        <div class="container">
          <form id="addnewcourse" wire:submit.prevent="addnewcourse">
          <div class="row">
            <div class="mb-3 col-6">
              <label class="form-label">Course Code <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addcoursecode" class="form-control" placeholder="Enter course code" required>
            </div>

            <div class="mb-3 col-12">
              <label class="form-label">Course Name <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addcoursename"  class="form-control" placeholder="Enter course name" required>
            </div>

            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Department</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selecteddepartment">
                <option value="" >Select Course Department</option>
               
                  <option value=""></option>
              
              </select>
            </div>
            
            
            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Type</label>
              <select class="form-select text-black" data-width="100%"  wire:model.defer="selectedcoursetype">
                <option value="" >Select Course Type</option>
            
                <option value=""></option>
             
              </select>
              
            </div>


            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Level</label>
              <select class="form-select text-black" data-width="100%"  wire:model.defer="selectedcourselevel">
                <option value="" >Select Course Level</option>
          
                <option value=""></option>
                
              </select>
            </div>

            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Vessel Type</label>
              <select class="form-select text-black" data-width="100%"  wire:model.defer="selectedvesseltype">
                  <option value="" >Select Vessel Type</option>
          
                <option value=""></option>
              
              </select>
            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Training Days <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addtrainingdays" class="form-control" placeholder="Enter No of Training Days" required>
            </div>

            <div class="mb-3 col-6">
              
            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Minimum No. of Trainees <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addmintrainees"  class="form-control" placeholder="Min No. of Trainees" required>
            </div>

            <div class="mb-3 col-6">
              <label class="form-label">Maximum No. of Trainees <span class="text-danger">*</span></label>
              <input type="text" wire:model.defer="addmaxtrainees" class="form-control" placeholder="Max No. of Trainees" required>
          </div>
            

            <div class="mb-3 col-md-6 col-12">
              <label class="form-label">Course Location</label>
              <select class="form-select text-black" data-width="100%" wire:model.defer="selectedcourselocation">
                  <option value="">Select Location</option>
                 
                      <option value=""></option>
              
              </select>
          </div>
          
          
            <div class="mb-3 col-md-6 col-12">   
                  <label class="form-label">Mode of Delivery</label>
                  <select class="form-select text-black" data-width="100%" wire:model.defer="selectedmod">
                    <option value="" >Select Mode of Delivery</option>
                  
                      <option value=""></option>
                 
                  </select>
                </div>
                
            
        
            <div class="col-md-8"></div>
       
            <div class="col-12">
              <button class="btn btn-primary" type="submit">Submit</button>
              <button type="button" class="btn btn-outline-primary ms-2" data-bs-dismiss="offcanvas"
                aria-label="Close">Close</button>
            </div>
          </div>
      </form>
        </div>
      </div>
    </div>


  <!-- Edit Course Modal -->
<div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title mb-0" id="newCatgoryLabel">
                  Edit Course
              </h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
          </div>
          <div class="modal-body">
              <form id="updatecourse" wire:submit.prevent="updatecourse">
                  <div class="row">
                    <div class="mb-3 col-6">
                      <label class="form-label">Course Code <span class="text-danger">*</span></label>
                      <input type="text" wire:model.defer="editcoursecode" class="form-control" placeholder="Enter course code" required>
                    </div>
      
                    <div class="mb-3 col-12">
                      <label class="form-label">Course Name <span class="text-danger">*</span></label>
                      <input type="text" wire:model.defer="editcoursename"  class="form-control" placeholder="Enter course name" required>
                    </div>
      
                    <div class="mb-3 col-md-6 col-12">
                      <label class="form-label">Course Department</label>
                      <select class="form-select text-black" data-width="100%" wire:model.defer="editselecteddepartment">
                        <option value="" >Select Course Department</option>
                       
                          <option value=""></option>
                      
                      </select>
                    </div>
                    
                    
                    <div class="mb-3 col-md-6 col-12">
                      <label class="form-label">Course Type</label>
                      <select class="form-select text-black" data-width="100%"  wire:model.defer="editselectedcoursetype">
                        <option value="" >Select Course Type</option>
                      
                        <option value=""></option>
                       
                      </select>
                      
                    </div>
      
      
                    <div class="mb-3 col-md-6 col-12">
                      <label class="form-label">Course Level</label>
                      <select class="form-select text-black" data-width="100%"  wire:model.defer="editselectedcourselevel">
                        <option value="" >Select Course Level</option>
               
                        <option value=""></option>
                  
                      </select>
                    </div>
      
                    <div class="mb-3 col-md-6 col-12">
                      <label class="form-label">Vessel Type</label>
                      <select class="form-select text-black" data-width="100%"  wire:model.defer="editselectedvesseltype">
                          <option value="" >Select Vessel Type</option>
                        
                        
                        <option value=""></option>
                     
                      </select>
                    </div>
      
                    <div class="mb-3 col-6">
                      <label class="form-label">Training Days <span class="text-danger">*</span></label>
                      <input type="text" wire:model.defer="edittrainingdays" class="form-control" placeholder="Enter No of Training Days" required>
                    </div>
      
                    <div class="mb-3 col-6">
                      
                    </div>
      
                    <div class="mb-3 col-6">
                      <label class="form-label">Minimum No. of Trainees <span class="text-danger">*</span></label>
                      <input type="text" wire:model.defer="editmintrainees"  class="form-control" placeholder="Min No. of Trainees" required>
                    </div>
      
                    <div class="mb-3 col-6">
                      <label class="form-label">Maximum No. of Trainees <span class="text-danger">*</span></label>
                      <input type="text" wire:model.defer="editmaxtrainees" class="form-control" placeholder="Max No. of Trainees" required>
                  </div>
                    
      
                    <div class="mb-3 col-md-6 col-12">
                      <label class="form-label">Course Location</label>
                      <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedcourselocation">
                          <option value="">Select Location</option>
                      
                              <option value=""></option>
                         
                      </select>
                  </div>
                  
                  
                    <div class="mb-3 col-md-6 col-12">   
                          <label class="form-label">Mode of Delivery</label>
                          <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedmod">
                            <option value="" >Select Mode of Delivery</option>
                            
                              <option value=""></option>
                     
                          </select>
                        </div>
                        
                    
                
                    <div class="col-md-8"></div>
               
                    <div class="col-12">
                      <button class="btn btn-primary" type="submit">Submit</button>
                      <button type="button" class="btn btn-white ms-2" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
<!-- Edit Course Modal -->

</section>