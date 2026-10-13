document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS
    ====================================================== */

    const filterButtons = document.querySelectorAll(
        ".booking-filter-btn"
    );

    const searchInput = document.getElementById(
        "bookingSearch"
    );

    const desktopRows = document.querySelectorAll(
        ".bookings-table .booking-row"
    );

    const mobileCards = document.querySelectorAll(
        ".mobile-bookings-list .booking-row"
    );

    const emptyMessage = document.getElementById(
        "bookingEmpty"
    );


    /* =====================================================
       STATUS LABELS
    ====================================================== */

    const statusLabels = {

        pending: "Pending",

        accepted: "Accepted",

        on_the_way: "On The Way",

        arrived: "Arrived",

        in_progress: "In Progress",

        completed: "Completed",

        cancelled: "Cancelled",

        timeout: "Timeout"

    };


    /* =====================================================
       TERMINAL STATUSES
    ====================================================== */

    const terminalStatuses = [

        "completed",

        "cancelled",

        "timeout"

    ];


    /* =====================================================
       GET FILTER STATUS
    ====================================================== */

    function getFilterStatus(status) {

        if (status === "pending") {

            return "pending";

        }

        if (
            [
                "accepted",
                "on_the_way",
                "arrived",
                "in_progress"
            ].includes(status)
        ) {

            return "active";

        }

        if (status === "completed") {

            return "completed";

        }

        if (
            [
                "cancelled",
                "timeout"
            ].includes(status)
        ) {

            return "cancelled";

        }

        return "all";

    }


    /* =====================================================
       GET STATUS CSS CLASS
    ====================================================== */

    function getStatusClass(status) {

        switch (status) {

            case "pending":

                return "pending";


            case "accepted":

            case "on_the_way":

            case "arrived":

            case "in_progress":

                return "accepted";


            case "completed":

                return "completed";


            case "cancelled":

            case "timeout":

                return "cancelled";


            default:

                return "pending";

        }

    }


    /* =====================================================
       GET STATUS LABEL
    ====================================================== */

    function getStatusLabel(status) {

        return statusLabels[status]
            || status
                .replaceAll("_", " ")
                .replace(/\b\w/g, function (letter) {
                    return letter.toUpperCase();
                });

    }


    /* =====================================================
       UPDATE STATUS BADGE
    ====================================================== */

    function updateStatusBadge(
        container,
        status
    ) {

        if (!container) {

            return;

        }

        const badge =
            container.querySelector(
                ".booking-status"
            );

        if (!badge) {

            return;

        }

        const statusClass =
            getStatusClass(status);

        const statusLabel =
            getStatusLabel(status);


        badge.className =
            "booking-status " +
            statusClass;


        badge.innerHTML =
            "<span></span>" +
            escapeHtml(statusLabel);

    }


    /* =====================================================
       ESCAPE HTML
    ====================================================== */

    function escapeHtml(value) {

        return String(value)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");

    }


    /* =====================================================
       UPDATE DESKTOP ROW
    ====================================================== */

    function updateDesktopRow(
        row,
        status
    ) {

        if (!row) {

            return;

        }


        /* ---------------------------------------------
           Update real status
        --------------------------------------------- */

        row.dataset.realStatus = status;


        /* ---------------------------------------------
           Update filter status
        --------------------------------------------- */

        row.dataset.status =
            getFilterStatus(status);


        /* ---------------------------------------------
           Update visible badge
        --------------------------------------------- */

        updateStatusBadge(
            row,
            status
        );


        /* ---------------------------------------------
           Update search text
        --------------------------------------------- */

        row.dataset.search =
            row.textContent
                .toLowerCase()
                .trim();

    }


    /* =====================================================
       UPDATE MOBILE CARD
    ====================================================== */

    function updateMobileCard(
        card,
        status
    ) {

        if (!card) {

            return;

        }


        /* ---------------------------------------------
           Update real status
        --------------------------------------------- */

        card.dataset.realStatus = status;


        /* ---------------------------------------------
           Update filter status
        --------------------------------------------- */

        card.dataset.status =
            getFilterStatus(status);


        /* ---------------------------------------------
           Update visible badge
        --------------------------------------------- */

        updateStatusBadge(
            card,
            status
        );


        /* ---------------------------------------------
           Update search text
        --------------------------------------------- */

        card.dataset.search =
            card.textContent
                .toLowerCase()
                .trim();

    }


    /* =====================================================
       FILTER BOOKINGS
    ====================================================== */

    function filterBookings() {

        const activeButton =
            document.querySelector(
                ".booking-filter-btn.active"
            );


        const filter =
            activeButton
                ? activeButton.dataset.filter
                : "all";


        const search =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : "";


        let desktopVisibleCount = 0;


        /* =================================================
           DESKTOP
        ================================================== */

        desktopRows.forEach(function (row) {

            const status =
                row.dataset.status || "";


            const text =
                row.textContent
                    .toLowerCase()
                    .trim();


            const matchesFilter =
                filter === "all" ||
                status === filter;


            const matchesSearch =
                search === "" ||
                text.includes(search);


            if (
                matchesFilter &&
                matchesSearch
            ) {

                row.style.display = "";

                desktopVisibleCount++;

            }
            else {

                row.style.display = "none";

            }

        });


        /* =================================================
           MOBILE
        ================================================== */

        mobileCards.forEach(function (card) {

            const status =
                card.dataset.status || "";


            const text =
                card.textContent
                    .toLowerCase()
                    .trim();


            const matchesFilter =
                filter === "all" ||
                status === filter;


            const matchesSearch =
                search === "" ||
                text.includes(search);


            if (
                matchesFilter &&
                matchesSearch
            ) {

                card.style.display = "";

            }
            else {

                card.style.display = "none";

            }

        });


        /* =================================================
           EMPTY MESSAGE
        ================================================== */

        if (emptyMessage) {

            if (
                desktopVisibleCount === 0 &&
                desktopRows.length > 0
            ) {

                emptyMessage.style.display =
                    "block";

            }
            else {

                emptyMessage.style.display =
                    "none";

            }

        }

    }


    /* =====================================================
       FIND DESKTOP ROW
    ====================================================== */

    function findDesktopRow(
        bookingId
    ) {

        return document.querySelector(
            '.bookings-table .booking-row[data-booking-id-number="' +
            bookingId +
            '"]'
        );

    }


    /* =====================================================
       FIND MOBILE CARD
    ====================================================== */

    function findMobileCard(
        bookingId
    ) {

        return document.querySelector(
            '.mobile-bookings-list .booking-row[data-booking-id-number="' +
            bookingId +
            '"]'
        );

    }


    /* =====================================================
       CHECK ONE BOOKING STATUS
    ====================================================== */

    async function checkBookingStatus(row) {

        if (!row) {

            return;

        }


        const bookingId =
            row.dataset.bookingIdNumber;


        const statusUrl =
            row.dataset.statusUrl;


        if (
            !bookingId ||
            !statusUrl
        ) {

            return;

        }


        /* ---------------------------------------------
           Don't keep polling completed/cancelled
           bookings
        --------------------------------------------- */

        const currentStatus =
            row.dataset.realStatus;


        if (
            terminalStatuses.includes(
                currentStatus
            )
        ) {

            return;

        }


        try {

            const response =
                await fetch(
                    statusUrl,
                    {
                        method: "GET",

                        headers: {
                            "Accept":
                                "application/json",

                            "X-Requested-With":
                                "XMLHttpRequest"
                        },

                        cache: "no-store"
                    }
                );


            if (!response.ok) {

                return;

            }


            const data =
                await response.json();


            if (
                !data.success ||
                !data.status
            ) {

                return;

            }


            const newStatus =
                data.status;


            /* -----------------------------------------
               Update desktop
            ----------------------------------------- */

            const desktopRow =
                findDesktopRow(
                    bookingId
                );


            if (desktopRow) {

                updateDesktopRow(
                    desktopRow,
                    newStatus
                );

            }


            /* -----------------------------------------
               Update mobile
            ----------------------------------------- */

            const mobileCard =
                findMobileCard(
                    bookingId
                );


            if (mobileCard) {

                updateMobileCard(
                    mobileCard,
                    newStatus
                );

            }


            /* -----------------------------------------
               Re-apply filter
            ----------------------------------------- */

            filterBookings();


        }
        catch (error) {

            console.error(
                "Booking status check failed:",
                error
            );

        }

    }


    /* =====================================================
       CHECK ALL BOOKING STATUSES
    ====================================================== */

    async function checkAllBookingStatuses() {

        if (
            desktopRows.length === 0
        ) {

            return;

        }


        /*
         * Check all bookings at the same time.
         */

        await Promise.all(

            Array.from(
                desktopRows
            ).map(function (row) {

                return checkBookingStatus(
                    row
                );

            })

        );

    }


    /* =====================================================
       FILTER BUTTONS
    ====================================================== */

    filterButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function () {

                filterButtons.forEach(
                    function (item) {

                        item.classList.remove(
                            "active"
                        );

                    }
                );


                this.classList.add(
                    "active"
                );


                filterBookings();

            }
        );

    });


    /* =====================================================
       SEARCH
    ====================================================== */

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            function () {

                filterBookings();

            }
        );

    }


    /* =====================================================
       INITIAL LOAD
    ====================================================== */

    filterBookings();


    /* =====================================================
       FIRST STATUS CHECK
    ====================================================== */

    checkAllBookingStatuses();


    /* =====================================================
       AUTO STATUS CHECK
       EVERY 1 SECOND
    ====================================================== */

    setInterval(
        checkAllBookingStatuses,
        1000
    );

});