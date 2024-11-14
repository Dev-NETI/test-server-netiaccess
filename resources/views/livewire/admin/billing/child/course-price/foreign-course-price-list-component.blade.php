<tr>
    <td>{{ $course->course->coursecode }} / {{ $course->course->coursename }}</td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.courseRate" style="width: 80px;">
            @error('course.courseRate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->course_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.checkinAMFee" style="width: 80px;">
            @error('course.checkinAMFee')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dorm_am_checkin }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.checkinPMFee" style="width: 80px;">
            @error('course.checkinPMFee')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dorm_pm_checkin }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.checkoutAMFee" style="width: 80px;">
            @error('course.checkoutAMFee')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dorm_am_checkout }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.checkoutPMFee" style="width: 80px;">
            @error('course.meal_price_dollar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dorm_pm_checkout }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.breakfastRate" style="width: 80px;">
            @error('course.breakfastRate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->bf_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.lunchRate" style="width: 80px;">
            @error('course.lunchRate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->lh_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.dinnerRate" style="width: 80px;">
            @error('course.dinnerRate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dn_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.dorm_rate" style="width: 80px;">
            @error('course.dorm_rate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->dorm_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.meal_rate" style="width: 80px;">
            @error('course.meal_rate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->meal_rate }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.transpo" style="width: 80px;">
            @error('course.transpo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->transpo }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <select name="" wire:model='course.billingFormat' class="form-select" id="">
                <option value="">Select Format</option>
                <option value="1">Single Billing Statement</option>
                <option value="2">Individual Billing Statement</option>
                <option value="3">Individual Billing By Vessel</option>
            </select>
            @error('course.billing_statement_format')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            @if ($course->format == 1)
                Single Billing Statement
            @elseif ( $course->format == 2)
                Individual Billing Statement
            @else
                Individual Billing By Vessel
            @endif
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <select class="form-select" name="" wire:model='course.billingTemplate' id="">
                <option value="">Select Template</option>
                <option value="1">PESO</option>
                <option value="2">USD</option>
                <option value="3">USD w/ VAT</option>
            </select>
            @error('course.billing_statement_template')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            @if ($course->template == 1)
                PESO w/ VAT
            @elseif ($course->template == 2)
                USD
            @else
                USD w/ VAT
            @endif
        @endif
    </td>
    <td>
        @if ($isEdit == $course->id)
            <input type="text" wire:model="course.bankCharge" style="width: 80px;">
            @error('course.bankCharge')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        @else
            {{ $course->bank_charge }}
        @endif
    </td>
    <td>
        @if ($isEdit)
            <button class="btn btn-primary btn-sm" wire:click="update({{ $course->id }})" title="Save">
                <i class="bi bi-save dropdown-item-icon" style="color: white;"></i>
            </button>

            <button class="btn btn-danger btn-sm" wire:click="closeEdit()" title="Cancel">
                <i class="bi bi-x dropdown-item-icon" style="color: white;"></i>
            </button>
        @else
            <button class="btn btn-primary btn-sm" wire:click="edit({{ $course->id }})" title="Edit">
                <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i>
            </button>

            <button class="btn btn-danger btn-sm" wire:click="delete({{ $course->id }})" title="Delete">
                <i class="bi bi-trash dropdown-item-icon" style="color: white;"></i>
            </button>
        @endif
    </td>
</tr>