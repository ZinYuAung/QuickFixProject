/* =========================================================
   QUICKFIX ADMIN - CREATE SERVICE
   JavaScript
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS
    ====================================================== */

    const form = document.getElementById("createServiceForm");

    const nameInput = document.getElementById("name");

    const categoryInput =
        document.getElementById("service_category_id");

    const descriptionInput =
        document.getElementById("description");

    const priceInput =
        document.getElementById("base_price");

    const durationInput =
        document.getElementById("estimated_minutes");

    const activeInput =
        document.getElementById("is_active");

    const createButton =
        document.getElementById("createServiceButton");


    /* Preview elements */

    const previewName =
        document.getElementById("previewName");

    const previewCategory =
        document.getElementById("previewCategory");

    const previewDescription =
        document.getElementById("previewDescription");

    const previewPrice =
        document.getElementById("previewPrice");

    const previewDuration =
        document.getElementById("previewDuration");


    /* Description counter */

    const descriptionCounter =
        document.getElementById("descriptionCounter");


    /* =====================================================
       DESCRIPTION COUNTER
    ====================================================== */

    function updateDescriptionCounter() {

        if (!descriptionInput || !descriptionCounter) {
            return;
        }

        const currentLength =
            descriptionInput.value.length;

        const maxLength =
            descriptionInput.getAttribute("maxlength") || 1000;

        descriptionCounter.textContent =
            `${currentLength} / ${maxLength}`;

    }


    /* =====================================================
       FORMAT PRICE
    ====================================================== */

    function formatPrice(value) {

        if (!value || Number(value) < 0) {
            return "0 MMK";
        }

        const number =
            Number(value);

        return number.toLocaleString("en-US") + " MMK";
    }


    /* =====================================================
       UPDATE NAME PREVIEW
    ====================================================== */

    function updateNamePreview() {

        if (!previewName || !nameInput) {
            return;
        }

        const value =
            nameInput.value.trim();

        previewName.textContent =
            value || "New Service";
    }


    /* =====================================================
       UPDATE CATEGORY PREVIEW
    ====================================================== */

    function updateCategoryPreview() {

        if (!previewCategory || !categoryInput) {
            return;
        }

        const selectedOption =
            categoryInput.options[
                categoryInput.selectedIndex
            ];

        if (
            selectedOption &&
            selectedOption.value
        ) {

            previewCategory.textContent =
                selectedOption.textContent.trim();

        } else {

            previewCategory.textContent =
                "Service Category";
        }

    }


    /* =====================================================
       UPDATE DESCRIPTION PREVIEW
    ====================================================== */

    function updateDescriptionPreview() {

        if (!previewDescription || !descriptionInput) {
            return;
        }

        const value =
            descriptionInput.value.trim();

        previewDescription.textContent =
            value ||
            "Your service description will appear here.";
    }


    /* =====================================================
       UPDATE PRICE PREVIEW
    ====================================================== */

    function updatePricePreview() {

        if (!previewPrice || !priceInput) {
            return;
        }

        previewPrice.textContent =
            formatPrice(priceInput.value);
    }


    /* =====================================================
       UPDATE DURATION PREVIEW
    ====================================================== */

    function updateDurationPreview() {

        if (!previewDuration || !durationInput) {
            return;
        }

        const value =
            durationInput.value;

        previewDuration.textContent =
            value
                ? `${value} min`
                : "0 min";
    }


    /* =====================================================
       UPDATE STATUS
    ====================================================== */

    function updateStatusPreview() {

        if (!activeInput) {
            return;
        }

        const statusIcon =
            document.querySelector(
                ".service-status-icon"
            );

        const statusTitle =
            document.querySelector(
                ".service-status-information strong"
            );

        const statusDescription =
            document.querySelector(
                ".service-status-information span"
            );

        if (!statusTitle || !statusDescription) {
            return;
        }

        if (activeInput.checked) {

            statusTitle.textContent =
                "Active Service";

            statusDescription.textContent =
                "Customers can see and book this service.";

            if (statusIcon) {

                statusIcon.innerHTML =
                    '<i class="fa-solid fa-check"></i>';

            }

        } else {

            statusTitle.textContent =
                "Inactive Service";

            statusDescription.textContent =
                "Customers cannot book this service.";

            if (statusIcon) {

                statusIcon.innerHTML =
                    '<i class="fa-solid fa-pause"></i>';

            }

        }

    }


    /* =====================================================
       UPDATE ALL PREVIEW
    ====================================================== */

    function updatePreview() {

        updateNamePreview();

        updateCategoryPreview();

        updateDescriptionPreview();

        updatePricePreview();

        updateDurationPreview();

        updateDescriptionCounter();

        updateStatusPreview();

    }


    /* =====================================================
       EVENT LISTENERS
    ====================================================== */

    if (nameInput) {

        nameInput.addEventListener(
            "input",
            updateNamePreview
        );

    }


    if (categoryInput) {

        categoryInput.addEventListener(
            "change",
            updateCategoryPreview
        );

    }


    if (descriptionInput) {

        descriptionInput.addEventListener(
            "input",
            function () {

                updateDescriptionPreview();

                updateDescriptionCounter();

            }
        );

    }


    if (priceInput) {

        priceInput.addEventListener(
            "input",
            updatePricePreview
        );

    }


    if (durationInput) {

        durationInput.addEventListener(
            "input",
            updateDurationPreview
        );

    }


    if (activeInput) {

        activeInput.addEventListener(
            "change",
            updateStatusPreview
        );

    }


    /* =====================================================
       FORM SUBMIT
    ====================================================== */

    if (form && createButton) {

        form.addEventListener(
            "submit",
            function () {

                /*
                 * Prevent double clicking while Laravel
                 * processes the request.
                 */

                createButton.disabled = true;

                createButton.classList.add(
                    "is-loading"
                );

                createButton.innerHTML = `
                    <span class="button-spinner"></span>
                    <span>Creating...</span>
                `;

            }
        );

    }


    /* =====================================================
       INITIALIZE
    ====================================================== */

    updatePreview();

});

