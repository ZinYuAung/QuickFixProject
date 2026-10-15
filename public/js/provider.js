/* =====================================================
   QUICKFIX PROVIDER JAVASCRIPT
===================================================== */

document.addEventListener("DOMContentLoaded", function () {


    /* =================================================
       SIDEBAR
    ================================================= */

    const layout =
        document.getElementById("providerLayout");

    const hamburger =
        document.querySelector(".sidebar-toggle");

    const logoToggle =
        document.querySelector(".sidebar-logo-toggle");

    const overlay =
        document.getElementById("providerOverlay");

    const mobileMenu =
        document.querySelector(".provider-mobile-menu");


    /*
     * Desktop:
     *
     * QuickFix   ☰
     *
     * click ☰
     *      ↓
     *
     * QuickFix icon only
     *
     * click icon
     *      ↓
     *
     * QuickFix   ☰
     */


    if (layout && hamburger && logoToggle) {


        /* =============================================
           HAMBURGER
        ============================================= */

        hamburger.addEventListener("click", function () {

            if (window.innerWidth > 767) {

                /* Desktop */

                layout.classList.toggle(
                    "sidebar-collapsed"
                );

            } else {

                /* Mobile */

                layout.classList.toggle(
                    "mobile-sidebar-open"
                );

            }

        });


        /* =============================================
           QUICKFIX LOGO
        ============================================= */

        logoToggle.addEventListener("click", function () {

            if (window.innerWidth > 767) {

                /* Desktop */

                layout.classList.remove(
                    "sidebar-collapsed"
                );

            } else {

                /* Mobile */

                layout.classList.remove(
                    "mobile-sidebar-open"
                );

            }

        });

    }


    /* =================================================
       MOBILE MENU BUTTON
    ================================================= */

    if (mobileMenu && layout) {

        mobileMenu.addEventListener(
            "click",
            function () {

                layout.classList.add(
                    "mobile-sidebar-open"
                );

            }
        );

    }


    /* =================================================
       MOBILE OVERLAY
    ================================================= */

    if (overlay && layout) {

        overlay.addEventListener(
            "click",
            function () {

                layout.classList.remove(
                    "mobile-sidebar-open"
                );

            }
        );

    }


    /* =================================================
       MOBILE MENU LINKS
    ================================================= */

    const menuLinks =
        document.querySelectorAll(
            ".provider-menu-link"
        );


    menuLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function () {

                if (window.innerWidth <= 767) {

                    layout.classList.remove(
                        "mobile-sidebar-open"
                    );

                }

            }
        );

    });


    /* =================================================
       WINDOW RESIZE
    ================================================= */

    window.addEventListener(
        "resize",
        function () {

            if (window.innerWidth > 767) {

                layout.classList.remove(
                    "mobile-sidebar-open"
                );

            }

        }
    );


    
    /* =================================================
       ONLINE / OFFLINE
       SAVE STATUS TO DATABASE
    ================================================= */

    const statusContainer =
        document.querySelector(".provider-status");

    const statusToggle =
        document.querySelector(".status-toggle");

    const statusText =
        document.querySelector(".status-text");

    const statusDot =
        document.querySelector(".status-dot");

    if (
        statusContainer &&
        statusToggle &&
        statusText &&
        statusDot
    ) {

        statusToggle.addEventListener(
            "change",
            async function () {

                const previousChecked = !this.checked;

                const requestedStatus =
                    this.checked ? "online" : "offline";

                const statusUrl =
                    statusContainer.dataset.statusUrl;

                const csrfToken =
                    statusContainer.dataset.csrfToken;

                if (!statusUrl || !csrfToken) {

                    this.checked = previousChecked;

                    showAppAlert(
                        "Availability settings are missing. Please check the dashboard Blade section.",
                        "Settings Missing"
                    );

                    return;
                }

                // Prevent multiple requests while saving.
                this.disabled = true;

                try {

                    const response = await fetch(
                        statusUrl,
                        {
                            method: "POST",

                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": csrfToken
                            },

                            body: JSON.stringify({
                                availability_status: requestedStatus
                            })
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            "Failed to update availability."
                        );
                    }

                    // Use the status saved by Laravel.
                    const isOnline =
                        data.availability_status === "online";

                    statusToggle.checked = isOnline;

                    statusText.textContent =
                        isOnline ? "Online" : "Offline";

                    statusText.style.color =
                        isOnline ? "#16a34a" : "#64748b";

                    statusDot.style.background =
                        isOnline ? "#16a34a" : "#94a3b8";

                } catch (error) {

                    // Restore the previous state if saving fails.
                    statusToggle.checked = previousChecked;

                    showAppAlert(
                        error.message ||
                        "Could not update availability. Please try again.",
                        "Update Failed"
                    );

                    console.error(
                        "Provider availability error:",
                        error
                    );

                } finally {

                    statusToggle.disabled = false;

                }

            }
        );

    }


    /* =================================================
       DARK / LIGHT MODE
    ================================================= */

    const themeToggle =
        document.getElementById(
            "themeToggle"
        );

    const themeIcon =
        document.getElementById(
            "themeIcon"
        );


    if (
        themeToggle &&
        themeIcon
    ) {


        /* =============================================
           CHECK SAVED THEME
        ============================================= */

        const savedTheme =
            localStorage.getItem(
                "quickfix-theme"
            );


        if (savedTheme === "dark") {

            document.body.classList.add(
                "dark-mode"
            );

            themeIcon.classList.remove(
                "fa-moon"
            );

            themeIcon.classList.add(
                "fa-sun"
            );

            themeToggle.setAttribute(
                "aria-label",
                "Switch to light mode"
            );

            themeToggle.setAttribute(
                "title",
                "Switch to light mode"
            );

        }


        /* =============================================
           THEME BUTTON CLICK
        ============================================= */

        themeToggle.addEventListener(
            "click",
            function () {


                document.body.classList.toggle(
                    "dark-mode"
                );


                /* =====================================
                   DARK MODE
                ===================================== */

                if (
                    document.body.classList.contains(
                        "dark-mode"
                    )
                ) {

                    themeIcon.classList.remove(
                        "fa-moon"
                    );

                    themeIcon.classList.add(
                        "fa-sun"
                    );


                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to light mode"
                    );

                    themeToggle.setAttribute(
                        "title",
                        "Switch to light mode"
                    );


                    localStorage.setItem(
                        "quickfix-theme",
                        "dark"
                    );


                }


                /* =====================================
                   LIGHT MODE
                ===================================== */

                else {

                    themeIcon.classList.remove(
                        "fa-sun"
                    );

                    themeIcon.classList.add(
                        "fa-moon"
                    );


                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to dark mode"
                    );

                    themeToggle.setAttribute(
                        "title",
                        "Switch to dark mode"
                    );


                    localStorage.setItem(
                        "quickfix-theme",
                        "light"
                    );

                }

            }
        );

    }


    /* =========================================================
       SERVICE EDIT
       UI ONLY
    ========================================================= */

    function editService(button) {

        const card =
            button.closest(
                ".provider-service-card"
            );

        if (!card) {
            return;
        }


        const name =
            card.querySelector(
                ".service-name"
            );

        const description =
            card.querySelector(
                ".service-description"
            );

        const nameInput =
            card.querySelector(
                ".service-edit-name"
            );

        const descriptionInput =
            card.querySelector(
                ".service-edit-input"
            );

        const editButton =
            card.querySelector(
                ".service-edit-btn"
            );

        const actions =
            card.querySelector(
                ".service-edit-actions"
            );


        /*
         * Store current values.
         * Used when Cancel is clicked.
         */

        card.dataset.originalName =
            name.textContent.trim();

        card.dataset.originalDescription =
            description.textContent.trim();


        /*
         * Put current values into inputs.
         */

        nameInput.value =
            name.textContent.trim();

        descriptionInput.value =
            description.textContent.trim();


        /*
         * Hide read-only content.
         */

        name.style.display =
            "none";

        description.style.display =
            "none";


        /*
         * Show editable inputs.
         */

        nameInput.style.display =
            "block";

        descriptionInput.style.display =
            "block";


        /*
         * Hide Edit button.
         */

        editButton.style.display =
            "none";


        /*
         * Show Save / Cancel buttons.
         */

        actions.style.display =
            "flex";

    }


    /* =========================================================
       SAVE SERVICE
       UI ONLY
    ========================================================= */

    function saveService(button) {

        const card =
            button.closest(
                ".provider-service-card"
            );

        if (!card) {
            return;
        }


        const name =
            card.querySelector(
                ".service-name"
            );

        const description =
            card.querySelector(
                ".service-description"
            );

        const nameInput =
            card.querySelector(
                ".service-edit-name"
            );

        const descriptionInput =
            card.querySelector(
                ".service-edit-input"
            );

        const editButton =
            card.querySelector(
                ".service-edit-btn"
            );

        const actions =
            card.querySelector(
                ".service-edit-actions"
            );


        const newName =
            nameInput.value.trim();

        const newDescription =
            descriptionInput.value.trim();


        /* =============================================
           SIMPLE VALIDATION
        ============================================= */

        if (newName === "") {

            nameInput.focus();

            return;

        }


        if (newDescription === "") {

            descriptionInput.focus();

            return;

        }


        /*
         * Update displayed values.
         *
         * UI ONLY.
         * Database is NOT updated.
         */

        name.textContent =
            newName;

        description.textContent =
            newDescription;


        /*
         * Return to read-only mode.
         */

        name.style.display =
            "block";

        description.style.display =
            "block";

        nameInput.style.display =
            "none";

        descriptionInput.style.display =
            "none";

        editButton.style.display =
            "inline-flex";

        actions.style.display =
            "none";

    }


    /* =========================================================
       CANCEL SERVICE EDIT
       UI ONLY
    ========================================================= */

    function cancelService(button) {

        const card =
            button.closest(
                ".provider-service-card"
            );

        if (!card) {
            return;
        }


        const name =
            card.querySelector(
                ".service-name"
            );

        const description =
            card.querySelector(
                ".service-description"
            );

        const nameInput =
            card.querySelector(
                ".service-edit-name"
            );

        const descriptionInput =
            card.querySelector(
                ".service-edit-input"
            );

        const editButton =
            card.querySelector(
                ".service-edit-btn"
            );

        const actions =
            card.querySelector(
                ".service-edit-actions"
            );


        /*
         * Restore original values.
         */

        name.textContent =
            card.dataset.originalName ||
            name.textContent;

        description.textContent =
            card.dataset.originalDescription ||
            description.textContent;


        /*
         * Return to read-only mode.
         */

        name.style.display =
            "block";

        description.style.display =
            "block";

        nameInput.style.display =
            "none";

        descriptionInput.style.display =
            "none";

        editButton.style.display =
            "inline-flex";

        actions.style.display =
            "none";

    }
        /* =========================================================
       PROVIDER NOTIFICATIONS
    ========================================================= */

    const notificationButton =
        document.getElementById(
            "providerNotificationButton"
        );

    const notificationDropdown =
        document.getElementById(
            "providerNotificationDropdown"
        );

    const notificationList =
        document.getElementById(
            "providerNotificationList"
        );

    const notificationBadge =
        document.getElementById(
            "providerNotificationBadge"
        );

    const notificationUnreadText =
        document.getElementById(
            "providerNotificationUnreadText"
        );


    /* =========================================================
       OPEN / CLOSE NOTIFICATION DROPDOWN
    ========================================================= */

    if (
        notificationButton &&
        notificationDropdown
    ) {

        notificationButton.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                notificationDropdown.classList.toggle(
                    "show"
                );

            }
        );


        document.addEventListener(
            "click",
            function (event) {

                if (
                    !notificationDropdown.contains(event.target) &&
                    !notificationButton.contains(event.target)
                ) {

                    notificationDropdown.classList.remove(
                        "show"
                    );

                }

            }
        );

    }


    /* =========================================================
       CSRF TOKEN
    ========================================================= */

    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute("content");


    /* =========================================================
       UPDATE UNREAD COUNT
    ========================================================= */

    function updateProviderNotificationCount(count) {

        /* Header badge */

        let badge =
            document.getElementById(
                "providerNotificationBadge"
            );


        if (count > 0) {

            if (!badge && notificationButton) {

                badge =
                    document.createElement("span");

                badge.id =
                    "providerNotificationBadge";

                badge.className =
                    "provider-notification-badge";

                notificationButton.appendChild(
                    badge
                );

            }

            if (badge) {

                badge.textContent =
                    count > 99
                        ? "99+"
                        : count;

            }

        } else {

            if (badge) {
                badge.remove();
            }

        }


        /* Dropdown text */

        if (notificationUnreadText) {

            notificationUnreadText.textContent =
                count + " unread";

        }


        /* Sidebar badge */

        const menuBadge =
            document.getElementById(
                "providerNotificationMenuBadge"
            );

        if (menuBadge) {

            if (count > 0) {

                menuBadge.textContent =
                    count > 99
                        ? "99+"
                        : count;

            } else {

                menuBadge.remove();

            }

        }

    }



function buildProviderNotification(
    notification
) {

    const item =
        document.createElement("div");

    item.className =
        "provider-notification-item unread";

    item.dataset.id =
        notification.id;


    /*
     * =====================================================
     * NOTIFICATION DESTINATION
     * =====================================================
     */

    let notificationLink = "#";


    if (
        notification.type === "payment_submitted" &&
        notification.payment_id
    ) {

        notificationLink =
            "/provider/payments/" +
            notification.payment_id;

    }

    else if (notification.booking_id) {

        notificationLink =
            "/provider/bookings/" +
            notification.booking_id;

    }


    /*
     * =====================================================
     * HAS LINK?
     * =====================================================
     */

    const hasLink =
        notificationLink !== "#";


    /*
     * =====================================================
     * BOOKING PAYMENT INFORMATION
     * =====================================================
     *
     * Only booking_created notifications
     * contain these payment fields.
     */

    let paymentInfo = "";


    if (
        notification.type === "booking_created"
    ) {

        const serviceAmount =
            Number(
                notification.service_amount || 0
            ).toLocaleString(
                "en-US",
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );


        const prepaidAmount =
            Number(
                notification.prepaid_amount || 0
            ).toLocaleString(
                "en-US",
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );


        const prepaymentRequired =
            notification.prepayment_required === true ||
            notification.prepayment_required === 1 ||
            notification.prepayment_required === "1";


        const paymentMethod =
            notification.payment_method === "kbzpay"
                ? "KBZPay"
                : notification.payment_method === "cod"
                    ? "COD"
                    : "N/A";


        const paymentStatus =
            notification.payment_status === "pending"
                ? "Pending"
                : notification.payment_status === "verified"
                    ? "Verified"
                    : notification.payment_status === "rejected"
                        ? "Rejected"
                        : "N/A";


        paymentInfo = `

            <div class="provider-notification-payment-info">

                <div>
                    <strong>Booking:</strong>
                    #${notification.booking_id ?? "N/A"}
                </div>


                <div>
                    <strong>Service Amount:</strong>
                    ${serviceAmount} MMK
                </div>


                <div>
                    <strong>Prepayment Required:</strong>
                    ${
                        prepaymentRequired
                            ? prepaidAmount + " MMK"
                            : "No"
                    }
                </div>


                <div>
                    <strong>Payment Method:</strong>
                    ${paymentMethod}
                </div>


                <div>
                    <strong>Payment Status:</strong>
                    ${paymentStatus}
                </div>

            </div>

        `;

    }


    /*
     * =====================================================
     * BUILD HTML
     * =====================================================
     */

    item.innerHTML = `

        <div class="provider-notification-item-icon">

            <i class="fa-solid fa-bell"></i>

        </div>


        <div class="provider-notification-item-content">

            ${
                hasLink
                    ? `
                        <a
                            href="${notificationLink}"
                            class="provider-notification-link"
                            data-notification-id="${notification.id}"
                        >

                            <strong>
                                ${notification.title}
                            </strong>


                            <p>
                                ${notification.message}
                            </p>


                            ${paymentInfo}

                        </a>
                    `
                    : `
                        <strong>
                            ${notification.title}
                        </strong>


                        <p>
                            ${notification.message}
                        </p>


                        ${paymentInfo}

                    `
            }


            <small>
                ${notification.created_at}
            </small>

        </div>


        <div class="provider-notification-actions">

            <button
                type="button"
                class="btn btn-sm btn-light provider-mark-read-btn"
                data-id="${notification.id}"
            >

                <i class="fa-solid fa-check"></i>

                Read

            </button>


            <button
                type="button"
                class="btn btn-sm btn-outline-danger provider-delete-notification-btn"
                data-id="${notification.id}"
            >

                <i class="fa-solid fa-trash"></i>

            </button>

        </div>


        <span
            class="provider-notification-unread-dot"
        ></span>

    `;


    return item;

}


    /* =========================================================
       POLL UNREAD NOTIFICATIONS
    ========================================================= */

    async function pollProviderNotifications() {

        if (!notificationList) {
            return;
        }


        try {

            const response =
                await fetch(
                    "/provider/notifications/unread",
                    {
                        method: "GET",
                        headers: {
                            "Accept": "application/json",
                            "X-Requested-With":
                                "XMLHttpRequest"
                        }
                    }
                );


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            if (!data.success) {
                return;
            }


            updateProviderNotificationCount(
                data.count
            );


          /*
 * Do not rebuild the notification list
 * while Read/Delete is being processed.
 */
if (notificationList.dataset.actionRunning === "true") {
    return;
}

notificationList.innerHTML = "";


            if (
                !data.notifications ||
                data.notifications.length === 0
            ) {

                notificationList.innerHTML = `

                    <div
                        class="provider-notification-empty"
                    >

                        <i
                            class="fa-regular fa-bell-slash"
                        ></i>

                        <p>
                            No notifications
                        </p>

                    </div>

                `;

                return;

            }


            data.notifications.forEach(
                function (notification) {

                    notificationList.appendChild(
                        buildProviderNotification(
                            notification
                        )
                    );

                }
            );


        } catch (error) {

            console.error(
                "Provider notification polling error:",
                error
            );

        }

    }

    /* =========================================================
   MARK ONE NOTIFICATION AS READ
========================================================= */

async function markProviderNotificationAsRead(
    notificationId,
    element
) {

    /*
     * Tell polling that a user action is running.
     *
     * This prevents the 1-second polling from
     * rebuilding the notification list while
     * the Read request is being processed.
     */
    if (notificationList) {

        notificationList.dataset.actionRunning =
            "true";

    }


    try {

        const response =
            await fetch(
                `/provider/notifications/${notificationId}/read`,
                {
                    method: "POST",

                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept":
                            "application/json",
                        "X-Requested-With":
                            "XMLHttpRequest"
                    }
                }
            );


        if (!response.ok) {
            return;
        }


        const data =
            await response.json();


        if (!data.success) {
            return;
        }


        /*
         * Remove from unread dropdown.
         */

        if (element) {

            const item =
                element.closest(
                    ".provider-notification-item"
                );

            if (item) {
                item.remove();
            }

        }


        /*
         * Refresh unread count and
         * notification list.
         *
         * We will allow polling again
         * in finally below.
         */

    } catch (error) {

        console.error(
            "Mark notification as read error:",
            error
        );

    } finally {

        /*
         * Allow polling again.
         */

        if (notificationList) {

            notificationList.dataset.actionRunning =
                "false";

        }


        /*
         * Refresh unread count/list
         * after the action is finished.
         */

        await pollProviderNotifications();

    }

}


    /* =========================================================
   DELETE ONE NOTIFICATION
========================================================= */

async function deleteProviderNotification(
    notificationId,
    element
) {

    /*
     * Tell polling that a user action is running.
     *
     * This prevents the 1-second polling from
     * rebuilding the notification list while
     * the Delete request is being processed.
     */
    if (notificationList) {

        notificationList.dataset.actionRunning =
            "true";

    }


    try {

        const response =
            await fetch(
                `/provider/notifications/${notificationId}`,
                {
                    method: "DELETE",

                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept":
                            "application/json",
                        "X-Requested-With":
                            "XMLHttpRequest"
                    }
                }
            );


        if (!response.ok) {
            return;
        }


        const data =
            await response.json();


        if (!data.success) {
            return;
        }


        /*
         * Remove from dropdown.
         */

        if (element) {

            const item =
                element.closest(
                    ".provider-notification-item"
                );

            if (item) {
                item.remove();
            }

        }


    } catch (error) {

        console.error(
            "Delete notification error:",
            error
        );

    } finally {

        /*
         * Allow polling again.
         */

        if (notificationList) {

            notificationList.dataset.actionRunning =
                "false";

        }


        /*
         * Refresh unread count/list
         * after the action is finished.
         */

        await pollProviderNotifications();

    }

}

    /* =========================================================
   EVENT DELEGATION
   IMPORTANT FOR AJAX-GENERATED BUTTONS
========================================================= */

if (notificationList) {

    notificationList.addEventListener(
        "click",
        function (event) {


            /* =================================================
               READ BUTTON
            ================================================= */

            const readButton =
                event.target.closest(
                    ".provider-mark-read-btn"
                );


            if (readButton) {

                event.preventDefault();

                event.stopPropagation();

                const notificationId =
                    readButton.dataset.id;


                if (!notificationId) {
                    return;
                }


                markProviderNotificationAsRead(
                    notificationId,
                    readButton
                );

                return;

            }



            /* =================================================
               DELETE BUTTON
            ================================================= */

            const deleteButton =
                event.target.closest(
                    ".provider-delete-notification-btn"
                );


            if (deleteButton) {

                event.preventDefault();

                event.stopPropagation();

                const notificationId =
                    deleteButton.dataset.id;


                if (!notificationId) {
                    return;
                }


                deleteProviderNotification(
                    notificationId,
                    deleteButton
                );

                return;

            }

        }
    );

}

    /* =========================================================
       NOTIFICATION LINK
       MARK AS READ BEFORE OPENING BOOKING
    ========================================================= */

    if (notificationList) {

        notificationList.addEventListener(
            "click",
            async function (event) {

                const link =
                    event.target.closest(
                        ".provider-notification-link"
                    );


                if (!link) {
                    return;
                }


                event.preventDefault();


                const notificationId =
                    link.dataset.notificationId;

                const destination =
                    link.href;


                try {

                    await fetch(
                        `/provider/notifications/${notificationId}/read`,
                        {
                            method: "POST",

                            headers: {
                                "X-CSRF-TOKEN":
                                    csrfToken,
                                "Accept":
                                    "application/json",
                                "X-Requested-With":
                                    "XMLHttpRequest"
                            }
                        }
                    );

                } catch (error) {

                    console.error(
                        "Notification read error:",
                        error
                    );

                }


                window.location.href =
                    destination;

            }
        );

    }


    /* =========================================================
       MARK ALL AS READ
    ========================================================= */

    const markAllButton =
        document.getElementById(
            "providerMarkAllReadBtn"
        );


    if (markAllButton) {

        markAllButton.addEventListener(
            "click",
            async function () {

                try {

                    const response =
                        await fetch(
                            "/provider/notifications/read-all",
                            {
                                method: "POST",

                                headers: {
                                    "X-CSRF-TOKEN":
                                        csrfToken,
                                    "Accept":
                                        "application/json",
                                    "X-Requested-With":
                                        "XMLHttpRequest"
                                }
                            }
                        );


                    if (!response.ok) {
                        return;
                    }


                    const data =
                        await response.json();


                    if (!data.success) {
                        return;
                    }


                    /*
                     * Refresh dropdown/count.
                     */

                    await pollProviderNotifications();


                    /*
                     * Reload page so the
                     * full notification list
                     * reflects read_at.
                     */

                    window.location.reload();

                } catch (error) {

                    console.error(
                        "Mark all notifications error:",
                        error
                    );

                }

            }
        );

    }


    /* =========================================================
       FULL PAGE — READ
    ========================================================= */

    document.addEventListener(
        "click",
        async function (event) {

            const button =
                event.target.closest(
                    ".provider-page-mark-read-btn"
                );


            if (!button) {
                return;
            }


            const notificationId =
                button.dataset.id;


            try {

                const response =
                    await fetch(
                        `/provider/notifications/${notificationId}/read`,
                        {
                            method: "POST",

                            headers: {
                                "X-CSRF-TOKEN":
                                    csrfToken,
                                "Accept":
                                    "application/json",
                                "X-Requested-With":
                                    "XMLHttpRequest"
                            }
                        }
                    );


                if (!response.ok) {
                    return;
                }


                const data =
                    await response.json();


                if (!data.success) {
                    return;
                }


                const item =
                    button.closest(
                        ".provider-notification-page-item"
                    );


                if (item) {

                    item.classList.remove(
                        "unread"
                    );

                }


                button.replaceWith(
                    Object.assign(
                        document.createElement("span"),
                        {
                            className:
                                "text-muted small",
                            textContent:
                                "Read"
                        }
                    )
                );


                const dot =
                    item?.querySelector(
                        ".provider-unread-dot"
                    );

                if (dot) {
                    dot.remove();
                }


                pollProviderNotifications();


            } catch (error) {

                console.error(
                    "Provider page read error:",
                    error
                );

            }

        }
    );


    /* =========================================================
       FULL PAGE — DELETE
    ========================================================= */

    document.addEventListener(
        "click",
        async function (event) {

            const button =
                event.target.closest(
                    ".provider-page-delete-btn"
                );


            if (!button) {
                return;
            }


            const notificationId =
                button.dataset.id;


            try {

                const response =
                    await fetch(
                        `/provider/notifications/${notificationId}`,
                        {
                            method: "DELETE",

                            headers: {
                                "X-CSRF-TOKEN":
                                    csrfToken,
                                "Accept":
                                    "application/json",
                                "X-Requested-With":
                                    "XMLHttpRequest"
                            }
                        }
                    );


                if (!response.ok) {
                    return;
                }


                const data =
                    await response.json();


                if (!data.success) {
                    return;
                }


                const item =
                    button.closest(
                        ".provider-notification-page-item"
                    );


                if (item) {
                    item.remove();
                }


                pollProviderNotifications();


            } catch (error) {

                console.error(
                    "Provider page delete error:",
                    error
                );

            }

        }
    );

    /* =========================================================
       PROVIDER CHAT UNREAD COUNT
    ========================================================= */

    const providerSidebarChatUnread =
        document.getElementById(
            "providerSidebarChatUnread"
        );


    async function pollProviderChatUnreadCount() {

        if (!providerSidebarChatUnread) {
            return;
        }


        try {

            const response =
                await fetch(
                    "/provider/chats/unread-counts",
                    {
                        method: "GET",

                        headers: {
                            "Accept": "application/json",
                            "X-Requested-With":
                                "XMLHttpRequest"
                        }
                    }
                );


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            if (!data.success) {
                return;
            }


            const total =
                Number(data.total || 0);


            if (total > 0) {

                providerSidebarChatUnread.textContent =
                    total > 99
                        ? "99+"
                        : total;

                providerSidebarChatUnread.style.display =
                    "inline-flex";

            } else {

                providerSidebarChatUnread.textContent =
                    "0";

                providerSidebarChatUnread.style.display =
                    "none";

            }


        } catch (error) {

            console.error(
                "Provider chat unread count error:",
                error
            );

        }

    }


    /* =========================================================
       START CHAT UNREAD POLLING
    ========================================================= */

    if (providerSidebarChatUnread) {

        pollProviderChatUnreadCount();

        setInterval(
            pollProviderChatUnreadCount,
            1000
        );

    }
    /* =========================================================
       START POLLING
    ========================================================= */

    if (notificationList) {

        pollProviderNotifications();


        /*
         * Check every 1 second.
         * No page refresh required.
         */

        setInterval(
            pollProviderNotifications,
            1000
        );

    }

});
// ============================================================
// PROVIDER BOOKING REQUEST AJAX
// ============================================================

document.addEventListener('submit', async function (event) {

    const form = event.target.closest(
        '.provider-booking-action-form'
    );

    if (!form) {
        return;
    }

    event.preventDefault();

    const action = form.dataset.action;
    const bookingId = form.dataset.bookingId;
    const button = form.querySelector(
        'button[type="submit"]'
    );

    // --------------------------------------------------------
    // Reject confirmation
    // --------------------------------------------------------

    if (action === 'reject') {

        const confirmed = confirm(
            'Are you sure you want to reject this booking?'
        );

        if (!confirmed) {
            return;
        }
    }

    // --------------------------------------------------------
    // Save original button
    // --------------------------------------------------------

    const originalButtonHTML = button.innerHTML;

    button.disabled = true;

    button.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i>';

    try {

        // ----------------------------------------------------
        // Send AJAX request
        // ----------------------------------------------------

        const response = await fetch(
            form.action,
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute('content'),

                    'Accept': 'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'
                },

                body: new FormData(form)
            }
        );

        const data = await response.json();

        // ----------------------------------------------------
        // Check response
        // ----------------------------------------------------

        if (
            !response.ok ||
            !data.success
        ) {
            throw new Error(
                data.message ||
                'Booking action failed.'
            );
        }

        // ----------------------------------------------------
        // Remove booking request from dashboard
        // ----------------------------------------------------

        const requestElement =
            document.getElementById(
                `booking-request-${bookingId}`
            );

        if (requestElement) {
            requestElement.remove();
        }

        // ----------------------------------------------------
        // Show empty message if no requests remain
        // ----------------------------------------------------

        const container =
            document.getElementById(
                'providerBookingRequests'
            );

        if (
            container &&
            !container.querySelector(
                '.booking-request'
            )
        ) {

            container.innerHTML = `
                <div
                    class="text-center py-4"
                    id="no-provider-booking-requests"
                >
                    <i
                        class="fa-solid fa-calendar-check mb-2"
                        style="font-size: 28px; opacity: 0.5;"
                    ></i>

                    <p class="mb-0">
                        No pending booking requests.
                    </p>
                </div>
            `;
        }

        // ----------------------------------------------------
        // Success message
        // ----------------------------------------------------

        showProviderBookingMessage(
            data.message ||
            'Booking updated successfully.',
            'success'
        );

    } catch (error) {

        console.error(
            'Provider booking action error:',
            error
        );

        showProviderBookingMessage(
            error.message ||
            'Something went wrong.',
            'danger'
        );

        // ----------------------------------------------------
        // Restore button if request failed
        // ----------------------------------------------------

        button.disabled = false;

        button.innerHTML =
            originalButtonHTML;
    }

});

// ============================================================
// PROVIDER TODAY'S JOBS AJAX
// ============================================================

async function loadTodayJobs() {

    const container =
        document.getElementById(
            'providerTodayJobs'
        );

    if (!container) {
        return;
    }

    try {

        const todayJobsUrl =
    container.dataset.todayJobsUrl;

if (!todayJobsUrl) {
    console.error(
        'Today jobs URL is missing.'
    );
    return;
}

const response = await fetch(
    todayJobsUrl,
    {
        method: 'GET',

        headers: {
            'Accept': 'application/json',
            'X-Requested-With':
                'XMLHttpRequest'
        }
    }
);

        if (!response.ok) {
            return;
        }

        const data =
            await response.json();

        if (!data.success) {
            return;
        }

        // ====================================================
        // NO TODAY'S JOBS
        // ====================================================

        if (
            !data.todayJobs ||
            data.todayJobs.length === 0
        ) {

            container.innerHTML = `
                <div class="text-center py-4">

                    <i
                        class="fa-solid fa-calendar-check mb-2"
                        style="font-size: 28px; opacity: 0.5;"
                    ></i>

                    <p class="mb-0">
                        No jobs scheduled for today.
                    </p>

                </div>
            `;

            return;
        }


        // ====================================================
        // BUILD TODAY'S JOBS
        // ====================================================

        container.innerHTML = '';


        data.todayJobs.forEach(function (job) {

            const item =
                document.createElement('div');

            item.className =
                'job-item';

            // ------------------------------------------------
            // STATUS CLASS
            // ------------------------------------------------

            let statusClass =
                'pending';

            if (
                job.status === 'accepted' ||
                job.status === 'in_progress'
            ) {

                statusClass =
                    'confirmed';

            }


            // ------------------------------------------------
            // SERVICES
            // ------------------------------------------------

            const services =
                job.services &&
                job.services.length > 0

                    ? job.services.join(', ')

                    : 'No service selected';


            // ------------------------------------------------
            // LOCATION
            // ------------------------------------------------

            let location =
                job.city || 'N/A';

            if (job.area) {

                location +=
                    ' · ' + job.area;

            }


            // ------------------------------------------------
            // BUILD HTML
            // ------------------------------------------------

            item.innerHTML = `

                <div class="job-time">

                    <strong>
                        ${job.scheduled_at}
                    </strong>

                    <span>
                        Today
                    </span>

                </div>


                <div class="job-icon blue">

                    <i
                        class="fa-solid fa-screwdriver-wrench"
                    ></i>

                </div>


                <div class="job-info">

                    <h4>
                        ${services}
                    </h4>

                    <p>
                        ${job.customer_name}
                    </p>

                    <small>

                        <i
                            class="fa-solid fa-location-dot"
                        ></i>

                        ${location}

                    </small>

                </div>


                <span
                    class="job-status ${statusClass}"
                >
                    ${formatProviderJobStatus(job.status)}
                </span>


                <a
                    href="${job.show_url}"
                    class="provider-action-btn"
                    title="View Booking"
                >

                    <i
                        class="fa-solid fa-eye"
                    ></i>

                </a>

            `;


            container.appendChild(item);

        });

    } catch (error) {

        console.error(
            'Provider today jobs polling error:',
            error
        );

    }

}


// ============================================================
// FORMAT JOB STATUS
// ============================================================

function formatProviderJobStatus(status) {

    return String(status || '')
        .replace(/_/g, ' ')
        .replace(/\b\w/g, function (letter) {
            return letter.toUpperCase();
        });

}


// ============================================================
// START TODAY'S JOBS POLLING
// ============================================================

document.addEventListener(
    'DOMContentLoaded',
    function () {

        loadTodayJobs();

        setInterval(
            loadTodayJobs,
            3000
        );

    }
);
// ============================================================
// PROVIDER BOOKING MESSAGE
// ============================================================

function showProviderBookingMessage(
    message,
    type = 'success'
) {

    let alert =
        document.getElementById(
            'providerBookingMessage'
        );

    // Create alert if it does not exist
    if (!alert) {

        alert =
            document.createElement('div');

        alert.id =
            'providerBookingMessage';

        alert.style.position =
            'fixed';

        alert.style.top =
            '90px';

        alert.style.right =
            '20px';

        alert.style.zIndex =
            '9999';

        document.body.appendChild(
            alert
        );
    }

    alert.className =
        `alert alert-${type} shadow`;

    alert.textContent =
        message;

    setTimeout(() => {

        if (alert) {
            alert.remove();
        }

    }, 3000);
}
// ============================================================
// PROVIDER PROFILE UPDATE AJAX
// ============================================================

document.addEventListener('submit', async function (event) {

    const form = event.target.closest(
        '.provider-profile-form'
    );

    if (!form) {
        return;
    }

    event.preventDefault();


    // ========================================================
    // SAVE BUTTON
    // ========================================================

    const submitButton =
        form.querySelector(
            'button[type="submit"]'
        );

    if (!submitButton) {
        return;
    }


    // ========================================================
    // SAVE ORIGINAL BUTTON
    // ========================================================

    const originalButtonHTML =
        submitButton.innerHTML;


    // ========================================================
    // DISABLE BUTTON
    // ========================================================

    submitButton.disabled = true;

    submitButton.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        Saving...
    `;


    // ========================================================
    // FORM DATA
    // ========================================================

    const formData =
        new FormData(form);


    try {

        // ====================================================
        // SEND AJAX REQUEST
        // ====================================================

        const response =
            await fetch(
                form.action,
                {
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute('content'),

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                    body: formData
                }
            );


        // ====================================================
        // READ RESPONSE
        // ====================================================

        const data =
            await response.json();


        // ====================================================
        // VALIDATION / SERVER ERROR
        // ====================================================

        if (
            !response.ok ||
            !data.success
        ) {

            /*
             * Laravel validation errors
             */

            if (
                data.errors &&
                typeof data.errors === 'object'
            ) {

                const firstError =
                    Object.values(
                        data.errors
                    )[0];

                if (
                    Array.isArray(firstError) &&
                    firstError.length > 0
                ) {

                    throw new Error(
                        firstError[0]
                    );

                }

            }


            throw new Error(
                data.message ||
                'Profile update failed.'
            );

        }


        // ====================================================
        // SUCCESS
        // ====================================================
showProviderProfileMessage(
    data.message || 'Profile updated successfully.',
    'success'
);

setTimeout(function () {
    window.location.href =
        data.redirect_url ||
        form.dataset.profileUrl;
}, 1000);

return;


        // ====================================================
        // UPDATE SIDEBAR NAME
        // ====================================================

        if (data.provider) {

            const sidebarName =
                document.querySelector(
                    '.provider-profile-info h4'
                );

            if (
                sidebarName &&
                data.provider.name
            ) {

                sidebarName.textContent =
                    data.provider.name;

            }


            // =================================================
            // UPDATE PROFILE HEADER NAME
            // =================================================

            const profileName =
                document.querySelector(
                    '.provider-profile-card-info h3'
                );

            if (
                profileName &&
                data.provider.name
            ) {

                profileName.textContent =
                    data.provider.name;

            }


            // =================================================
            // UPDATE PROFILE IMAGE
            // =================================================

            if (
                data.provider.profile_image
            ) {

                const imageUrl =
                    data.provider.profile_image;


                /*
                 * Sidebar profile image
                 */

                const sidebarAvatar =
                    document.querySelector(
                        '.provider-avatar'
                    );

                if (sidebarAvatar) {

                    sidebarAvatar.innerHTML = `
                        <img
                            src="${imageUrl}"
                            alt="${data.provider.name || 'Provider'}"
                        >
                    `;

                }


                /*
                 * Profile page large image
                 */

                const profileAvatar =
                    document.querySelector(
                        '.provider-profile-large-avatar'
                    );

                if (profileAvatar) {

                    profileAvatar.innerHTML = `
                        <img
                            src="${imageUrl}"
                            alt="${data.provider.name || 'Provider'}"
                        >
                    `;

                }

            }

        }


        // ====================================================
        // RE-ENABLE BUTTON
        // ====================================================

        submitButton.disabled = false;

        submitButton.innerHTML =
            originalButtonHTML;


    } catch (error) {

        console.error(
            'Provider profile update error:',
            error
        );


        // ====================================================
        // ERROR MESSAGE
        // ====================================================

        showProviderProfileMessage(
            error.message ||
            'Something went wrong while updating your profile.',
            'danger'
        );


        // ====================================================
        // RESTORE BUTTON
        // ====================================================

        submitButton.disabled = false;

        submitButton.innerHTML =
            originalButtonHTML;

    }

});


// ============================================================
// PROVIDER PROFILE MESSAGE
// ============================================================

function showProviderProfileMessage(
    message,
    type = 'success'
) {

    let alert =
        document.getElementById(
            'providerProfileMessage'
        );


    // ========================================================
    // CREATE ALERT
    // ========================================================

    if (!alert) {

        alert =
            document.createElement('div');

        alert.id =
            'providerProfileMessage';

        alert.style.position =
            'fixed';

        alert.style.top =
            '90px';

        alert.style.right =
            '20px';

        alert.style.zIndex =
            '9999';

        alert.style.minWidth =
            '280px';

        document.body.appendChild(
            alert
        );

    }


    // ========================================================
    // SET ALERT
    // ========================================================

    alert.className =
        `alert alert-${type} shadow`;

    alert.textContent =
        message;


    // ========================================================
    // AUTO REMOVE
    // ========================================================

    setTimeout(
        function () {

            if (alert) {
                alert.remove();
            }

        },
        3000
    );

}

// =====================================================
// CHANGE PASSWORD
// =====================================================

const changePasswordForm =
    document.getElementById(
        'changePasswordForm'
    );


if (changePasswordForm) {

    changePasswordForm.addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();


            const submitButton =
                document.getElementById(
                    'changePasswordSubmit'
                );


            const message =
                document.getElementById(
                    'changePasswordMessage'
                );


            /*
             * Get Laravel route from
             * the Blade data attribute.
             *
             * IMPORTANT:
             * Do NOT use {{ route(...) }} here.
             */

            const changePasswordUrl =
                changePasswordForm.dataset.url;


            if (!changePasswordUrl) {

                console.error(
                    'Change password URL is missing.'
                );

                return;
            }


            const formData =
                new FormData(
                    changePasswordForm
                );


            submitButton.disabled = true;

            submitButton.textContent =
                'Changing...';


            message.className =
                'alert d-none';

            message.textContent =
                '';


            try {

                const response =
                    await fetch(
                        changePasswordUrl,
                        {
                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute(
                                            'content'
                                        ),

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: formData
                        }
                    );


                const data =
                    await response.json();


                /*
                 * Handle Laravel validation
                 * or server errors.
                 */

                if (!response.ok) {

                    let errorMessage =
                        data.message ||
                        'Unable to change password.';


                    if (data.errors) {

                        errorMessage =
                            Object.values(
                                data.errors
                            )
                                .flat()
                                .join(' ');
                    }


                    throw new Error(
                        errorMessage
                    );
                }


                /*
                 * SUCCESS
                 */

                message.className =
                    'alert alert-success';

                message.textContent =
                    data.message ||
                    'Your password has been changed successfully.';


                /*
                 * Clear form.
                 */

                changePasswordForm.reset();


                /*
                 * Close modal after
                 * successful password change.
                 */

                setTimeout(
                    function () {

                        const modalElement =
                            document.getElementById(
                                'changePasswordModal'
                            );


                        const modal =
                            bootstrap.Modal.getInstance(
                                modalElement
                            );


                        if (modal) {
                            modal.hide();
                        }

                    },
                    1500
                );


            } catch (error) {

                console.error(
                    'Change password error:',
                    error
                );


                message.className =
                    'alert alert-danger';

                message.textContent =
                    error.message ||
                    'Unable to change password.';


            } finally {

                submitButton.disabled =
                    false;

                submitButton.textContent =
                    'Change Password';

            }

        }
    );

}