<div wire:ignore.self class="modal fade" id="addforeignmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="newCatgoryLabel">
                    Add Foreign Matrix
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store">
                    @csrf

                    <div class="mb-3 mb-2">
                        <label class="form-label float-start" for="title">Course<span
                                class="text-danger">*</span></label>
                        <select class="form-control" wire:model="selectedCourse">
                            <option value="">Select</option>
                            @foreach ($courses_list as $course)
                            <option value="{{ $course->courseid }}">{{ $course->coursecode }} /
                                {{ $course->coursename }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Course Rate<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="courseRate" class="form-control" />
                        @error('courseRate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Arrival AM Check In<span class="text-danger">*</span>
                        </x-label>
                        <input id="" wire:model="departureAMCI" class="form-control" />
                        @error('departureAMCI') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Arrival PM Check In<span class="text-danger">*</span>
                        </x-label>
                        <input id="" wire:model="departurePMCI" class="form-control" />
                        @error('departurePMCI') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Departure AM Check Out<span class="text-danger">*</span>
                        </x-label>
                        <input id="" wire:model="departureAMCO" class="form-control" />
                        @error('departureAMCO') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Departure PM Check Out<span class="text-danger">*</span>
                        </x-label>
                        <input id="" wire:model="departurePMCO" class="form-control" />
                        @error('departurePMCO') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Breakfast Rate<span class="text-danger">*</span>
                        </x-label>
                        <input id="" wire:model="breakfastRate" class="form-control" />
                        @error('breakfastRate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Lunch Rate<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="lunchRate" class="form-control" />
                        @error('lunchRate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Dinner Date<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="dinnerRate" class="form-control" />
                        @error('dinnerRate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Dorm Rate<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="dorm_rate" label="" class="form-control" />
                        @error('dorm_rate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Meal Rate<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="meal_rate" class="form-control" />
                        @error('meal_rate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <x-label class="form-label float-start">Transpo<span class="text-danger">*</span></x-label>
                        <input id="" wire:model="transpoFee" class="form-control" />
                        @error('transpoFee') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3 mb-2">
                        <label class="form-label float-start" for="title">Format<span
                                class="text-danger">*</span></label>
                        <select class="form-select" name="" wire:model='billingFormat' id="">
                            <option value="">Select</option>
                            <option value="1">Single Billing Statement</option>
                            <option value="2">Individual Billing Statement</option>
                            <option value="3">Individual Billing By Vessel</option>
                        </select>
                        @error('billingFormat') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="mb-3 mb-2">
                        <label class="form-label float-start" for="title">Template<span
                                class="text-danger">*</span></label>
                        <select class="form-select" name="" wire:model='billingTemplate' id="">
                            <option value="">Select</option>
                            <option value="1">PESO</option>
                            <option value="2">USD</option>
                            <option value="3">USD w/ VAT</option>
                        </select>
                        @error('billingTemplate') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="mb-3 mb-2">
                        <label class="form-label float-start" for="title">Bank Charge<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="bankCharge">

                        @error('bankCharge') <small class="text-danger">{{$message}}</small> @enderror
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