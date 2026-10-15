/* =========================================================
   QUICKFIX CUSTOMER BOOKING DETAIL
   AJAX STATUS POLLING
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const bookingDetailPage =
        document.querySelector(
            ".customer-booking-detail-content"
        );


    if (!bookingDetailPage) {
        return;
    }


    /* =====================================================
       PREVENT EMPTY HASH LINKS
    ====================================================== */

    const buttons =
        bookingDetailPage.querySelectorAll(
            'a[href="#"]'
        );


    buttons.forEach(function (button) {

        button.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

            }
        );

    });


    /* =====================================================
       BOOKING INFORMATION
    ====================================================== */

    const bookingId =
        bookingDetailPage.dataset.bookingId;

       const providerId =
    bookingDetailPage.dataset.providerId || "";
        

    const statusUrl =
        bookingDetailPage.dataset.statusUrl;
        const bookingType =
    bookingDetailPage.dataset.bookingType || "";


    if (!bookingId || !statusUrl) {

        console.warn(
            "QuickFix: Booking status polling could not start."
        );

        return;
    }


    /* =====================================================
       CURRENT STATUS
    ====================================================== */

    let currentStatus =
        bookingDetailPage.dataset.bookingStatus || "";


    /* =====================================================
       POLLING TIMER
    ====================================================== */

    let statusPolling = null;


    /* =====================================================
       STATUS LABELS
    ====================================================== */

    const statusLabels = {

        pending:
            "Pending",

        accepted:
            "Accepted",

        on_the_way:
            "On the Way",

        arrived:
            "Arrived",

        in_progress:
            "In Progress",

        completed:
            "Completed",

        cancelled:
            "Cancelled",

        timeout:
            "Timeout"

    };


    /* =====================================================
       STATUS CSS CLASSES
    ====================================================== */

    const statusClasses = {

        pending:
            "pending",

        accepted:
            "accepted",

        on_the_way:
            "on-the-way",

        arrived:
            "arrived",

        in_progress:
            "in-progress",

        completed:
            "completed",

        cancelled:
            "cancelled",

        timeout:
            "timeout"

    };


    /* =====================================================
       NORMAL BOOKING FLOW
    ====================================================== */

    const normalStatuses = [

        "pending",

        "accepted",

        "on_the_way",

        "arrived",

        "in_progress",

        "completed"

    ];


    /* =====================================================
       STATUS ICONS
    ====================================================== */

    const statusIcons = {

        pending:
            "fa-clock",

        accepted:
            "fa-circle-check",

        on_the_way:
            "fa-car-side",

        arrived:
            "fa-location-dot",

        in_progress:
            "fa-screwdriver-wrench",

        completed:
            "fa-check",

        cancelled:
            "fa-ban",

        timeout:
            "fa-clock"

    };


    /* =====================================================
       ESCAPE HTML
       Prevent user/provider text from being inserted
       directly into HTML.
    ====================================================== */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return "";
        }

        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

/* =====================================================
   UPDATE CANCELLATION UI
===================================================== */

function updateCancellationUI(data) {

    const cancellationSection =
        document.getElementById(
            "customerCancellationSection"
        );

    const cancelButton =
        document.getElementById(
            "cancelBookingButton"
        );


    if (!cancellationSection) {
        return;
    }


    const status =
        data.status || currentStatus;


    const type =
        data.booking_type ||
        bookingType;


    const canCancel =
        data.can_cancel === true;


    /*
     * --------------------------------------------------
     * FINAL STATES
     * --------------------------------------------------
     */

    if (
        status === "completed" ||
        status === "cancelled" ||
        status === "timeout"
    ) {

        if (cancelButton) {
            cancelButton.style.display = "none";
        }

        return;
    }


    /*
     * --------------------------------------------------
     * ASAP
     * --------------------------------------------------
     */

    if (type === "asap") {

        if (cancelButton) {
            cancelButton.style.display = "none";
        }

        return;
    }


    /*
     * --------------------------------------------------
     * SCHEDULED BOOKING
     * --------------------------------------------------
     */

    if (canCancel) {

        if (cancelButton) {
            cancelButton.style.display = "";
        }

        return;
    }


    /*
     * Cancellation is no longer allowed.
     */

    if (cancelButton) {
        cancelButton.style.display = "none";
    }

}
/* =====================================================
   UPDATE CANCELLATION / REFUND INFORMATION
===================================================== */

function updateCancellationInformation(data) {

    const cancellationSection =
        document.getElementById(
            "customerCancellationSection"
        );

    if (!cancellationSection) {
        return;
    }

    const oldInformation =
        cancellationSection.querySelector(
            ".dynamic-cancellation-information"
        );

    if (oldInformation) {
        oldInformation.remove();
    }

    if (data.status !== "cancelled") {
        return;
    }

    let html = `
        <div class="dynamic-cancellation-information">
    `;

    /*
     * Cancelled by
     */

    if (data.cancelled_by) {

        let cancelledBy = data.cancelled_by;

        if (cancelledBy === "customer") {
            cancelledBy = "You";
        } else if (cancelledBy === "provider") {
            cancelledBy = "Provider";
        }

        html += `
            <div class="mb-2">

                <strong>
                    Cancelled by:
                </strong>

                ${escapeHtml(cancelledBy)}

            </div>
        `;
    }

    /*
     * Cancellation reason
     */

    if (data.cancellation_reason) {

        html += `
            <div class="mb-2">

                <strong>
                    Cancellation Reason:
                </strong>

                ${escapeHtml(
                    data.cancellation_reason
                )}

            </div>
        `;
    }

    /*
     * Refund information
     */

    const refundedAmount =
        Number(data.refunded_amount || 0);

    if (
        data.refund_status &&
        data.refund_status !== "not_required"
    ) {

        let refundMessage = "";

        if (data.refund_status === "pending") {

            refundMessage =
                "Your prepaid amount is marked for refund and is awaiting processing.";

        } else if (
            data.refund_status === "refunded"
        ) {

            refundMessage =
                "Your prepaid amount has been refunded.";

        }

        html += `
            <div class="mb-2">

                <strong>
                    Refund:
                </strong>

                ${escapeHtml(refundMessage)}

            </div>
        `;

        if (refundedAmount > 0) {

            html += `
                <div>

                    <strong>
                        Refund Amount:
                    </strong>

                    ${refundedAmount.toFixed(2)}

                </div>
            `;
        }
    }

    html += `
        </div>
    `;

    cancellationSection.insertAdjacentHTML(
        "beforeend",
        html
    );
}
/* =====================================================
   UPDATE PAYMENT UI
===================================================== */

function updatePaymentUI(payment) {

    const paymentSection =
        document.getElementById(
            "finalPaymentSection"
        );

    if (!paymentSection) {
        return;
    }


    /*
     * No payment record
     */

    if (!payment) {

        paymentSection.innerHTML = "";

        return;
    }


    const remainingAmount =
        Number(
            payment.remaining_amount || 0
        );


    const finalPaymentStatus =
        payment.final_payment_status ||
        "not_required";


    const finalPaymentRequired =
        payment.final_payment_required === true;


    const finalPaymentPending =
        payment.final_payment_pending === true;


    const finalPaymentRejected =
        payment.final_payment_rejected === true;


    const finalPaymentVerified =
        payment.final_payment_verified === true;


    const paymentUrl =
        payment.payment_url ||
        "#";


    /*
     * --------------------------------------------------
     * FINAL PAYMENT VERIFIED
     * --------------------------------------------------
     */

    if (finalPaymentVerified) {

        paymentSection.innerHTML = `

            <div class="alert alert-success mb-0">

                <i
                    class="fa-solid fa-circle-check me-2"
                ></i>

                <strong>
                    Final Payment Verified
                </strong>

                <div class="small mt-1">

                    Your final payment has been
                    verified by the provider.

                </div>

            </div>

        `;

        return;
    }


    /*
     * --------------------------------------------------
     * FINAL PAYMENT REJECTED
     * --------------------------------------------------
     */

    if (finalPaymentRejected) {

        const rejectionReason =
            payment.final_rejection_reason ||
            "The payment was rejected by the provider.";


        paymentSection.innerHTML = `

            <div class="alert alert-danger">

                <div>

                    <i
                        class="fa-solid fa-circle-xmark me-2"
                    ></i>

                    <strong>
                        Final Payment Rejected
                    </strong>

                </div>


                <div class="small mt-2">

                    <strong>
                        Reason:
                    </strong>

                    ${escapeHtml(
                        rejectionReason
                    )}

                </div>


                <a
                    href="${escapeHtml(paymentUrl)}"
                    class="btn btn-primary btn-sm mt-3"
                >

                    <i
                        class="fa-solid fa-upload me-1"
                    ></i>

                    Update Final Payment

                </a>

            </div>

        `;

        return;
    }


    /*
     * --------------------------------------------------
     * FINAL PAYMENT PENDING
     * --------------------------------------------------
     */

    if (finalPaymentPending) {

        paymentSection.innerHTML = `

            <div class="alert alert-warning mb-0">

                <i
                    class="fa-solid fa-clock me-2"
                ></i>

                <strong>
                    Final Payment Pending Verification
                </strong>

                <div class="small mt-1">

                    Your final payment has been
                    submitted and is waiting for
                    provider verification.

                </div>

                <div class="mt-2">

                    Amount:

                    <strong>
                        ${remainingAmount.toFixed(2)} MMK
                    </strong>

                </div>

            </div>

        `;

        return;
    }


    /*
     * --------------------------------------------------
     * FINAL PAYMENT REQUIRED
     * --------------------------------------------------
     */

    if (
        finalPaymentRequired &&
        remainingAmount > 0
    ) {

        paymentSection.innerHTML = `

            <div class="alert alert-info">

                <div>

                    <i
                        class="fa-solid fa-credit-card me-2"
                    ></i>

                    <strong>
                        Final Payment Required
                    </strong>

                </div>


                <div class="mt-2">

                    Remaining Amount:

                    <strong>
                        ${remainingAmount.toFixed(2)} MMK
                    </strong>

                </div>


                <div class="small mt-1">

                    Please submit your final payment
                    after the service has been completed.

                </div>


                <a
                    href="${escapeHtml(paymentUrl)}"
                    class="btn btn-primary btn-sm mt-3"
                >

                    <i
                        class="fa-solid fa-money-bill-wave me-1"
                    ></i>

                    Make Final Payment

                </a>

            </div>

        `;

        return;
    }


    /*
     * --------------------------------------------------
     * NO FINAL PAYMENT REQUIRED
     * --------------------------------------------------
     */

    if (
        finalPaymentStatus === "not_required" ||
        remainingAmount <= 0
    ) {

        paymentSection.innerHTML = `

            <div class="alert alert-light mb-0">

                <i
                    class="fa-solid fa-circle-check me-2"
                ></i>

                No final payment is required.

            </div>

        `;

    }

}
    /* =====================================================
       UPDATE STATUS BADGE
    ====================================================== */

    function updateStatusBadge(status) {

        const statusElements =
            document.querySelectorAll(
                "#currentBookingStatus, #summaryBookingStatus"
            );


        statusElements.forEach(function (element) {


            /* Remove old classes */

            element.classList.remove(

                "pending",

                "accepted",

                "on-the-way",

                "arrived",

                "in-progress",

                "completed",

                "cancelled",

                "timeout"

            );


            /* Add current class */

            if (statusClasses[status]) {

                element.classList.add(
                    statusClasses[status]
                );

            }


            /* Update text */

            element.innerHTML = `

                <span></span>

                ${escapeHtml(
                    statusLabels[status] || status
                )}

            `;

        });

    }


    /* =====================================================
       FIND HISTORY ITEM
    ====================================================== */

    function findHistoryItem(history, status) {

        if (!Array.isArray(history)) {
            return null;
        }

        return history.find(function (item) {

            return item.status === status;

        }) || null;

    }


    /* =====================================================
       RENDER TIMELINE
    ====================================================== */

    function renderTimeline(
        status,
        history
    ) {

        const timeline =
            document.getElementById(
                "bookingStatusTimeline"
            );


        if (!timeline) {
            return;
        }


        /*
         * --------------------------------------------------
         * CANCELLED / TIMEOUT
         * --------------------------------------------------
         */

        if (
            status === "cancelled" ||
            status === "timeout"
        ) {

            const pendingHistory =
                findHistoryItem(
                    history,
                    "pending"
                );


            const alternativeHistory =
                findHistoryItem(
                    history,
                    status
                );


            const alternativeLabel =
                status === "cancelled"
                    ? "Cancelled"
                    : "Timeout";


            timeline.innerHTML = `

                <div class="timeline-item completed">

                    <div class="timeline-marker">

                        <i class="fa-solid fa-check"></i>

                    </div>


                    <div class="timeline-content">

                        <strong>
                            Pending
                        </strong>

                        <span>

                            ${
                                pendingHistory?.created_at
                                    ? escapeHtml(
                                        pendingHistory.created_at
                                    )
                                    : "Booking created"
                            }

                        </span>

                        ${
                            pendingHistory?.updated_by
                                ? `
                                    <small class="d-block mt-1 text-muted">

                                        <i
                                            class="fa-solid fa-user-pen me-1"
                                        ></i>

                                        Updated by
                                        ${escapeHtml(
                                            pendingHistory.updated_by
                                        )}

                                    </small>
                                `
                                : ""
                        }

                        ${
                            pendingHistory?.note
                                ? `
                                    <small class="d-block mt-1 text-muted">

                                        <i
                                            class="fa-solid fa-note-sticky me-1"
                                        ></i>

                                        ${escapeHtml(
                                            pendingHistory.note
                                        )}

                                    </small>
                                `
                                : ""
                        }

                    </div>

                </div>


                <div class="timeline-item current completed">

                    <div class="timeline-marker">

                        <i class="fa-solid fa-check"></i>

                    </div>


                    <div class="timeline-content">

                        <strong>
                            ${escapeHtml(
                                alternativeLabel
                            )}
                        </strong>

                        <span>

                            ${
                                alternativeHistory?.created_at
                                    ? escapeHtml(
                                        alternativeHistory.created_at
                                    )
                                    : "Current status"
                            }

                        </span>

                        ${
                            alternativeHistory?.updated_by
                                ? `
                                    <small class="d-block mt-1 text-muted">

                                        <i
                                            class="fa-solid fa-user-pen me-1"
                                        ></i>

                                        Updated by
                                        ${escapeHtml(
                                            alternativeHistory.updated_by
                                        )}

                                    </small>
                                `
                                : ""
                        }

                        ${
                            alternativeHistory?.note
                                ? `
                                    <div class="mt-2">

                                        <strong>
                                            Reason:
                                        </strong>

                                        <span>
                                            ${escapeHtml(
                                                alternativeHistory.note
                                            )}
                                        </span>

                                    </div>
                                `
                                : ""
                        }

                    </div>

                </div>

            `;

            return;
        }


        /*
         * --------------------------------------------------
         * NORMAL FLOW
         * --------------------------------------------------
         */

        let html = "";


        const currentIndex =
            normalStatuses.indexOf(status);


        normalStatuses.forEach(
            function (statusKey, index) {

                const historyItem =
                    findHistoryItem(
                        history,
                        statusKey
                    );


                const isCompleted =
                    currentIndex !== -1 &&
                    index < currentIndex;


                const isCurrent =
                    statusKey === status;


                let classes =
                    "timeline-item";


                if (isCompleted) {

                    classes +=
                        " completed";

                }


                if (isCurrent) {

                    classes +=
                        " current completed";

                }


                let icon = "";


                if (
                    isCompleted ||
                    isCurrent
                ) {

                    icon = `
                        <i class="fa-solid fa-check"></i>
                    `;

                }


                let timeText =
                    "Waiting";


                if (
                    historyItem &&
                    historyItem.created_at
                ) {

                    timeText =
                        escapeHtml(
                            historyItem.created_at
                        );

                }
                else if (isCurrent) {

                    timeText =
                        "Current status";

                }


                let updatedByHtml = "";


                if (
                    historyItem &&
                    historyItem.updated_by
                ) {

                    updatedByHtml = `

                        <small class="d-block mt-1 text-muted">

                            <i
                                class="fa-solid fa-user-pen me-1"
                            ></i>

                            Updated by

                            ${escapeHtml(
                                historyItem.updated_by
                            )}

                        </small>

                    `;

                }


                let noteHtml = "";


                if (
                    historyItem &&
                    historyItem.note
                ) {

                    noteHtml = `

                        <small class="d-block mt-1 text-muted">

                            <i
                                class="fa-solid fa-note-sticky me-1"
                            ></i>

                            ${escapeHtml(
                                historyItem.note
                            )}

                        </small>

                    `;

                }


                html += `

                    <div class="${classes}">

                        <div class="timeline-marker">

                            ${icon}

                        </div>


                        <div class="timeline-content">

                            <strong>

                                ${escapeHtml(
                                    statusLabels[statusKey]
                                )}

                            </strong>


                            <span>

                                ${timeText}

                            </span>


                            ${updatedByHtml}


                            ${noteHtml}

                        </div>

                    </div>

                `;

            }
        );


        timeline.innerHTML =
            html;

    }


    /* =====================================================
       PROVIDER CARD
    ====================================================== */

    function renderProviderCard(
        status,
        provider,
        history,
        cancellationData = {}
    ) {

        const providerSection =
            document.getElementById(
                "providerStatusSection"
            );


        if (!providerSection) {
            return;
        }


        /*
         * --------------------------------------------------
         * PENDING
         * --------------------------------------------------
         */

        if (status === "pending") {

            const providerName =
                provider?.name ||
                "Waiting for Provider";


            providerSection.innerHTML = `

                <div class="provider-detail-body">

                    <div class="provider-large-avatar">

                        <i class="fa-solid fa-user-clock"></i>

                    </div>


                    <div class="provider-detail-info">

                        <h4>

                            ${escapeHtml(
                                providerName
                            )}

                        </h4>


                        <span>
                            Waiting for provider response
                        </span>


                        <div class="provider-rating">

                            <i class="fa-solid fa-clock"></i>

                            <strong>
                                Pending
                            </strong>

                            <span>
                                Awaiting Acceptance
                            </span>

                        </div>

                    </div>

                </div>


                <div class="booking-provider-message">

                    <i class="fa-solid fa-hourglass-half"></i>

                    <span>

                        Your booking request has been sent
                        to the selected provider. Please wait
                        for the provider to accept your request.

                    </span>

                </div>

            `;

            return;
        }


        /*
         * --------------------------------------------------
         * CANCELLED
         * --------------------------------------------------
         */
if (status === "cancelled") {

    const cancelledBy =
        cancellationData.cancelled_by;

    const cancellationReason =
        cancellationData.cancellation_reason;

    let title =
        "Booking Cancelled";

    let message =
        "This booking has been cancelled.";

    if (cancelledBy === "customer") {

        title =
            "You Cancelled This Booking";

        message =
            "You cancelled this booking.";

    } else if (cancelledBy === "provider") {

        title =
            "Provider Cancelled This Booking";

        message =
            "The provider cancelled this booking.";

    }

    providerSection.innerHTML = `

        <div class="provider-detail-body">

            <div class="provider-large-avatar">

                <i class="fa-solid fa-ban"></i>

            </div>

            <div class="provider-detail-info">

                <h4>
                    ${escapeHtml(title)}
                </h4>

                <span>
                    ${escapeHtml(message)}
                </span>

            </div>

        </div>

        ${
            cancellationReason
                ? `

                    <div class="booking-provider-message">

                        <i
                            class="fa-solid fa-circle-info"
                        ></i>

                        <span>

                            <strong>
                                Cancellation Reason:
                            </strong>

                            ${escapeHtml(
                                cancellationReason
                            )}

                        </span>

                    </div>

                `
                : ""
        }

    `;

    return;
}
       


        /*
         * --------------------------------------------------
         * TIMEOUT
         * --------------------------------------------------
         */

        if (status === "timeout") {

            const timeoutHistory =
                findHistoryItem(
                    history,
                    "timeout"
                );

const isPrepaymentTimeout =
    timeoutHistory?.note
        ?.toLowerCase()
        .includes("prepayment slip") || false;

const timeoutTitle = isPrepaymentTimeout
    ? "Prepayment Deadline Expired"
    : "Provider Did Not Respond";

const timeoutDescription = isPrepaymentTimeout
    ? "The required prepayment slip was not submitted by the scheduled service time."
    : "The provider did not respond within the required time.";

const timeoutMessage = isPrepaymentTimeout
    ? "Your booking timed out because the required prepayment slip was not submitted before the scheduled service time."
    : "The response deadline has expired and this booking can no longer be accepted.";


            providerSection.innerHTML = `

                <div class="provider-detail-body">

                    <div class="provider-large-avatar">

                        <i class="fa-solid fa-clock"></i>

                    </div>


                    <div class="provider-detail-info">

                        <h4>
    ${escapeHtml(timeoutTitle)}
</h4>

                        <span>

                            ${escapeHtml(timeoutDescription)}

                        </span>


                        ${
                            timeoutHistory?.updated_by
                                ? `
                                    <small class="d-block mt-2 text-muted">

                                        <i
                                            class="fa-solid fa-user-pen me-1"
                                        ></i>

                                        Updated by

                                        ${escapeHtml(
                                            timeoutHistory.updated_by
                                        )}

                                    </small>
                                `
                                : ""
                        }

                    </div>

                </div>


                <div class="booking-provider-message">

                    <i class="fa-solid fa-hourglass-end"></i>

                    <span>

                        ${escapeHtml(timeoutMessage)}

                    </span>

                </div>

            `;

            return;
        }


        /*
         * --------------------------------------------------
         * ACTIVE / COMPLETED
         * --------------------------------------------------
         */

        if (
            status === "accepted" ||
            status === "on_the_way" ||
            status === "arrived" ||
            status === "in_progress" ||
            status === "completed"
        ) {

            if (!provider) {

                providerSection.innerHTML = `

                    <div class="provider-detail-body">

                        <div class="provider-large-avatar">

                            <i class="fa-solid fa-user-clock"></i>

                        </div>


                        <div class="provider-detail-info">

                            <h4>
                                Provider Not Available
                            </h4>

                            <span>

                                No provider is currently
                                assigned to this booking.

                            </span>

                        </div>

                    </div>

                `;

                return;
            }


            /*
             * Provider image
             */

            let avatarHtml = `

                <i class="fa-solid fa-user"></i>

            `;


            if (provider.profile_image) {

                avatarHtml = `

                    <img
                        src="${escapeHtml(
                            provider.profile_image
                        )}"
                        alt="${escapeHtml(
                            provider.name || "Provider"
                        )}"
                    >

                `;

            }


            /*
             * Experience
             */

            let experienceHtml = "";


            if (
                provider.experience_years !== null &&
                provider.experience_years !== undefined
            ) {

                const years =
                    Number(
                        provider.experience_years
                    );


                experienceHtml = `

                    <div class="mt-2">

                        <small>

                            <i
                                class="fa-solid fa-briefcase me-1"
                            ></i>

                            ${escapeHtml(
                                provider.experience_years
                            )}

                            ${
                                years === 1
                                    ? "year"
                                    : "years"
                            }

                            experience

                        </small>

                    </div>

                `;

            }


            /*
             * Provider status message
             */

            const providerMessages = {

                accepted: {

                    icon:
                        "fa-circle-check",

                    text:
                        "Your booking has been accepted by the provider."

                },

                on_the_way: {

                    icon:
                        "fa-car-side",

                    text:
                        "Your provider is on the way to your location."

                },

                arrived: {

                    icon:
                        "fa-location-dot",

                    text:
                        "Your provider has arrived at your location."

                },

                in_progress: {

                    icon:
                        "fa-screwdriver-wrench",

                    text:
                        "Your service is currently in progress."

                },

                completed: {

                    icon:
                        "fa-circle-check",

                    text:
                        "Your service has been completed successfully."

                }

            };

const message =
    providerMessages[status];

const chatProviderId =
    providerId;

if (!chatProviderId) {
    console.warn(
        "QuickFix: Provider ID is missing. Chat button cannot be created."
    );
    return;
}

const chatUrl =
    `/customer/chats?provider_id=${encodeURIComponent(chatProviderId)}`;

providerSection.innerHTML = `

                <div class="provider-detail-body">


                    <div class="provider-large-avatar">

                        ${avatarHtml}

                    </div>


                    <div class="provider-detail-info">

                        <h4>

                            ${escapeHtml(
                                provider.name ||
                                "Provider"
                            )}

                        </h4>


                        <span>
                            Home Service Provider
                        </span>


                        <div class="provider-rating">

                            <i class="fa-solid fa-star"></i>

                            <strong>

                                ${
                                    provider.rating !== null &&
                                    provider.rating !== undefined
                                        ? escapeHtml(
                                            provider.rating
                                        )
                                        : "N/A"
                                }

                            </strong>

                            <span>
                                Professional
                            </span>

                        </div>


                        ${experienceHtml}

                    </div>

                </div>


                ${
                    message
                        ? `
                            <div class="booking-provider-message">

                                <i
                                    class="fa-solid ${message.icon}"
                                ></i>

                                <span>

                                    ${escapeHtml(
                                        message.text
                                    )}

                                </span>

                            </div>
                        `
                        : ""
                }


                <a
                      href="${chatUrl}"
                    class="booking-chat-button"
                    
                >

                    <i class="fa-solid fa-comments"></i>

                    Chat with Provider

                </a>

            `;

        }

    }


    /* =====================================================
       UPDATE EVERYTHING
    ====================================================== */

    function updateBookingUI(data) {

        const newStatus =
            data.status;


        /*
         * Update status attribute
         */

        bookingDetailPage.dataset.bookingStatus =
            newStatus;


        /*
         * Update status badge
         */

        updateStatusBadge(
            newStatus
        );


        /*
         * Update timeline
         */

        renderTimeline(
            newStatus,
            data.history || []
        );


        /*
         * Update provider section
         */

      renderProviderCard(
    newStatus,
    data.provider || null,
    data.history || [],
    data
);

updateCancellationUI(data);

updateCancellationInformation(data);

updatePaymentUI(
    data.payment || null
);
    }


    /* =====================================================
       CHECK BOOKING STATUS
    ====================================================== */

    function checkBookingStatus() {

        fetch(
            statusUrl,
            {
                method: "GET",

                headers: {

                    "Accept":
                        "application/json",

                    "X-Requested-With":
                        "XMLHttpRequest"

                }
            }
        )
            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        "Unable to check booking status."
                    );

                }

                return response.json();

            })
            .then(function (data) {

                /*
                 * Validate Laravel response
                 */

                if (
                    !data.success ||
                    !data.status
                ) {

                    return;

                }


               const newStatus =
    data.status;


/*
 * Always update cancellation information.
 *
 * These values can change even when
 * booking status stays the same.
 */

updateCancellationUI(data);
updateCancellationInformation(data);

updatePaymentUI(
    data.payment || null
);


/*
 * Check review eligibility.
 *
 * This must happen BEFORE the
 * booking-status early return because
 * payment/review information can change
 * while booking status stays "completed".
 */

if (
    typeof window.quickFixCheckReview === "function"
) {

    window.quickFixCheckReview(
        data.review || null
    );

}


/*
 * Nothing changed in booking status.
 */

if (
    newStatus ===
    currentStatus
) {

    return;
}         /*
                 * Status changed
                 */

                currentStatus =
                    newStatus;


                /*
                 * Update page immediately
                 */

                updateBookingUI(
                    data
                );


                /*
                 * Stop polling when
                 * booking reaches final state.
                 */

              /*
 * Cancelled and timeout are final states.
 */

if (
    newStatus === "cancelled" ||
    newStatus === "timeout"
) {

    stopPolling();

}


/*
 * Completed is not always the final state.
 * Final payment may still be required.
 */

if (newStatus === "completed") {

    const payment =
        data.payment || null;

    const finalPaymentStatus =
        payment?.final_payment_status ||
        "not_required";

    const remainingAmount =
        Number(
            payment?.remaining_amount || 0
        );


    const finalPaymentFinished =
        finalPaymentStatus === "verified" ||
        (
            finalPaymentStatus === "not_required" &&
            remainingAmount <= 0
        );


    if (finalPaymentFinished) {

        stopPolling();

    }

}

            })
            .catch(function (error) {

                console.error(
                    "QuickFix booking status check failed:",
                    error
                );

            });

    }


    /* =====================================================
       STOP POLLING
    ====================================================== */

    function stopPolling() {

        if (statusPolling) {

            clearInterval(
                statusPolling
            );

            statusPolling = null;

        }

    }


    /* =====================================================
       FINAL STATUS CHECK
    ====================================================== */
if (
    currentStatus === "cancelled" ||
    currentStatus === "timeout"
) {

    return;

}


    /* =====================================================
       START POLLING
       
       1000 = 1 second
       5000 = 5 seconds
       ====================================================== */

    statusPolling =
        setInterval(
            checkBookingStatus,
            1000
        );


       /* =====================================================
       INITIAL CHECK
    ====================================================== */

    checkBookingStatus();


   
});