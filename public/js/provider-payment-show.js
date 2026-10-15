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


    
if (
    modalElement &&
    window.bootstrap &&
    typeof window.bootstrap.Modal === 'function'
) {
    rejectModal = new window.bootstrap.Modal(modalElement);
} else if (modalElement) {
    console.error(
        'Payment Details reject modal could not initialize. Check Bootstrap JS.'
    );
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

        approveForm.addEventListener('submit', async function (event) {

            event.preventDefault();

            const confirmed = await window.showAppConfirm(
                'Are you sure you want to approve this payment?',
                'Confirm Payment Approval',
                'Confirm'
            );

            if (!confirmed) {
                return;
            }

            const button =
                approveForm.querySelector('button[type="submit"]') ||
                approveForm.querySelector('button');

            if (button) {
                button.disabled = true;

                button.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i> Approving...';
            }

            approveForm.submit();

        });

    }


});