/* =========================================================
   QUICKFIX ADMIN - CITY SHOW
   JavaScript
========================================================= */

document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       SUCCESS MESSAGE
    ====================================================== */

    const successAlert =
        document.querySelector(
            ".admin-city-alert"
        );


    if (successAlert) {

        setTimeout(function () {

            successAlert.style.transition =
                "opacity 0.4s ease, transform 0.4s ease";

            successAlert.style.opacity =
                "0";

            successAlert.style.transform =
                "translateY(-5px)";


            setTimeout(function () {

                successAlert.remove();

            }, 400);

        }, 4000);

    }


    /* =====================================================
       DEACTIVATE / REACTIVATE
    ====================================================== */

    const actionForms =
        document.querySelectorAll(
            ".admin-city-action-form"
        );


    actionForms.forEach(function (form) {

        form.addEventListener(
            "submit",
            function (event) {

                const message =
                    form.dataset.confirmMessage;


                if (!message) {
                    return;
                }


                const confirmed =
                    window.confirm(message);


                if (!confirmed) {

                    event.preventDefault();

                    return;

                }


                /* =================================================
                   PREVENT DOUBLE SUBMISSION
                ================================================== */

                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (button) {

                    button.disabled = true;

                    button.innerHTML =
                        '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

                }

            }
        );

    });

});