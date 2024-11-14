<div>

    <div class="py-lg-14 bg-light pt-8 pb-10">
        
        <div class="container">
          <div class="row">
            
            <div class="col-md-12 col-12">

              <div class="row text-center">
                <div class="col-md-12 px-lg-10 mb-8 mt-6">
                    
                  <h2 class="h1 fw-bold mt-3">Bank Account Management
                  </h2>
                  
                  <p class="mb-0 fs-4">Here you can edit the information of Bank Account.</p>

                </div>
              </div>

              <div class="row gy-4">
                                    
                                    <div class="col-md-4 offset-md-8 ">
                                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#AddBankModal">
                                            Add Bank
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <x-request-message />
                                    </div>
                                    <div class="col-md-12 table-responsive bg-white border-1 rounded-end">
                                        <table class="table table-hover table-striped mt-4">
                                                <thead>
                                                        <tr>
                                                                <th>Account</th>
                                                                <th>Account Name</th>
                                                                <th>Account Number</th>
                                                                <th>Bank</th>
                                                                <th>Action</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($bankaccounts as $bankaccount)
                                                            <livewire:admin.billing.child.bank-account.bank-account-list-component :bankaccount="$bankaccount" wire:key="{{$bankaccount->billingaccountid}}" />
                                                    @endforeach
                                                </tbody>
                                        </table>  
                                    </div>    
              </div>

            </div>
          </div>

        </div>
      </div>
      
      {{-- ADD BANK ACCOUNT MODAL --}}
      <livewire:admin.billing.child.bank-account.create-bank-account-component />
      {{-- ADD BANK ACCOUNT MODAL --}}
</div>
