/* =====================================================
QUICKFIX ADMIN
CUSTOMER SHOW PAGE
===================================================== */

document.addEventListener('DOMContentLoaded', function () {


/* =================================================
   COPY EMAIL / PHONE
================================================== */

const copyButtons = document.querySelectorAll(
    '.customer-show-copy-btn'
);

copyButtons.forEach(function (button) {

    button.addEventListener('click', async function () {

        const value = this.dataset.copy;

        if (!value) {
            return;
        }

        try {

            await navigator.clipboard.writeText(value);

            const icon = this.querySelector('i');

            if (icon) {

                const originalClass = icon.className;

                icon.className = 'fa-solid fa-check';

                setTimeout(function () {
                    icon.className = originalClass;
                }, 1500);

            }

        } catch (error) {

            console.error(
                'Unable to copy:',
                error
            );

        }

    });

});

});
