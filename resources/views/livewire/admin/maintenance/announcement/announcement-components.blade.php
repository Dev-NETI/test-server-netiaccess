<section class="py-lg-14 py-8 bg-gray-200">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-12 col-12">
                <!-- heading -->
                <div class="text-center">
                    <h3 class="display-3 mb-3 fw-bold">Announcement</h3>
                    <p>Here you can edit announcements that are shown in the profile of trainees.</p>
                </div>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-xl-12 col-lg-8 col-md-12 col-12">
                <!-- Card -->
                <div class="card border-0 mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Announcement</h4>
                    </div>

                    <div class="card-body">
                        <form wire:submit.prevent="updateAnnouncement">
                            @csrf
                            <!-- Editor -->
                            <div class="mb-4" wire:ignore>
                                <div wire:ignore>
                                    <textarea class="form-control" id="description" wire:model.defer="announcement"></textarea>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <button type="submit" id="button" class="btn btn-primary">Publish</button>
                            <a href="#" class="btn btn-outline-secondary">Save to Draft</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script>
$(document).ready(function(){
        $('#description').summernote({
                height: 300, // Set your preferred height
                callbacks: {
                    // Update Livewire property when Summernote content changes
                    onChange: function (contents) {
                        @this.set('announcement', contents);
                    }
                }
        });
});
</script>
@endpush