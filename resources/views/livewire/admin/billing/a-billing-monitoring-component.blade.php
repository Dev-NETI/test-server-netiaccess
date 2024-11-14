<x-card-layout cardTitle="Billing and Collection Monitoring"
    cardDescription="Showing data for current week: {{ $currentWeek }}">
    <div wire:loading>
        
    </div>
    <script>
        window.addEventListener('openNewTab', event => {
                        window.open(event.detail.url, '_blank');
                    });
    </script>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('a.billing-summary') }}" class="btn btn-primary">View Summary</a>
            <button data-bs-toggle="modal" data-bs-target=".gd-example-modal-xl" class=" btn btn-primary"><i
                    class="bi bi-stopwatch-fill"></i>
                Board History</button>

            <div wire:ignore.self class="modal fade gd-example-modal-xl" tabindex="-1" role="dialog"
                aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="text-white">Board History</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-header">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input wire:model="searchSerial" type="text" class="form-control"
                                    placeholder="Search serial number" aria-label="Search serial number"
                                    aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="modal-body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serial Number</th>
                                        <th>From Board</th>
                                        <th>To Board</th>
                                        <th>Modified By</th>
                                        <th>Transaction Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($boardhistory->count() > 0)
                                    @foreach ($boardhistory as $history)
                                    <tr>
                                        <td>{{ $history->serialnumber }}</td>
                                        <td>{{ $history->fromboard }}</td>
                                        <td>{{ $history->toboard }}</td>
                                        <td>{{ $history->modified_by }}</td>
                                        <td>{{ date('l, F d, Y h:i:s a', strtotime($history->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ $boardhistory->links('livewire.components.customized-pagination-link') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offset-md-4 col-md-4">
            <x-select-input label="Select Week #" defaultOption="select" :data="$scheduleData"
                wire:model="currentWeek" />
        </div>

        {{-- Pending Statements Board --}}
        <div wire:ignore class="row">

            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="1" icon="bi bi-clock" step="Step 1"
                process="On-process" role="62" />

            {{-- Billing Statement Review Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="2" icon="bi bi-file-earmark-text" step="Step 2"
                process="Billing Statement Review Board" role="63" />

            {{-- BOD Manager Review Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="3" icon="bi bi-people" step="Step 3"
                process="BOD Manager Review Board" role="65" />

            {{-- GM Review Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="4" icon="bi bi-person" step="Step 4"
                process="GM Review Board" role="66" />

            {{-- BOD Manager Dispatch Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="5" icon="bi bi-arrow-up-circle" step="Step 5"
                process="BOD Manager Dispatch Board" role="67" />

            {{-- Client Confirmation Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="6" icon="bi bi-check-circle" step="Step 6"
                process="Client Confirmation Board" role="68" />

            {{-- Proof of Payment Upload Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="7" icon="bi bi-cloud-upload" step="Step 7"
                process="Proof of Payment Upload Board" role="69" />

            {{-- Official Receipt Issuance Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="8" icon="bi bi-receipt" step="Step 8"
                process="Official Receipt Issuance Board" role="70" />

            {{-- Official Receipt Confirmation Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="9" icon="bi bi-receipt-cutoff" step="Step 9"
                process="Official Receipt Confirmation Board" role="71" />

            {{-- Transaction Close Board --}}
            <livewire:admin.billing.child.monitoring.board-card-component :key="$currentWeek"
                currentWeek="{{ $currentWeek }}" :billingstatusid="10" icon="bi bi-file-check-fill" step="Step 10"
                process="Transaction Close Board" role="72" />
        </div>
    </div>


</x-card-layout>