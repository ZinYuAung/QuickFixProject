document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       CLOSE ALERTS
    ====================================================== */

    const alertCloseButtons =
        document.querySelectorAll('.payment-alert-close');

    alertCloseButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const alert =
                button.closest('.payment-alert');

            if (alert) {
                alert.remove();
            }

        });

    });


    /* =====================================================
       REJECT MODAL
    ====================================================== */

    const rejectModalElement =
        document.getElementById('rejectPaymentModal');

    const rejectForm =
        document.getElementById('rejectPaymentForm');

    const rejectPaymentName =
        document.getElementById('rejectPaymentName');

    const rejectionReason =
        document.getElementById('rejection_reason');

    const characterCount =
        document.getElementById('rejectionCharacterCount');

    const confirmRejectButton =
        document.getElementById('confirmRejectButton');


    let rejectModal = null;


   
if (
    rejectModalElement &&
    window.bootstrap &&
    typeof window.bootstrap.Modal === 'function'
) {
    rejectModal = new window.bootstrap.Modal(
        rejectModalElement
    );
} else if (rejectModalElement) {
    console.error(
        'Payments List reject modal could not initialize. Check Bootstrap JS.'
    );
}



    /* =====================================================
       OPEN REJECT MODAL
    ====================================================== */

    const rejectButtons =
        document.querySelectorAll('.payment-open-reject');

    rejectButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const paymentId =
                button.dataset.paymentId;

            const paymentName =
                button.dataset.paymentName || 'Payment';


            if (rejectForm) {

                rejectForm.action =
                    '/provider/payments/' +
                    paymentId +
                    '/reject';

            }


            if (rejectPaymentName) {

                rejectPaymentName.textContent =
                    'Payment ' + paymentName;

            }


            if (rejectionReason) {

                rejectionReason.value = '';

            }


            if (characterCount) {

                characterCount.textContent = '0';

            }


            if (confirmRejectButton) {

                confirmRejectButton.disabled = false;

                confirmRejectButton.innerHTML =
                    '<i class="fa-solid fa-xmark"></i> Reject Payment';

            }


            if (rejectModal) {

                rejectModal.show();

            }

        });

    });


    /* =====================================================
       CHARACTER COUNT
    ====================================================== */

    if (rejectionReason && characterCount) {

        rejectionReason.addEventListener(
            'input',
            function () {

                characterCount.textContent =
                    rejectionReason.value.length;

            }
        );

    }


    /* =====================================================
       REJECT FORM SUBMIT
    ====================================================== */

    if (rejectForm && confirmRejectButton) {

        rejectForm.addEventListener(
            'submit',
            function (event) {

                if (
                    !rejectionReason ||
                    !rejectionReason.value.trim()
                ) {

                    event.preventDefault();

                    if (rejectionReason) {
                        rejectionReason.focus();
                    }

                    return;

                }


                confirmRejectButton.disabled = true;

                confirmRejectButton.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i> Rejecting...';

            }
        );

    }


   

/* =====================================================
   APPROVE FORM SUBMIT
====================================================== */

const approveForms =
    document.querySelectorAll('.payment-approve-form');

approveForms.forEach(function (form) {

    let approvalInProgress = false;

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (approvalInProgress) {
            return;
        }

        if (typeof window.showAppConfirm !== 'function') {
            console.error(
                'Custom confirmation modal is not loaded.'
            );

            if (typeof window.showAppAlert === 'function') {
                window.showAppAlert(
                    'Confirmation dialog could not be opened. Please refresh the page and try again.',
                    'Error'
                );
            }

            return;
        }

        const confirmed = await window.showAppConfirm(
            'Are you sure you want to approve this payment?',
            'Confirm Payment Approval',
            'Confirm'
        );

        if (!confirmed) {
            return;
        }

        approvalInProgress = true;

        const button =
            form.querySelector('button[type="submit"]');

        if (button) {
            button.disabled = true;

            button.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Approving...';
        }

        form.submit();

    });

});




    /* =====================================================
       VIEW BUTTON LOADING
    ====================================================== */

    const viewButtons =
        document.querySelectorAll('.payment-view-button');

    viewButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            if (button.dataset.clicked === 'true') {
                return;
            }


            button.dataset.clicked = 'true';

            button.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

        });

    });


    /* =====================================================
       AUTOMATIC PAYMENT UPDATE POLLING
    ====================================================== */

    const paymentPage =
        document.querySelector(
            '.provider-payments-section'
        );


    if (paymentPage) {

        const paymentUpdateUrl =
            '/provider/payments/check-updates';


        let lastPendingCount = null;

        let lastPendingPaymentId = null;

        let paymentPolling = null;


        /* =================================================
           CHECK PAYMENT UPDATES
        ================================================= */

        function checkPaymentUpdates() {

            fetch(
                paymentUpdateUrl,
                {
                    method: 'GET',

                    headers: {
                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                    cache: 'no-store'
                }
            )
                .then(function (response) {

                    if (!response.ok) {

                        throw new Error(
                            'Unable to check payment updates.'
                        );

                    }


                    return response.json();

                })
                .then(function (data) {

                    if (!data.success) {
                        return;
                    }


                    const pendingCount =
                        Number(
                            data.pending_count || 0
                        );


                    const pendingPaymentId =
                        data.latest_pending_id ||
                        null;


                    /* =====================================
                       FIRST RESPONSE
                    ===================================== */

                    if (
                        lastPendingCount === null
                    ) {

                        lastPendingCount =
                            pendingCount;

                        lastPendingPaymentId =
                            pendingPaymentId;

                        return;

                    }


                    /* =====================================
                       DETECT NEW PAYMENT
                    ===================================== */

                  const newPaymentDetected =
    pendingCount >
    lastPendingCount;


                    if (newPaymentDetected) {

                        /*
                         * Stop polling before reload.
                         */

                        clearInterval(
                            paymentPolling
                        );

                        paymentPolling = null;


                        /*
                         * Reload the existing page.
                         *
                         * This preserves:
                         * - Blade UI
                         * - filters
                         * - pagination
                         * - Approve button
                         * - Reject button
                         * - existing styling
                         */

                        window.location.reload();

                        return;

                    }


                    /* =====================================
                       UPDATE STORED STATE
                    ===================================== */

                    lastPendingCount =
                        pendingCount;

                    lastPendingPaymentId =
                        pendingPaymentId;

                })
                .catch(function (error) {

                    console.error(
                        'QuickFix payment update check failed:',
                        error
                    );

                });

        }


        /* =================================================
           INITIAL CHECK
        ================================================= */

        checkPaymentUpdates();


        /* =================================================
           POLL EVERY 2 SECONDS
        ================================================= */

        paymentPolling =
            setInterval(
                checkPaymentUpdates,
                2000
            );

    }

});
