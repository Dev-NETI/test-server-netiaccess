<section>

    <div class="py-lg-14 bg-light pt-8 pb-10">
        <!-- container -->

        <div class="container">

            <div class="card">
                <div class="card-header text-center">
                    <h1 class="card-title">Bank</h1>
                    <p class="card-text">Here you can edit the bank information that appears in the billing statement.
                    </p>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-request-message />
                            <form wire:submit.prevent="update">
                                @csrf
                                <div class="form-group form-row">
                                    <div class="col-md-12">
                                        <label>Select Bank</label><br>
                                        @foreach ($bank_data as $index => $data)
                                            <label>
                                                <input type="radio" wire:model="bank_id"
                                                    value="{{ $data->billingaccountid }}" >
                                                {{ $data->billingaccount }}
                                            </label><br>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary float-end">Save</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-body-secondary">
                </div>
            </div>

        </div>
    </div>
</section>
