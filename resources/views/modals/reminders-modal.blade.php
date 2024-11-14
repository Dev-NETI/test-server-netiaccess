<div wire:ignore.self class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">IMPORTANT REMINDER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>1. Our training hours are from 0800H to 1700H.</p>
                <p>2. Please observe the proper dress code (i.e., polo shirt, dark slacks, black leather shoes). Wearing maong pants and rubber shoes is strictly prohibited. </p>
                <p>3. Discipline, good manners, and right conduct should be observed at all times. Littering, loitering, sleeping during class, smoking outside of the designated area, and displaying a lack of self-control due to the influence of alcohol are grounds for termination of training.</p>
                <p>4. If you wish to use the shuttle service, please arrive on time at NYK-FIL Intramuros; the bus departs at exactly 0600H every Monday through Friday. </p>
                <p>5. We have a 'No admission slip - no boarding of the bus’ policy. For those who will go directly to NETI, you are also required to present a digital copy of your admission slip at the main gate.</p>
                <p>6. Upon arrival at the training center, please proceed to the FDC 1st Floor for the NETI Safety Briefing and Orientation.</p>
                <p>7. 'No photo, no issuance of training certificate' is strictly observed to avoid delay. For your convenience, you may upload your photos to your NETI Online Enrollment System (OES) account or have them taken on-site at our photo center.</p>
                <p>8. Enrollment cancellations must be made through the OES at least seven (7) working days before the start of your training. If you cannot cancel online due to unavoidable circumstances, please contact the Registrar at (02) 8-908-4900 or (02) 8-554-3888.</p>
                <p>9. For walk-in trainees, payment should be settled in full at least one day before the start of training. Please upload your proof of payment to your NETI OES account.</p>

                <h4 for="terms_conditions" class="form-check-label">
                    <input type="checkbox" id="terms_conditions" class="form-check-input" wire:model="acceptTerms" required>
                    If you click this button, you acknowledge that you have read and understand this important reminders.
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" wire:click="create" wire:loading.attr="disabled" class="btn btn-success" @if(!$acceptTerms) disabled @endif>
                    I understand and proceed
                </button>
            </div>
        </div>
    </div>
</div>