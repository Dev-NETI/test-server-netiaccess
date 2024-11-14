<x-card-layout cardTitle="Guest Instructor Biometrics" cardDescription="Instructor will time in and out here!">

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">CLOCK</span>
                        </div>
                        <div>
                            <span class=" fe fe-users fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-center" id="clock">
                    </h2>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateClock() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('clock').innerText = hours + ':' + minutes + ':' + seconds;
        }

        setInterval(updateClock, 1000);
    </script>
    @endpush

    <livewire:components.instructor-time-log.create-instructor-time-log-component />

    <div class="row">
        <livewire:components.instructor-time-log.time-log-list-component wire:key="{{ $listKey }}" />
    </div>

</x-card-layout>