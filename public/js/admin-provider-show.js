document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       TABLE RESPONSIVENESS
    ===================================================== */

    const tables = document.querySelectorAll(
        '.admin-provider-bookings-table, ' +
        '.admin-provider-location-table'
    );

    tables.forEach(function (table) {

        const wrapper = table.closest('.admin-table-wrapper');

        if (!wrapper) {
            return;
        }

        wrapper.style.overflowX = 'auto';
        wrapper.style.overflowY = 'hidden';

        wrapper.style.webkitOverflowScrolling = 'touch';

    });


    /* =====================================================
       PREVENT DOUBLE SUBMISSION
    ===================================================== */

    const actionForms = document.querySelectorAll(
        '.admin-provider-quick-actions form'
    );

    actionForms.forEach(function (form) {

        form.addEventListener('submit', function () {

            const button = form.querySelector('button');

            if (!button) {
                return;
            }

            button.disabled = true;

        });

    });

});