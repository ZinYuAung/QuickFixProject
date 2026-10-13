/* =========================================================
   QUICKFIX CUSTOMER REVIEW
   AUTOMATIC REVIEW POPUP
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const bookingDetailPage =
        document.querySelector(
            ".customer-booking-detail-content"
        );


    if (!bookingDetailPage) {
        return;
    }


    /* =====================================================
       ELEMENTS
    ====================================================== */

    const reviewModalElement =
        document.getElementById(
            "customerReviewModal"
        );


    const ratingInput =
        document.getElementById(
            "reviewRating"
        );


    const stars =
        document.querySelectorAll(
            ".review-star"
        );


    const ratingText =
        document.getElementById(
            "reviewRatingText"
        );


    const commentInput =
        document.getElementById(
            "reviewComment"
        );


    const characterCount =
        document.getElementById(
            "reviewCharacterCount"
        );


    const submitButton =
        document.getElementById(
            "submitCustomerReview"
        );


    const errorBox =
        document.getElementById(
            "reviewError"
        );


    const providerNameElement =
        document.getElementById(
            "reviewProviderName"
        );


    if (
        !reviewModalElement ||
        !ratingInput ||
        !submitButton
    ) {
        return;
    }


    /* =====================================================
       BOOTSTRAP MODAL
    ====================================================== */

    const reviewModal =
        bootstrap.Modal.getOrCreateInstance(
            reviewModalElement
        );


    /* =====================================================
       BOOKING INFORMATION
    ====================================================== */

    const reviewStoreUrl =
        bookingDetailPage.dataset.reviewStoreUrl;


    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute("content");


    if (!reviewStoreUrl) {

        console.warn(
            "QuickFix: Review store URL is missing."
        );

        return;
    }


    /* =====================================================
       STATE
    ====================================================== */

    let selectedRating = 0;

    let reviewPopupShown = false;

    let reviewSubmitting = false;


    /* =====================================================
       RATING LABELS
    ====================================================== */

    const ratingLabels = {

        1:
            "Very Poor",

        2:
            "Poor",

        3:
            "Average",

        4:
            "Good",

        5:
            "Excellent"

    };


    /* =====================================================
       UPDATE STAR UI
    ====================================================== */

    function updateStars(rating) {

        stars.forEach(function (star) {

            const starRating =
                Number(
                    star.dataset.rating
                );


            const icon =
                star.querySelector("i");


            if (!icon) {
                return;
            }


            if (starRating <= rating) {

                star.classList.add(
                    "active"
                );


                icon.classList.remove(
                    "fa-regular"
                );


                icon.classList.add(
                    "fa-solid"
                );

            } else {

                star.classList.remove(
                    "active"
                );


                icon.classList.remove(
                    "fa-solid"
                );


                icon.classList.add(
                    "fa-regular"
                );

            }

        });


        if (ratingText) {

            ratingText.textContent =
                ratingLabels[rating] ||
                "Select a rating";

        }

    }


    /* =====================================================
       STAR CLICK
    ====================================================== */

    stars.forEach(function (star) {

        star.addEventListener(
            "click",
            function () {

                selectedRating =
                    Number(
                        star.dataset.rating
                    );


                ratingInput.value =
                    selectedRating;


                updateStars(
                    selectedRating
                );


                hideError();

            }
        );

    });


    /* =====================================================
       COMMENT CHARACTER COUNT
    ====================================================== */

    if (commentInput && characterCount) {

        commentInput.addEventListener(
            "input",
            function () {

                characterCount.textContent =
                    commentInput.value.length;

            }
        );

    }


    /* =====================================================
       ERROR
    ====================================================== */

    function showError(message) {

        if (!errorBox) {
            return;
        }


        errorBox.textContent =
            message;


        errorBox.classList.remove(
            "d-none"
        );

    }


    function hideError() {

        if (!errorBox) {
            return;
        }


        errorBox.textContent =
            "";


        errorBox.classList.add(
            "d-none"
        );

    }


    /* =====================================================
       RESET FORM
    ====================================================== */

    function resetReviewForm() {

        selectedRating = 0;

        ratingInput.value = "";

        updateStars(0);

        hideError();


        if (commentInput) {

            commentInput.value = "";

        }


        if (characterCount) {

            characterCount.textContent =
                "0";

        }

    }


    /* =====================================================
       OPEN REVIEW MODAL
    ====================================================== */

    function openReviewModal(reviewData) {

        if (reviewPopupShown) {
            return;
        }


        if (!reviewData) {
            return;
        }


        if (reviewData.exists === true) {
            return;
        }


        if (reviewData.eligible !== true) {
            return;
        }


        /*
         * Prevent the popup from repeatedly opening
         * every second.
         */

        reviewPopupShown = true;


        /*
         * Provider name
         */

        if (providerNameElement) {

            providerNameElement.textContent =
                reviewData.provider_name ||
                "your provider";

        }


        resetReviewForm();


        reviewModal.show();

    }


    /* =====================================================
       REVIEW DATA CHECK
       Called by customer-booking-detail.js
    ====================================================== */

    window.quickFixCheckReview =
        function (reviewData) {

            openReviewModal(
                reviewData
            );

        };
            function checkInitialReviewStatus() {

        const statusUrl =
            bookingDetailPage.dataset.statusUrl;

        if (!statusUrl) {
            console.warn(
                "QuickFix: Booking status URL is missing."
            );
            return;
        }

        fetch(statusUrl, {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {

                if (!data || !data.success) {
                    return;
                }

                openReviewModal(
                    data.review || null
                );
            })
            .catch(function (error) {

                console.error(
                    "QuickFix initial review check failed:",
                    error
                );

            });
    }

    checkInitialReviewStatus();


    /* =====================================================
       SUBMIT REVIEW
    ====================================================== */

    submitButton.addEventListener(
        "click",
        function () {

            if (reviewSubmitting) {
                return;
            }


            hideError();


            const rating =
                Number(
                    ratingInput.value
                );


            if (
                !rating ||
                rating < 1 ||
                rating > 5
            ) {

                showError(
                    "Please select a rating from 1 to 5 stars."
                );

                return;

            }


            const comment =
                commentInput
                    ? commentInput.value.trim()
                    : "";


            reviewSubmitting = true;


            const originalButtonHtml =
                submitButton.innerHTML;


            submitButton.disabled =
                true;


            submitButton.innerHTML = `

                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                ></span>

                Submitting...

            `;


            fetch(
                reviewStoreUrl,
                {
                    method: "POST",

                    headers: {

                        "Content-Type":
                            "application/json",

                        "Accept":
                            "application/json",

                        "X-Requested-With":
                            "XMLHttpRequest",

                        "X-CSRF-TOKEN":
                            csrfToken || ""

                    },

                    body: JSON.stringify({

                        rating:
                            rating,

                        comment:
                            comment

                    })

                }
            )
                .then(function (response) {

                    return response.json()
                        .then(function (data) {

                            return {
                                ok:
                                    response.ok,

                                data:
                                    data
                            };

                        });

                })
                .then(function (result) {

                    if (
                        !result.ok ||
                        !result.data.success
                    ) {

                        throw new Error(
                            result.data.message ||
                            "Unable to submit your review."
                        );

                    }


                    /*
                     * Review successfully saved.
                     */

                    reviewPopupShown =
                        true;


                    reviewModal.hide();


                    /*
                     * Small success notification.
                     */

                    showReviewSuccess();

                })
                .catch(function (error) {

                    console.error(
                        "QuickFix review submission failed:",
                        error
                    );


                    showError(
                        error.message ||
                        "Unable to submit your review. Please try again."
                    );

                })
                .finally(function () {

                    reviewSubmitting =
                        false;


                    submitButton.disabled =
                        false;


                    submitButton.innerHTML =
                        originalButtonHtml;

                });

        }
    );


    /* =====================================================
       SUCCESS MESSAGE
    ====================================================== */

    function showReviewSuccess() {

        const existing =
            document.getElementById(
                "reviewSuccessMessage"
            );


        if (existing) {
            existing.remove();
        }


        const successMessage =
            document.createElement(
                "div"
            );


        successMessage.id =
            "reviewSuccessMessage";


        successMessage.className =
            "alert alert-success position-fixed shadow";


        successMessage.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 360px;
        `;


        successMessage.innerHTML = `

            <i
                class="fa-solid fa-circle-check me-2"
            ></i>

            <strong>
                Thank you!
            </strong>

            Your review has been submitted successfully.

        `;


        document.body.appendChild(
            successMessage
        );


        setTimeout(
            function () {

                successMessage.remove();

            },
            3000
        );

    }

});