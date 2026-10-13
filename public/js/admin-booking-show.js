/* =========================================================
   QUICKFIX ADMIN
   BOOKING SHOW / DETAILS PAGE
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       PAGE ELEMENT
    ====================================================== */

    const page = document.querySelector(
        '.admin-booking-show-page'
    );

    if (!page) {
        return;
    }


    /* =====================================================
       TIMELINE ANIMATION
    ====================================================== */

    const timelineItems = document.querySelectorAll(
        '.admin-booking-timeline-item'
    );

    timelineItems.forEach(function (item, index) {

        item.style.opacity = '0';
        item.style.transform = 'translateY(10px)';

        setTimeout(function () {

            item.style.transition =
                'opacity 0.35s ease, transform 0.35s ease';

            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';

        }, 80 * index);

    });


    /* =====================================================
       DETAIL CARD HOVER
    ====================================================== */

    const detailCards = document.querySelectorAll(
        '.admin-booking-detail-card'
    );

    detailCards.forEach(function (card) {

        card.addEventListener('mouseenter', function () {

            card.style.transition =
                'box-shadow 0.2s ease, transform 0.2s ease';

            card.style.boxShadow =
                '0 8px 25px rgba(7, 27, 65, 0.06)';

        });


        card.addEventListener('mouseleave', function () {

            card.style.boxShadow =
                '0 5px 20px rgba(7, 27, 65, 0.04)';

        });

    });


    /* =====================================================
       SERVICE ITEM HOVER
    ====================================================== */

    const serviceItems = document.querySelectorAll(
        '.admin-booking-service-item'
    );

    serviceItems.forEach(function (item) {

        item.addEventListener('mouseenter', function () {

            item.style.transition =
                'background-color 0.2s ease';

            item.style.backgroundColor =
                'rgba(20, 108, 255, 0.025)';

        });


        item.addEventListener('mouseleave', function () {

            item.style.backgroundColor = '';

        });

    });


    /* =====================================================
       BACK BUTTON
    ====================================================== */

    const backButton = document.querySelector(
        '.admin-booking-back-btn'
    );

    if (backButton) {

        backButton.addEventListener('click', function (event) {

            /*
             * Normal Laravel route navigation is preferred.
             * This only adds a small visual effect.
             */

            backButton.style.transform =
                'translateY(0)';

        });

    }


    /* =====================================================
       COPY COORDINATES
    ====================================================== */

    const coordinateValues = document.querySelectorAll(
        '.admin-booking-coordinates strong'
    );

    coordinateValues.forEach(function (coordinate) {

        coordinate.style.cursor = 'pointer';

        coordinate.setAttribute(
            'title',
            'Click to copy'
        );


        coordinate.addEventListener('click', function () {

            const value =
                coordinate.textContent.trim();

            if (!value) {
                return;
            }


            if (
                navigator.clipboard &&
                navigator.clipboard.writeText
            ) {

                navigator.clipboard
                    .writeText(value)
                    .then(function () {

                        showCopyMessage();

                    })
                    .catch(function () {

                        fallbackCopy(value);

                    });

            } else {

                fallbackCopy(value);

            }

        });

    });


    /* =====================================================
       COPY MESSAGE
    ====================================================== */

    function showCopyMessage() {

        let message =
            document.querySelector(
                '.admin-booking-copy-message'
            );

        if (!message) {

            message =
                document.createElement('div');

            message.className =
                'admin-booking-copy-message';

            message.textContent =
                'Copied to clipboard';

            document.body.appendChild(message);


            const style =
                document.createElement('style');

            style.textContent = `
                .admin-booking-copy-message {
                    position: fixed;
                    right: 24px;
                    bottom: 24px;
                    z-index: 9999;
                    padding: 10px 15px;
                    border-radius: 8px;
                    background: #071b41;
                    color: #ffffff;
                    font-size: 12px;
                    font-weight: 600;
                    box-shadow: 0 8px 25px rgba(7, 27, 65, 0.18);
                    opacity: 0;
                    transform: translateY(10px);
                    transition:
                        opacity 0.2s ease,
                        transform 0.2s ease;
                }

                .admin-booking-copy-message.show {
                    opacity: 1;
                    transform: translateY(0);
                }
            `;

            document.head.appendChild(style);

        }


        message.classList.add('show');


        clearTimeout(
            message.hideTimer
        );


        message.hideTimer =
            setTimeout(function () {

                message.classList.remove('show');

            }, 1500);

    }


    /* =====================================================
       FALLBACK COPY
    ====================================================== */

    function fallbackCopy(value) {

        const textarea =
            document.createElement('textarea');

        textarea.value = value;

        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';

        document.body.appendChild(textarea);

        textarea.select();

        try {

            document.execCommand('copy');

            showCopyMessage();

        } catch (error) {

            console.warn(
                'Unable to copy value.',
                error
            );

        }

        document.body.removeChild(textarea);

    }


    /* =====================================================
       STATUS BADGE
    ====================================================== */

    const statusBadge = document.querySelector(
        '.admin-booking-summary-right .admin-booking-status'
    );

    if (statusBadge) {

        statusBadge.style.opacity = '0';

        statusBadge.style.transform =
            'scale(0.95)';


        setTimeout(function () {

            statusBadge.style.transition =
                'opacity 0.3s ease, transform 0.3s ease';

            statusBadge.style.opacity = '1';

            statusBadge.style.transform =
                'scale(1)';

        }, 150);

    }

});