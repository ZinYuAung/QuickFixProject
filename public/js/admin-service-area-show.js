/* =====================================================
   QUICKFIX ADMIN
   SERVICE AREA DETAILS JS
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const deactivateForm =
        document.getElementById(
            "deactivateServiceAreaForm"
        );

    const reactivateForm =
        document.getElementById(
            "reactivateServiceAreaForm"
        );


    /* =================================================
       DEACTIVATE CONFIRMATION
    ================================================= */

    if (deactivateForm) {

        deactivateForm.addEventListener(
            "submit",
            function (event) {

                const confirmed = confirm(
                    "Are you sure you want to deactivate this service area?"
                );

                if (!confirmed) {

                    event.preventDefault();

                    return;

                }

            }
        );

    }


    /* =================================================
       REACTIVATE CONFIRMATION
    ================================================= */

    if (reactivateForm) {

        reactivateForm.addEventListener(
            "submit",
            function (event) {

                const confirmed = confirm(
                    "Are you sure you want to reactivate this service area?"
                );

                if (!confirmed) {

                    event.preventDefault();

                    return;

                }

            }
        );

    }

});