/* =========================================================
   QUICKFIX PROVIDER
   BOOKING DETAIL
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       STATUS HISTORY
    ===================================================== */

    const historyItems =
        document.querySelectorAll(
            ".provider-status-history-item"
        );

    historyItems.forEach(function (item) {

        item.addEventListener("click", function () {

            historyItems.forEach(function (historyItem) {

                historyItem.classList.remove("active");

            });

            item.classList.add("active");

        });

    });

});
