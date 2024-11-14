<section class="container-fluid mt-3">

    <style>
        .toast-overlay {
            position: fixed;
            top: 3em;
            right: 1em;
            z-index: 9999;
            width: auto;
            max-width: 80%;
            opacity: 1;
            transition: opacity 0.5s ease-in-out, transform 3s ease-in-out;
        }

        .fade-out-upward {
            opacity: 0;
            transform: translateY(-50px);
        }

        @keyframes fadeOutUpward {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-50px);
            }
        }
    </style>

    <div aria-live="polite" aria-atomic="true" style="position: relative;">
        <div id="toast" class="toast toast-overlay {{$showToast ? 'show' : ''}} bg-success">
            <div class="toast-header bg-dark-success text-white">
                <strong class="me-auto">{{$title}}</strong>
                <button type="button" class="ms-2 mb-1 btn-close" data-bs-dismiss="toast" aria-label="Close">
                </button>
            </div>
            <div class="toast-body text-white">
                Updated Successfully!
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                const toast = document.getElementById('toast');
                if (toast.classList.contains('show')) {
                    setTimeout(() => {
                        toast.classList.add('fade-out-upward');
                    }, 1500);
                }
            });
        });
    </script>


    <h2>
        JISS Template Editor
    </h2>
    <hr>
    <!-- placement -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Course Attributes</h4>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="row text-center">
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Serial Number" model="xySN"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Recipient" model="xyRecipient"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Recipient Company" model="xyRecipientCompany"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Recipient Position" model="xyRecipientPosition"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Recipient Add. Line 1"
                                    model="xyRecipientAddressline1" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Recipient Add. Line 2"
                                    model="xyRecipientAddressline2" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Company" model="xyCompany" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3">
                                <x-input class="text-center" label="Amount (vat)" model="xyAmountwVat"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Month/Year" model="xyMonthYear"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Date Billed" model="xyDateBilled"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Training Title" model="xyTrainingTitle"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Course" model="xyCourse" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Trainee Names" model="xyTrainee"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Nationality" model="xyNationality"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Amount" model="xyAmount" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Total" model="xyTotal" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Meal" model="xyMeal" placeholder="e.g. 0,1" />
                            </div>

                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Transpo" model="xyTranspo" placeholder="e.g. 0,1" />
                            </div>

                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Dormitory" model="xyDorm" placeholder="e.g. 0,1" />
                            </div>

                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="DMT Total" model="xyDMT" placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Service Charge" model="xyServiceCharge"
                                    placeholder="e.g. 0,1" />
                            </div>

                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Service Charge Text" model="xyServiceChargetxt"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Overall Total" model="xyOverAllTotal"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Signature 1" model="xySig1"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Signature 2" model="xySig2"
                                    placeholder="e.g. 0,1" />
                            </div>
                            <div class="col-lg-3 mt-2">
                                <x-input class="text-center" label="Signature 3" model="xySig3"
                                    placeholder="e.g. 0,1" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            div.stickyfx {
                /* position: -webkit-sticky; */
                position: sticky;
                top: 0;
            }
        </style>

        <div class="col-lg-6">
            <div class="card stickyfx">
                <div class="card-header">
                    <h4>PDF Preview</h4>
                </div>
                <div class="card-body">

                    <livewire:admin.billing.j-i-s-s-template-settings-component wire:key='{{rand()}}' courseid='
                        {{$courseID}}' />
                    <livewire:admin.billing.child.jiss.course-template-setting-component wire:key='{{rand()}}'
                        fileURL='{{$fileURL}}' />
                </div>
            </div>
        </div>

    </div>
</section>