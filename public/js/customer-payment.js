/* =========================================================
   QUICKFIX CUSTOMER PAYMENT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       PAYMENT SLIP MODAL
    ====================================================== */

    const slipButtons = document.querySelectorAll(
        ".payment-slip-button"
    );

    const paymentSlipModalElement =
        document.getElementById("paymentSlipModal");

    const paymentSlipImage =
        document.getElementById("paymentSlipImage");

    const paymentSlipPreview =
        document.getElementById("paymentSlipPreview");

    const paymentSlipLoading =
        document.getElementById("paymentSlipLoading");

    const paymentSlipError =
        document.getElementById("paymentSlipError");

    const paymentSlipBookingNumber =
        document.getElementById("paymentSlipBookingNumber");

    const paymentSlipOpenNew =
        document.getElementById("paymentSlipOpenNew");


    if (
        slipButtons.length &&
        paymentSlipModalElement
    ) {


        const paymentSlipModal =
            bootstrap.Modal.getOrCreateInstance(
                paymentSlipModalElement
            );


        slipButtons.forEach(function (button) {


            button.addEventListener(
                "click",
                function () {


                    const slipUrl =
                        this.getAttribute(
                            "data-slip-url"
                        );


                    const bookingId =
                        this.getAttribute(
                            "data-booking-id"
                        );


                    if (!slipUrl) {
                        return;
                    }


                    /* -----------------------------------------
                       Reset modal
                    ----------------------------------------- */

                    if (paymentSlipImage) {
                        paymentSlipImage.src = "";
                    }

                    if (paymentSlipPreview) {
                        paymentSlipPreview.style.display =
                            "none";
                    }

                    if (paymentSlipError) {
                        paymentSlipError.style.display =
                            "none";
                    }

                    if (paymentSlipLoading) {
                        paymentSlipLoading.style.display =
                            "flex";
                    }


                    if (paymentSlipBookingNumber) {

                        paymentSlipBookingNumber.textContent =
                            bookingId
                                ? "Booking #BK-" +
                                  String(bookingId).padStart(
                                      3,
                                      "0"
                                  )
                                : "Payment Slip";

                    }


                    if (paymentSlipOpenNew) {

                        paymentSlipOpenNew.href =
                            slipUrl;

                    }


                    /* -----------------------------------------
                       Open modal
                    ----------------------------------------- */

                    paymentSlipModal.show();


                    /* -----------------------------------------
                       Load image
                    ----------------------------------------- */

                    const image =
                        new Image();


                    image.onload =
                        function () {

                            if (paymentSlipLoading) {

                                paymentSlipLoading.style.display =
                                    "none";

                            }


                            if (paymentSlipImage) {

                                paymentSlipImage.src =
                                    slipUrl;

                            }


                            if (paymentSlipPreview) {

                                paymentSlipPreview.style.display =
                                    "block";

                            }

                        };


                    image.onerror =
                        function () {

                            if (paymentSlipLoading) {

                                paymentSlipLoading.style.display =
                                    "none";

                            }


                            if (paymentSlipError) {

                                paymentSlipError.style.display =
                                    "block";

                            }

                        };


                    image.src = slipUrl;

                }
            );

        });


        /* ---------------------------------------------
           Clear image after modal closes
        --------------------------------------------- */

        paymentSlipModalElement.addEventListener(
            "hidden.bs.modal",
            function () {

                if (paymentSlipImage) {

                    paymentSlipImage.src = "";

                }

                if (paymentSlipPreview) {

                    paymentSlipPreview.style.display =
                        "none";

                }

                if (paymentSlipError) {

                    paymentSlipError.style.display =
                        "none";

                }

            }
        );

    }


    /* =====================================================
       PREVENT BROKEN SLIP IMAGES
    ====================================================== */

    const slipImages =
        document.querySelectorAll(
            ".payment-slip-thumbnail"
        );


    slipImages.forEach(function (image) {

        image.addEventListener(
            "error",
            function () {

                this.style.display = "none";

            }
        );

    });


});