/* =====================================================
   QUICKFIX CUSTOMER SERVICES
   My Services Page JavaScript

   Shared customer functions:
   - Sidebar
   - Mobile menu
   - Overlay
   - Theme toggle

   These are handled by:
   public/js/customer.js

   This file handles ONLY:
   - Service filter
   - Pagination UI
   - View Details UI
===================================================== */

document.addEventListener("DOMContentLoaded", function () {


    /* =================================================
       SERVICE FILTER
       UI-only for now
    ================================================= */

    const serviceFilter =
        document.getElementById("serviceFilter");

    const serviceItems =
        document.querySelectorAll(".service-history-item");


    if (serviceFilter) {

        serviceFilter.addEventListener(
            "change",
            function () {

                const selectedStatus =
                    this.value;


                serviceItems.forEach(
                    function (item) {

                        const itemStatus =
                            item.dataset.status;


                        /* -------------------------
                           SHOW ALL
                        ------------------------- */

                        if (
                            selectedStatus === "all"
                        ) {

                            item.style.display = "";

                            return;
                        }


                        /* -------------------------
                           SHOW SELECTED STATUS
                        ------------------------- */

                        if (
                            itemStatus === selectedStatus
                        ) {

                            item.style.display = "";

                        } else {

                            item.style.display = "none";

                        }

                    }
                );

            }
        );

    }


    /* =================================================
       PAGINATION
       UI-only for now
    ================================================= */

    const paginationNumbers =
        document.querySelectorAll(
            ".pagination-number"
        );


    paginationNumbers.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {


                    /* -------------------------
                       Remove active state
                       from all page numbers
                    ------------------------- */

                    paginationNumbers.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );

                        }
                    );


                    /* -------------------------
                       Set clicked page active
                    ------------------------- */

                    this.classList.add("active");

                }
            );

        }
    );


    /* =================================================
       PAGINATION ARROW BUTTONS
       UI-only for now
    ================================================= */

    const paginationButtons =
        document.querySelectorAll(
            ".pagination-btn:not(.disabled)"
        );


    paginationButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    /*
                     * Backend pagination can be connected
                     * later.
                     *
                     * For now this is only a UI button.
                     */

                }
            );

        }
    );


    /* =================================================
       VIEW DETAILS
       Backend route can be connected later
    ================================================= */

    const detailButtons =
        document.querySelectorAll(
            ".service-details-btn"
        );


    detailButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function (event) {

                    /*
                     * UI-only for now.
                     *
                     * Prevent href="#" from
                     * jumping to the top.
                     */

                    if (
                        this.getAttribute("href") === "#"
                    ) {

                        event.preventDefault();

                    }

                }
            );

        }
    );


});