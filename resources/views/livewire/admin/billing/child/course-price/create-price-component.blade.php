<div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="newCatgoryLabel">
                    Add Matrix
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Course<span
                                    class="text-danger">*</span></label>
                            <select class="form-control" wire:model="selectedcourse">
                                <option value="">Select</option>
                                @foreach ($courses_list as $course)
                                <option value="{{ $course->courseid }}">{{ $course->coursecode }} /
                                    {{ $course->coursename }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Rate in Peso<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="ratepeso">
                            @error('ratepeso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Rate in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="rateusd">
                            @error('rateusd') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Meal in Peso<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="meal_price_peso">
                            @error('meal_price_peso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Meal in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="meal_price_dollar">
                            @error('meal_price_dollar') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm in Peso<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_price_peso">
                            @error('dorm_price_peso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_price_dollar">
                            @error('dorm_price_dollar') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm 2-sharing in Peso<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_2s_price_peso">
                            @error('dorm_price_peso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm 2-sharing in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_2s_price_dollar">
                            @error('dorm_price_dollar') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm 4-sharing in Peso<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_4s_price_peso">
                            @error('dorm_price_peso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Dorm 4-sharing in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="dorm_4s_price_dollar">
                            @error('dorm_price_dollar') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Transpo in PHP<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="transpo_fee_peso">
                            @error('transpo_fee_peso') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Transpo in USD<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="transpo_fee_dollar">
                            @error('transpo_fee_dollar') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Format<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="" wire:model='billing_statement_format' id="">
                                <option value="">Select</option>
                                <option value="1">Single Billing Statement</option>
                                <option value="2">Individual Billing Statement</option>
                                <option value="3">Individual Billing By Vessel</option>
                            </select>
                            @error('billing_statement_format') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Template<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="" wire:model='billing_statement_template' id="">
                                <option value="">Select</option>
                                <option value="1">PESO</option>
                                <option value="2">USD</option>
                                <option value="3">USD w/ VAT</option>
                            </select>
                            @error('billing_statement_template') <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Bank Charge<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="bank_charge">

                            @error('bank_charge') <small class="text-danger">{{$message}}</small> @enderror
                        </div>

                        <div class="col-lg-6 mb-3 mb-2">
                            <label class="form-label float-start" for="title">Add DMT<span
                                    class="text-danger">*</span></label>
                            <input type="checkbox" class="form-check-input" wire:model="wDMT">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>