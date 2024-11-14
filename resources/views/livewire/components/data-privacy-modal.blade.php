{{-- <style>
    /* Custom CSS */
    .modal {
        padding: 0;
    }

    .modal-content {
        border-radius: 0;
        border: none;
        max-height: 90vh !important;
    }

    .modal-dialog {
        display: flex;
        align-items: center;
    }

    .modal-body {
        overflow-y: auto;
    }
</style>
<script>
    function setCookie(cookieName, cookieValue, expirationDays) {
        const d = new Date();
        d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
    }

    function onAcceptDataPrivacy() {
        setCookie("data_privacy_accepted", "true", 30); // Expires in 30 days
        $('#dataprivacymodal').modal('hide');
    }

    function isDataPrivacyAccepted() {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.startsWith("data_privacy_accepted=")) {
                return cookie.substring("data_privacy_accepted=".length, cookie.length) === "true";
            }
        }
        return false;
    }
    $(document).ready(function() {
        if (!isDataPrivacyAccepted()) {
            $('#dataprivacymodal').modal('show');
        }

    });
</script>

<div class="modal fade gd-example-modal-lg" tabindex="-1" id="dataprivacymodal"role="dialog"
    aria-labelledby="dataprivacymodal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content h-80">
            <div class="modal-body">
                @include('livewire.components.data-privacy-text')
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="onAcceptDataPrivacy()">Accept</button>
            </div>
        </div>
    </div>
</div> --}}
