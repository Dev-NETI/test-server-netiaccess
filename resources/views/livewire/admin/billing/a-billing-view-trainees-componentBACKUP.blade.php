<ul class="list-group list-group-flush">
                                        <!-- List group -->
                                        <li class="list-group-item">
                                            <a wire:click="passSessionData()"
                                                class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                <div class="text-truncate">
                                                    <span
                                                        class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                            class="bi bi-receipt-cutoff"></i></span>
                                                    <span>Generate Billing Statement</span>
                                                </div>
                                            </a>
                                        </li>
                                        @if ($billingstatusid === 0)
                                            <li class="list-group-item">
                                                <a wire:click="attachSignature()"
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-pen-fill"></i></span>
                                                        <span>
                                                            @if ($is_SignatureAttached == 0)
                                                                Attach Signature
                                                            @else
                                                                Remove Signature
                                                            @endif
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @elseif($billingstatusid === 1)
                                            <li class="list-group-item">
                                                <a wire:click="attachGMSignature()"
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-pen-fill"></i></span>
                                                        <span>
                                                            @if ($is_GmSignatureAttached == 0)
                                                                Attach GM's Signature
                                                            @else
                                                                Remove GM's Signature
                                                            @endif
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="list-group-item">
                                            <a data-bs-target="#ViewAttachmentModal" data-bs-toggle='modal'
                                                class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                <div class="text-truncate">
                                                    <span
                                                        class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                            class="bi bi-binoculars"></i></span>
                                                    <span>View Attachment</span>
                                                </div>
                                            </a>
                                        </li>



                                        @if ($billingstatusid === 0)
                                            <li class="list-group-item">
                                                <a data-bs-target="#uploadAttachmentModal" data-bs-toggle='modal'
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-file-earmark-arrow-up"></i></span>
                                                        <span>Add Attachment</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a wire:click="generatePdf(1)"
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-send-check-fill"></i></span>
                                                        <span>Send Billing Statement to GM</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @elseif($billingstatusid === 1)
                                            <li class="list-group-item">
                                                <a wire:click="generatePdf(2)"
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-send-check-fill"></i></span>
                                                        <span>Send to client</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @elseif($billingstatusid === 4)
                                            <li class="list-group-item">
                                                <a data-bs-target="#uploadOfficialReceiptModal" data-bs-toggle='modal'
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span
                                                            class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-file-earmark-arrow-up"></i></span>
                                                        <span>Upload Official Receipt</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>