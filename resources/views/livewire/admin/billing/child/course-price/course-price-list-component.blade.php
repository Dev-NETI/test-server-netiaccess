<tr>
    <td>{{ $course->course->coursecode }} / {{ $course->course->coursename }}</td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.ratepeso" style="width: 80px;">
        @error('course.ratepeso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->ratepeso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.rateusd" style="width: 80px;">
        @error('course.rateusd')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->rateusd }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.meal_price_peso" style="width: 80px;">
        @error('course.meal_price_peso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->meal_price_peso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.meal_price_dollar" style="width: 80px;">
        @error('course.meal_price_dollar')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->meal_price_dollar }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_price_peso" style="width: 80px;">
        @error('course.dorm_price_peso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_price_peso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_price_dollar" style="width: 80px;">
        @error('course.dorm_price_dollar')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_price_dollar }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_2s_price_peso" style="width: 80px;">
        @error('course.dorm_2s_price_peso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_2s_price_peso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_2s_price_dollar" style="width: 80px;">
        @error('course.dorm_2s_price_dollar')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_2s_price_dollar }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_4s_price_peso" style="width: 80px;">
        @error('course.dorm_4s_price_peso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_4s_price_peso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.dorm_4s_price_dollar" style="width: 80px;">
        @error('course.dorm_4s_price_dollar')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->dorm_4s_price_dollar }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.transpo_fee_peso" style="width: 80px;">
        @error('course.transpo_fee_peso')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->transpo_fee_peso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input type="text" wire:model="course.transpo_fee_dollar" style="width: 80px;">
        @error('course.transpo_fee_dollar')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->transpo_fee_dollar }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <select name="" wire:model='course.billing_statement_format' class="form-select" id="">
            <option value="">Select Format</option>
            <option value="1">Single Billing Statement</option>
            <option value="2">Individual Billing Statement</option>
            <option value="3">Individual Billing By Vessel</option>
        </select>
        @error('course.billing_statement_format')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        @if ($course->billing_statement_format == 1)
        Single Billing Statement
        @elseif ( $course->billing_statement_format == 2)
        Individual Billing Statement
        @else
        Individual Billing By Vessel
        @endif
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <select class="form-select" name="" wire:model='course.billing_statement_template' id="">
            <option value="">Select Template</option>
            <option value="1">PESO</option>
            <option value="2">USD</option>
            <option value="3">USD w/ VAT</option>
        </select>
        @error('course.billing_statement_template')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        @if ($course->billing_statement_template == 1)
        PESO w/ VAT
        @elseif ($course->billing_statement_template == 2)
        USD
        @else
        USD w/ VAT
        @endif
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
        <input class="form-control" name="" wire:model='course.bank_charge' id="" />
        @error('course.bank_charge')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->bank_charge }}
        @endif
    </td>
    <td style="min-width: 12em;">
        @if ($isEdit == $course->companycourseid)
        <select class="form-control" name="" id="" wire:model="course.wDMT">
            <option value="0">No DMT</option>
            <option value="1">With DMT</option>
        </select>
        @error('course.wDMT')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @else
        {{ $course->wDMT ? 'With DMT' : 'No DMT' }}
        @endif
    </td>
    <td>
        @if ($isEdit)
        <button class="btn btn-primary btn-sm" wire:click="update({{ $course->companycourseid }})" title="Save">
            <i class="bi bi-save dropdown-item-icon" style="color: white;"></i>
        </button>

        <button class="btn btn-danger btn-sm" wire:click="closeEdit()" title="Cancel">
            <i class="bi bi-x dropdown-item-icon" style="color: white;"></i>
        </button>
        @else
        <button class="btn btn-primary btn-sm" wire:click="edit({{ $course->companycourseid }})" title="Edit">
            <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i>
        </button>

        <button class="btn btn-danger btn-sm" wire:click="delete({{ $course->companycourseid }})" title="Delete">
            <i class="bi bi-trash dropdown-item-icon" style="color: white;"></i>
        </button>
        @endif
    </td>
</tr>