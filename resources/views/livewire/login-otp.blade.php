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
                            <span>Please enter the 6-digit verification code emailed to you.</span>
                        </div>
                        <!-- Form -->
                        <form wire:submit.prevent="VerifyOtp">
                            @csrf
                            <div class="row">
                                <!-- Email Address -->
                                <div class="mb-3 col-md-12">
                                    @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">
                                        <strong> {{ session('error') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    <label for="otp" class="form-label">Please enter OTP:</label>
                                    {{-- <input type="number" id="input_otp" wire:model.defer="input_otp" class="form-control" name="input_otp" onKeyPress="if(this.value.length==6) return false;" placeholder="xxxxxx" required> --}}

                                    <div class="row">
                                        <div class="col-md-6 offset-md-3">
                                            <input type="number" wire:model.defer="input_1" id="input_1" maxlength="1" class="text-center otp-field">
                                            <input type="number" wire:model.defer="input_2" id="input_2" maxlength="1" class="text-center otp-field">
                                            <input type="number" wire:model.defer="input_3" id="input_3" maxlength="1" class="text-center otp-field">
                                            <input type="number" wire:model.defer="input_4" id="input_4" maxlength="1" class="text-center otp-field">
                                            <input type="number" wire:model.defer="input_5" id="input_5" maxlength="1" class="text-center otp-field">
                                            <input type="number" wire:model.defer="input_6" id="input_6" maxlength="1" class="text-center otp-field">
                                        </div>
                                    </div>


                                    <span>
                                        If you do not receive an email, please refresh the tab.
                                        <!-- <button class="btn btn-link btn-sm" wire:click="resend" @if ($cooldown) disabled @endif  wire:loading.attr="disabled">Resend OTP</button>
                                        @if ($cooldown)
                                        <div><small>(<span id="cooldownTimer" class="text-danger">  <i>{{ $cooldownDuration }}</i></span> seconds) </small></div>
                                        @endif -->
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .otp-field {
            border: 2px solid #ccc;
            border-radius: 500px;
            width: 42px;
            height: 42px;
        }
    </style>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            //otp text field cursor
            $(document).ready(function() {
                function handleInput(input, nextInput) {
                    $(input).on('keyup', function() {
                        var inputValue = $(this).val();
                        if (inputValue.length) {
                            $(input).blur();
                            $(nextInput).focus();
                        }
                    });
                }

                function backSpace(input, nextInput) {
                    $(input).on('keydown', function(e) {
                        if (e.which === 8) {
                            $(input).blur();
                            $(input).val('');
                            $(nextInput).focus();
                        }
                    });
                }

                handleInput('#input_1', '#input_2');
                handleInput('#input_2', '#input_3');
                handleInput('#input_3', '#input_4');
                handleInput('#input_4', '#input_5');
                handleInput('#input_5', '#input_6');

                backSpace('#input_6', '#input_5');
                backSpace('#input_5', '#input_4');
                backSpace('#input_4', '#input_3');
                backSpace('#input_3', '#input_2');
                backSpace('#input_2', '#input_1');
            });


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