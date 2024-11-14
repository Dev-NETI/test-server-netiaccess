<div  >

    <div class="modal fade" id="DataPrivacyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-body row">
                <div class="col-md-12"> @include('livewire.components.data-privacy-text') </div>
                <!-- <div class="col-md-12 text-center">
                    <input type="checkbox" wire:model="check_data_privacy" class="float-center" id="checkbox_dp">
                    <label for="checkbox_dp">"I Accept"</label>
                </div> -->
                <div class="d-grid gap-2 col-6 mx-auto">
                  <button class="btn btn-primary" wire:click="accept" type="button" id="acceptButton">I Accept</button>
                </div>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.addEventListener('livewire:load', function () {
              $(document).ready(function(){
                    $('#DataPrivacyModal').modal({
                        backdrop: 'static'  // Prevent closing on clicking outside
                        // keyboard: false      // Prevent closing with keyboard interactions
                    }).modal('show');

              });

            Livewire.on('closeModal', function () {
              $(document).ready(function(){
                $('#DataPrivacyModal').modal('hide');
              });
            });
        });
      </script>

</div>