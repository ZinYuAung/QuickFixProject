/* =========================================================
   QUICKFIX CUSTOMER PAYMENT FORM
   PREPAYMENT + FINAL PAYMENT
   AJAX PAYMENT STATUS HANDLER
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       PAYMENT ELEMENTS
    ====================================================== */

    const paymentMethods =
        document.querySelectorAll(
            'input[name="payment_method"]'
        );

    const kbzpaySection =
        document.getElementById(
            "kbzpaySection"
        );

    const slipInput =
        document.getElementById(
            "slip_image"
        );

    const uploadFileName =
        document.getElementById(
            "uploadFileName"
        );

    const payableAmountValue =
        document.getElementById(
            "payableAmountValue"
        );

    const summaryPayableAmount =
        document.getElementById(
            "summaryPayableAmount"
        );

    const paymentForm =
        document.getElementById(
            "paymentForm"
        );

    const paymentFormContainer =
        document.getElementById(
            "paymentFormContainer"
        );

    const codPaymentOption =
        document.getElementById(
            "codPaymentOption"
        );


    /* =====================================================
       PAYMENT FORM TEXT ELEMENTS
    ====================================================== */

    const paymentFormTitle =
        document.getElementById(
            "paymentFormTitle"
        );

    const paymentFormDescription =
        document.getElementById(
            "paymentFormDescription"
        );

    const kbzpayMethodDescription =
        document.getElementById(
            "kbzpayMethodDescription"
        );

    const kbzpayTitle =
        document.getElementById(
            "kbzpayTitle"
        );

    const kbzpayDescription =
        document.getElementById(
            "kbzpayDescription"
        );

    const payableAmountLabel =
        document.getElementById(
            "payableAmountLabel"
        );

    const slipUploadLabel =
        document.getElementById(
            "slipUploadLabel"
        );

    const paymentSubmitText =
        document.getElementById(
            "paymentSubmitText"
        );


    /* =====================================================
       STATUS MESSAGE
    ====================================================== */

    const paymentStatusMessage =
        document.getElementById(
            "paymentStatusMessage"
        );

    const paymentStatusMessageIcon =
        document.getElementById(
            "paymentStatusMessageIcon"
        );

    const paymentStatusMessageTitle =
        document.getElementById(
            "paymentStatusMessageTitle"
        );

    const paymentStatusMessageText =
        document.getElementById(
            "paymentStatusMessageText"
        );


    /* =====================================================
       REJECTION MESSAGE
    ====================================================== */

    const paymentRejectedMessage =
        document.getElementById(
            "paymentRejectedMessage"
        );

    const paymentRejectedTitle =
        document.getElementById(
            "paymentRejectedTitle"
        );

    const paymentRejectionReason =
        document.getElementById(
            "paymentRejectionReason"
        );


    /* =====================================================
       CURRENT STATUS CARD
    ====================================================== */

    const currentStatusCard =
        document.querySelector(
            ".payment-current-status-card"
        );

    const currentStatusIcon =
        currentStatusCard
            ? currentStatusCard.querySelector(
                ".payment-current-status-icon i"
            )
            : null;

    const currentStatusText =
        currentStatusCard
            ? currentStatusCard.querySelector(
                "strong"
            )
            : null;


    /* =====================================================
       BOOKING DATA
    ====================================================== */

    const bookingId =
        document.body.dataset.bookingId;

    const prepaidAmount =
        Number(
            document.body.dataset.prepaidAmount || 0
        );

    const serviceAmount =
        Number(
            document.body.dataset.serviceAmount || 0
        );

    let remainingAmount =
        Number(
            document.body.dataset.remainingAmount || 0
        );


    /* =====================================================
       CURRENT PAYMENT STAGE
    ====================================================== */

    let currentStage = "none";

    /*
        none
        prepayment
        final
    */


    /* =====================================================
       FORMAT AMOUNT
    ====================================================== */

    function formatAmount(amount) {

        return Number(amount).toLocaleString(
            "en-US",
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    /* =====================================================
       UPDATE REQUIRED PAYMENT AMOUNT
    ====================================================== */

    function updateRequiredAmount(amount) {

        const formattedAmount =
            formatAmount(amount);


        if (payableAmountValue) {

            payableAmountValue.textContent =
                formattedAmount;

        }


        if (summaryPayableAmount) {

            summaryPayableAmount.textContent =
                formattedAmount;

        }

    }


    /* =====================================================
       SET PAYMENT FORM STAGE
    ====================================================== */

    function setPaymentStage(
        stage,
        amount = 0
    ) {

        currentStage = stage;


        /* =================================================
           PREPAYMENT
        ================================================== */

        if (stage === "prepayment") {

            if (paymentFormContainer) {

                paymentFormContainer.style.display =
                    "block";

            }


            if (codPaymentOption) {

                codPaymentOption.style.display =
                    "none";

            }


            const kbzpayInput =
                document.getElementById(
                    "paymentMethodKbzpay"
                );

            if (kbzpayInput) {

                kbzpayInput.checked =
                    true;

            }


            if (paymentFormTitle) {

                paymentFormTitle.textContent =
                    "Prepayment Method";

            }


            if (paymentFormDescription) {

                paymentFormDescription.textContent =
                    "Pay the required 10% prepayment.";

            }


            if (kbzpayMethodDescription) {

                kbzpayMethodDescription.textContent =
                    "Pay the required prepayment using KBZPay.";

            }


            if (kbzpayTitle) {

                kbzpayTitle.textContent =
                    "KBZPay Prepayment";

            }


            if (kbzpayDescription) {

                kbzpayDescription.textContent =
                    "Please transfer the required prepayment amount and upload your payment slip.";

            }


            if (payableAmountLabel) {

                payableAmountLabel.textContent =
                    "Required Prepayment";

            }


            if (slipUploadLabel) {

                slipUploadLabel.textContent =
                    "KBZPay Prepayment Slip";

            }


            if (paymentSubmitText) {

                paymentSubmitText.textContent =
                    "Submit Prepayment";

            }


            updateRequiredAmount(
                prepaidAmount
            );

            return;

        }


        /* =================================================
           FINAL PAYMENT
        ================================================== */

        if (stage === "final") {

            if (paymentFormContainer) {

                paymentFormContainer.style.display =
                    "block";

            }


            if (codPaymentOption) {

                codPaymentOption.style.display =
                    "flex";

            }


            if (paymentFormTitle) {

                paymentFormTitle.textContent =
                    "Final Payment Method";

            }


            if (paymentFormDescription) {

                paymentFormDescription.textContent =
                    "Pay the remaining amount for your completed service.";

            }


            if (kbzpayMethodDescription) {

                kbzpayMethodDescription.textContent =
                    "Pay the remaining amount using KBZPay.";

            }


            if (kbzpayTitle) {

                kbzpayTitle.textContent =
                    "KBZPay Final Payment";

            }


            if (kbzpayDescription) {

                kbzpayDescription.textContent =
                    "Please transfer the remaining amount and upload your payment slip.";

            }


            if (payableAmountLabel) {

                payableAmountLabel.textContent =
                    "Remaining Payment";

            }


            if (slipUploadLabel) {

                slipUploadLabel.textContent =
                    "KBZPay Final Payment Slip";

            }


            if (paymentSubmitText) {

                paymentSubmitText.textContent =
                    "Submit Final Payment";

            }


            updateRequiredAmount(
                amount
            );


            updatePaymentMethod();

            return;

        }


        /* =================================================
           NO PAYMENT FORM
        ================================================== */

        if (paymentFormContainer) {

            paymentFormContainer.style.display =
                "none";

        }

    }


    /* =====================================================
       GET SELECTED PAYMENT METHOD
    ====================================================== */

    function getSelectedPaymentMethod() {

        const selected =
            document.querySelector(
                'input[name="payment_method"]:checked'
            );

        return selected
            ? selected.value
            : null;

    }


    /* =====================================================
       UPDATE PAYMENT METHOD
    ====================================================== */

    function updatePaymentMethod() {

        const selected =
            getSelectedPaymentMethod();


        if (!selected) {

            if (kbzpaySection) {

                kbzpaySection.classList.remove(
                    "show"
                );

            }

            return;

        }


        /* =================================================
           COD
        ================================================== */

        if (selected === "cod") {

            if (kbzpaySection) {

                kbzpaySection.classList.remove(
                    "show"
                );

            }


            /*
             * COD is only available for final payment.
             */

            if (currentStage === "final") {

                updateRequiredAmount(
                    remainingAmount
                );

            }

            return;

        }


        /* =================================================
           KBZPAY
        ================================================== */

        if (selected === "kbzpay") {

            if (kbzpaySection) {

                kbzpaySection.classList.add(
                    "show"
                );

            }


            if (currentStage === "prepayment") {

                updateRequiredAmount(
                    prepaidAmount
                );

            }


            if (currentStage === "final") {

                updateRequiredAmount(
                    remainingAmount
                );

            }

        }

    }


    /* =====================================================
       PAYMENT METHOD EVENTS
    ====================================================== */

    paymentMethods.forEach(
        function (method) {

            method.addEventListener(
                "change",
                updatePaymentMethod
            );

        }
    );


    /* =====================================================
       FILE SELECTION
    ====================================================== */

    if (slipInput) {

        slipInput.addEventListener(
            "change",
            function () {

                if (
                    this.files &&
                    this.files.length > 0
                ) {

                    if (uploadFileName) {

                        uploadFileName.textContent =
                            this.files[0].name;

                    }

                }
                else {

                    if (uploadFileName) {

                        uploadFileName.textContent =
                            "No file selected";

                    }

                }

            }
        );

    }


    /* =====================================================
       UPDATE CURRENT STATUS CARD
    ====================================================== */

    function updateCurrentStatus(
        bookingStatus,
        paymentStatus,
        finalPaymentStatus,
        slipUploaded,
        finalSlipUploaded
    ) {

        if (
            !currentStatusText ||
            !currentStatusIcon
        ) {

            return;

        }


        /* =================================================
           COMPLETED + FINAL VERIFIED
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "verified"
        ) {

            currentStatusText.textContent =
                "Fully Paid";

            currentStatusIcon.className =
                "fa-solid fa-circle-check";

            return;

        }


        /* =================================================
           COMPLETED + FINAL REJECTED
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "rejected"
        ) {

            currentStatusText.textContent =
                "Final Payment Rejected";

            currentStatusIcon.className =
                "fa-solid fa-circle-xmark";

            return;

        }


        /* =================================================
           COMPLETED + FINAL PENDING + SLIP
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "pending" &&
            finalSlipUploaded
        ) {

            currentStatusText.textContent =
                "Final Payment Pending";

            currentStatusIcon.className =
                "fa-solid fa-clock";

            return;

        }


        /* =================================================
           COMPLETED + FINAL PENDING + NO SLIP
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "pending" &&
            !finalSlipUploaded
        ) {

            currentStatusText.textContent =
                "Final Payment Required";

            currentStatusIcon.className =
                "fa-solid fa-wallet";

            return;

        }


        /* =================================================
           PREPAYMENT VERIFIED
        ================================================== */

        if (paymentStatus === "verified") {

            currentStatusText.textContent =
                "Prepayment Verified";

            currentStatusIcon.className =
                "fa-solid fa-circle-check";

            return;

        }


        /* =================================================
           PREPAYMENT REJECTED
        ================================================== */

        if (paymentStatus === "rejected") {

            currentStatusText.textContent =
                "Prepayment Rejected";

            currentStatusIcon.className =
                "fa-solid fa-circle-xmark";

            return;

        }


        /* =================================================
           PREPAYMENT PENDING + SLIP
        ================================================== */

        if (
            paymentStatus === "pending" &&
            slipUploaded
        ) {

            currentStatusText.textContent =
                "Prepayment Pending";

            currentStatusIcon.className =
                "fa-solid fa-clock";

            return;

        }


        /* =================================================
           PREPAYMENT PENDING + NO SLIP
        ================================================== */

        if (
            paymentStatus === "pending" &&
            !slipUploaded
        ) {

            currentStatusText.textContent =
                "Prepayment Required";

            currentStatusIcon.className =
                "fa-solid fa-wallet";

            return;

        }


        /* =================================================
           ACCEPTED - NO PREPAYMENT
        ================================================== */

        if (
            bookingStatus === "accepted" &&
            prepaidAmount <= 0
        ) {

            currentStatusText.textContent =
                "No Prepayment Required";

            currentStatusIcon.className =
                "fa-solid fa-circle-check";

            return;

        }


        /* =================================================
           DEFAULT
        ================================================== */

        currentStatusText.textContent =
            "Waiting for Acceptance";

        currentStatusIcon.className =
            "fa-solid fa-wallet";

    }


    /* =====================================================
       UPDATE STATUS MESSAGE
    ====================================================== */

    function updateStatusMessage(
        bookingStatus,
        paymentStatus,
        finalPaymentStatus,
        slipUploaded,
        finalSlipUploaded,
        finalRejectionReason,
        prepaymentRejectionReason
    ) {

        if (
            !paymentStatusMessage ||
            !paymentStatusMessageTitle ||
            !paymentStatusMessageText ||
            !paymentStatusMessageIcon
        ) {

            return;

        }


        /* =================================================
           COMPLETED + FINAL VERIFIED
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "verified"
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Payment Verified";

            paymentStatusMessageText.textContent =
                "Your final payment has been verified by the provider. This booking is fully paid.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-circle-check";

            return;

        }


        /* =================================================
           COMPLETED + FINAL REJECTED
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "rejected"
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Final Payment Rejected";

            paymentStatusMessageText.textContent =
                finalRejectionReason ||
                "Your final payment was rejected. Please submit the final payment again.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-circle-xmark";

            return;

        }


        /* =================================================
           COMPLETED + FINAL PENDING + NO SLIP
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "pending" &&
            !finalSlipUploaded
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Final Payment Required";

            paymentStatusMessageText.textContent =
                "Your service has been completed. Please pay the remaining amount.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-wallet";

            return;

        }


        /* =================================================
           COMPLETED + FINAL PENDING + SLIP
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "pending" &&
            finalSlipUploaded
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Final Payment Pending Verification";

            paymentStatusMessageText.textContent =
                "Your final payment has been submitted. Please wait for the provider to verify it.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-clock";

            return;

        }


        /* =================================================
           PREPAYMENT PENDING + SLIP
        ================================================== */

        if (
            paymentStatus === "pending" &&
            slipUploaded
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Prepayment Pending Verification";

            paymentStatusMessageText.textContent =
                "Your prepayment has been submitted. Please wait for the provider to verify it.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-clock";

            return;

        }


        /* =================================================
           PREPAYMENT REJECTED
        ================================================== */

        if (
            paymentStatus === "rejected"
        ) {

            paymentStatusMessage.style.display =
                "none";

            return;

        }


        /* =================================================
           PREPAYMENT VERIFIED
        ================================================== */

        if (
            paymentStatus === "verified"
        ) {

            paymentStatusMessage.style.display =
                "flex";

            paymentStatusMessageTitle.textContent =
                "Prepayment Verified";

            paymentStatusMessageText.textContent =
                "Your prepayment has been verified by the provider.";

            paymentStatusMessageIcon.className =
                "fa-solid fa-circle-check";

            return;

        }


        /* =================================================
           DEFAULT
        ================================================== */

        paymentStatusMessage.style.display =
            "none";

    }


    /* =====================================================
       UPDATE REJECTION MESSAGE
    ====================================================== */

    function updateRejectionMessage(
        bookingStatus,
        paymentStatus,
        finalPaymentStatus,
        rejectionReason,
        finalRejectionReason
    ) {

        if (!paymentRejectedMessage) {

            return;

        }


        /* =================================================
           FINAL PAYMENT REJECTED
        ================================================== */

        if (
            bookingStatus === "completed" &&
            finalPaymentStatus === "rejected"
        ) {

            paymentRejectedMessage.style.display =
                "flex";


            if (paymentRejectedTitle) {

                paymentRejectedTitle.textContent =
                    "Final Payment Rejected";

            }


            if (paymentRejectionReason) {

                paymentRejectionReason.textContent =
                    finalRejectionReason ||
                    "No rejection reason was provided.";

            }

            return;

        }


        /* =================================================
           PREPAYMENT REJECTED
        ================================================== */

        if (
            paymentStatus === "rejected"
        ) {

            paymentRejectedMessage.style.display =
                "flex";


            if (paymentRejectedTitle) {

                paymentRejectedTitle.textContent =
                    "Prepayment Rejected";

            }


            if (paymentRejectionReason) {

                paymentRejectionReason.textContent =
                    rejectionReason ||
                    "No rejection reason was provided.";

            }

            return;

        }


        paymentRejectedMessage.style.display =
            "none";

    }


    /* =====================================================
       UPDATE PAYMENT FORM VISIBILITY
    ====================================================== */

    function updatePaymentFormVisibility(
        bookingStatus,
        paymentStatus,
        finalPaymentStatus,
        slipUploaded,
        finalSlipUploaded
    ) {

        if (!paymentFormContainer) {

            return;

        }


        /* =================================================
           COMPLETED BOOKING
        ================================================== */

        if (
            bookingStatus === "completed"
        ) {

            /* ---------------------------------------------
               FINAL PAYMENT VERIFIED
            ---------------------------------------------- */

            if (
                finalPaymentStatus === "verified"
            ) {

                paymentFormContainer.style.display =
                    "none";

                return;

            }


            /* ---------------------------------------------
               FINAL PAYMENT REJECTED
            ---------------------------------------------- */

            if (
                finalPaymentStatus === "rejected"
            ) {

                paymentFormContainer.style.display =
                    "block";

                return;

            }


            /* ---------------------------------------------
               FINAL PAYMENT PENDING + SLIP
            ---------------------------------------------- */

            if (
                finalPaymentStatus === "pending" &&
                finalSlipUploaded
            ) {

                paymentFormContainer.style.display =
                    "none";

                return;

            }


            /* ---------------------------------------------
               FINAL PAYMENT PENDING + NO SLIP
            ---------------------------------------------- */

            if (
                finalPaymentStatus === "pending" &&
                !finalSlipUploaded
            ) {

                setPaymentStage(
                    "final",
                    remainingAmount
                );

                return;

            }


            /*
             * If final status is missing but
             * remaining amount exists, still allow
             * final payment.
             */

            if (
                remainingAmount > 0
            ) {

                setPaymentStage(
                    "final",
                    remainingAmount
                );

                return;

            }

            paymentFormContainer.style.display =
                "none";

            return;

        }


        /* =================================================
           ACCEPTED BOOKING
        ================================================== */

        if (
            bookingStatus === "accepted"
        ) {

            /* ---------------------------------------------
               PREPAYMENT VERIFIED
            ---------------------------------------------- */

            if (
                paymentStatus === "verified"
            ) {

                paymentFormContainer.style.display =
                    "none";

                return;

            }


            /* ---------------------------------------------
               PREPAYMENT PENDING + SLIP
            ---------------------------------------------- */

            if (
                paymentStatus === "pending" &&
                slipUploaded
            ) {

                paymentFormContainer.style.display =
                    "none";

                return;

            }


            /* ---------------------------------------------
               PREPAYMENT REQUIRED
            ---------------------------------------------- */

            if (
                prepaidAmount > 0 &&
                (
                    !paymentStatus ||
                    paymentStatus === "pending" ||
                    paymentStatus === "rejected"
                )
            ) {

                setPaymentStage(
                    "prepayment",
                    prepaidAmount
                );

                return;

            }


            paymentFormContainer.style.display =
                "none";

            return;

        }


        /* =================================================
           OTHER BOOKING STATUS
        ================================================== */

        paymentFormContainer.style.display =
            "none";

    }


    /* =====================================================
       APPLY PAYMENT STATUS
    ====================================================== */

    function applyPaymentStatus(data) {

        const bookingStatus =
            data.booking_status;

        const paymentStatus =
            data.status;

        const finalPaymentStatus =
            data.final_payment_status;

        const slipUploaded =
            data.slip_uploaded;

    
const finalSlipUploaded =
    Boolean(
        data.final_payment_submitted ||
        data.final_slip_uploaded
    );


        remainingAmount =
            Number(
                data.remaining_amount || 0
            );


        /*
         * Keep latest amount available
         * for payment method changes.
         */

        document.body.dataset.remainingAmount =
            remainingAmount;


        /* =================================================
           UPDATE CURRENT STATUS
        ================================================== */

        updateCurrentStatus(
            bookingStatus,
            paymentStatus,
            finalPaymentStatus,
            slipUploaded,
            finalSlipUploaded
        );


        /* =================================================
           UPDATE STATUS MESSAGE
        ================================================== */

        updateStatusMessage(
            bookingStatus,
            paymentStatus,
            finalPaymentStatus,
            slipUploaded,
            finalSlipUploaded,
            data.final_rejection_reason,
            data.rejection_reason
        );


        /* =================================================
           UPDATE REJECTION MESSAGE
        ================================================== */

        updateRejectionMessage(
            bookingStatus,
            paymentStatus,
            finalPaymentStatus,
            data.rejection_reason,
            data.final_rejection_reason
        );


        /* =================================================
           UPDATE PAYMENT FORM
        ================================================== */

        updatePaymentFormVisibility(
            bookingStatus,
            paymentStatus,
            finalPaymentStatus,
            slipUploaded,
            finalSlipUploaded
        );

    }


    /* =====================================================
       CHECK PAYMENT STATUS
    ====================================================== */

    async function checkPaymentStatus() {

        if (!bookingId) {

            return;

        }


        try {

            const response =
                await fetch(
                    `/customer/bookings/${bookingId}/payment/status`,
                    {
                        method: "GET",

                        headers: {
                            "Accept":
                                "application/json",

                            "X-Requested-With":
                                "XMLHttpRequest"
                        },

                        credentials:
                            "same-origin",

                        cache:
                            "no-store"
                    }
                );


            if (!response.ok) {

                return;

            }


            const data =
                await response.json();


            applyPaymentStatus(
                data
            );

        }
        catch (error) {

            console.error(
                "Payment status check failed:",
                error
            );

        }

    }


    /* =====================================================
       FORM VALIDATION
    ====================================================== */

    if (paymentForm) {

        paymentForm.addEventListener(
            "submit",
            function (event) {

                const selected =
                    getSelectedPaymentMethod();


                /* -----------------------------------------
                   PAYMENT METHOD REQUIRED
                ------------------------------------------ */

                if (!selected) {

                    event.preventDefault();

                    showAppAlert(
                        "Please select a payment method.",
                        "No Payment Method Selected"
                    );

                    return;

                }


                /* -----------------------------------------
                   PREPAYMENT MUST USE KBZPAY
                ------------------------------------------ */

                if (
                    currentStage === "prepayment" &&
                    selected !== "kbzpay"
                ) {

                    event.preventDefault();

                    showAppAlert(
                        "A KBZPay prepayment is required.",
                        "Invalid Payment Method"
                    );

                    return;

                }


                /* -----------------------------------------
                   KBZPAY SLIP REQUIRED
                ------------------------------------------ */

                if (
                    selected === "kbzpay" &&
                    (
                        !slipInput ||
                        !slipInput.files ||
                        !slipInput.files.length
                    )
                ) {

                    event.preventDefault();

                    showAppAlert(
                        "Please upload your KBZPay payment slip.",
                        "Payment Slip Required"
                    );


                    if (slipInput) {

                        slipInput.focus();

                    }

                    return;

                }

            }
        );

    }


    /* =====================================================
       INITIAL STATE
    ====================================================== */

    currentStage =
        "none";


    /*
     * Initial payment method display.
     */

    updatePaymentMethod();


    /* =====================================================
       INITIAL AJAX CHECK
    ====================================================== */

    checkPaymentStatus();


    /* =====================================================
       AUTOMATIC STATUS CHECK
       EVERY 1 SECOND
    ====================================================== */

    setInterval(
        checkPaymentStatus,
        1000
    );

});
