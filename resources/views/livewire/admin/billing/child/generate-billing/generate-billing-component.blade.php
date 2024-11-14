<li class="list-group-item" style="cursor: pointer;">
    <div class="row h-100">
        <div class="col-md-6">
            <a wire:click="passSessionData()"
                class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                <div class="text-truncate">
                    <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                            class="bi bi-receipt-cutoff"></i></span>
                    <span>Generate Billing Statement</span>
                </div>
            </a>
        </div>

        <x-billing-pdf-modal url="{{ $this->fileUrl }}"></x-billing-pdf-modal>

        @if ($this->billingstatusid == 2)
        <div class="col-md-4 offset-md-2">
            <select wire:model="default_recipient" {{-- wire:change="changeactive" --}} class="float-end form-select"
                style="width: 200px;">
                <option value="">Select</option>
                @foreach ($client_information as $item)
                @if ($item->label == 0)
                @php
                $clientinfo_plain = strip_tags($item->client_information);
                $charcount = strlen($clientinfo_plain);
                if ($charcount > 50) {
                $clientinfo = substr($clientinfo_plain, 0, 50) . "...";
                }else{
                $clientinfo = $clientinfo_plain;
                }
                @endphp
                <option value="{{ $item->id }}">{{$clientinfo}}
                </option>
                @else
                @php
                $clientname = json_decode($item->client_information);
                $txt = $clientname->recipient.', '.$clientname->companyname;

                $charcount = strlen($txt);
                if ($charcount > 50) {
                $clientinfo = substr($txt, 0, 50) . "...";
                }else{
                $clientinfo = $txt;
                }
                @endphp
                <option value="{{$item->id}}">{{ $clientinfo }}</option>
                @endif
                @endforeach
            </select>
        </div>
        @endif

    </div>
</li>