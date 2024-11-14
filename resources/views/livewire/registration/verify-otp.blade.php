<main>
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>

    <!-- basic -->

    <section class="container d-flex flex-column">
        <div class="row mt-15 justify-content-center g-0 min-vh-100">
            <div class="col-lg-6 col-md-8 py-8 py-xl-0">
                <!-- Card -->
                <div class="card shadow">
                    <!-- Card body -->
                    <div class="card-body p-3">
                        <h1 class="mb-1 fw-bold"> (OTP) One Time Password</h1>
                        <hr>
                        <div class="mb-4">
                            <span>Please enter the 6-digit verification code emailed to you.
                        </div>
                        <!-- Form -->
                        <form wire:submit.prevent="VerifyOtp">
                            @csrf
                            <div class="row">
                                <!-- Email Address -->
                                <div class="mb-3 col-md-12">
                                    @if (session()->has('message'))
                                    <div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">
                                        <strong> {{ session('message') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    <label for="otp" class="form-label">Please enter OTP:</label>
                                    <input type="number" id="otp" wire:model.defer="otp" class="form-control" name="otp" onKeyPress="if(this.value.length==6) return false;" placeholder="xxxxxx" required>
                                    <span>
                                    If you do not receive an email, please click the resend button.
                                    <button class="btn btn-link btn-sm" wire:click="resend" @if ($cooldown) disabled @endif wire:loading.attr="disabled">Resend OTP</button>
                                    @if ($cooldown)
                                    <div><small>(<span id="cooldownTimer" class="text-danger"> <i>{{ $cooldownDuration }}</i></span> seconds) </small></div>
                                    @endif
                                    </span>
                                </div>
                                <div>
                                    <hr>
                                    <!-- Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            Verify
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            var livewireComponent = @this;

            Livewire.on('startCooldownTimer', function(duration) {
                var timer = setInterval(function() {
                    duration--;
                    if (duration <= 0) {
                        clearInterval(timer);
                        livewireComponent.set('cooldown', false); // Set the Livewire component's property
                    }
                    document.getElementById('cooldownTimer').innerText = duration;
                }, 1000);
            });
        });
    </script>
    @endpush
</main>