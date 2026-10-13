document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       REJECT MODAL
    ====================================================== */

    const modalElement =
        document.getElementById('detailRejectModal');

    const openButton =
        document.getElementById('openDetailReject');

    const rejectForm =
        document.getElementById('detailRejectForm');

    const reasonInput =
        document.getElementById('detailRejectionReason');

    const characterCount =
        document.getElementById('detailCharacterCount');

    const confirmButton =
        document.getElementById('detailConfirmReject');


    let rejectModal = null;


    if (modalElement) {

        rejectModal =
            new bootstrap.Modal(modalElement);

    }


    /* =====================================================
       OPEN MODAL
    ====================================================== */

    if (openButton) {

        openButton.addEventListener('click', function () {

            if (reasonInput) {
                reasonInput.value = '';
            }

            if (characterCount) {
                characterCount.textContent = '0';
            }

            if (rejectModal) {
                rejectModal.show();
            }

        });

    }


    /* =====================================================
       CHARACTER COUNT
    ====================================================== */

    if (reasonInput && characterCount) {

        reasonInput.addEventListener('input', function () {

            characterCount.textContent =
                reasonInput.value.length;

        });

    }


    /* =====================================================
       REJECT SUBMIT
    ====================================================== */

    if (rejectForm && confirmButton) {

        rejectForm.addEventListener('submit', function (event) {

            if (!reasonInput.value.trim()) {

                event.preventDefault();

                reasonInput.focus();

                return;

            }


            confirmButton.disabled = true;

            confirmButton.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Rejecting...';

        });

    }


    /* =====================================================
       APPROVE SUBMIT
    ====================================================== */

    const approveForm =
        document.querySelector('.payment-detail-approve-form');

    if (approveForm) {

        approveForm.addEventListener('submit', function (event) {

            const confirmed =
                window.confirm(
                    'Are you sure you want to approve this payment?'
                );

            if (!confirmed) {

                event.preventDefault();

                return;

            }


            const button =
                approveForm.querySelector('button');

            if (button) {

                button.disabled = true;

                button.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i> Approving...';

            }

        });

    }

});