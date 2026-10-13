/* =========================================================
   QUICKFIX PROVIDER REVIEWS
========================================================= */

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       DELETE REVIEW CONFIRMATION
    ===================================================== */

    const deleteForms =
        document.querySelectorAll('.review-delete-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = window.confirm(
                'Are you sure you want to hide this review from your review history?'
            );


            if (!confirmed) {

                event.preventDefault();

            }

        });

    });


    /* =====================================================
       AUTO HIDE SUCCESS ALERT
    ===================================================== */

    const successAlert =
        document.getElementById('reviewSuccessAlert');


    if (successAlert) {

        setTimeout(function () {

            successAlert.style.transition =
                'opacity 0.4s ease';

            successAlert.style.opacity = '0';


            setTimeout(function () {

                successAlert.remove();

            }, 400);

        }, 4000);

    }


});
