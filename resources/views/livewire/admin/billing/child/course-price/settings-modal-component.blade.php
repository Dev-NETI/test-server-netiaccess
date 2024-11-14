<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                
                      <livewire:admin.billing.child.course-price.update-currency-component :companyid="$companyid" />
                      <livewire:admin.billing.child.course-price.update-template-component :companyid="$companyid" />
                      <livewire:admin.billing.child.course-price.update-format-component :companyid="$companyid" />
                      <livewire:admin.billing.child.course-price.bank-charge-component :companyid="$companyid" />

            </div>
            <div class="modal-footer">
            </div>

        </div>
    </div>
</div>
