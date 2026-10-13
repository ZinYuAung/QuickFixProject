/* =========================================================
   QUICKFIX ADMIN - EDIT SERVICE
   JavaScript
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS
    ====================================================== */

    const form =
        document.getElementById("editServiceForm");

    const nameInput =
        document.getElementById("name");

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

    const saveButton =
        document.getElementById("saveServiceButton");


    /* Preview */

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


    /* Counter */

    const descriptionCounter =
        document.getElementById("descriptionCounter");


    /* =====================================================
       FORMAT PRICE
    ====================================================== */

    function formatPrice(value) {

        if (
            value === "" ||
            value === null ||
            Number(value) < 0
        ) {

            return "0 MMK";
        }

        const number =
            Number(value);

        if (Number.isNaN(number)) {
            return "0 MMK";
        }

        return (
            number.toLocaleString("en-US")
            + " MMK"
        );
    }


    /* =====================================================
       UPDATE NAME
    ====================================================== */

    function updateNamePreview() {

        if (!nameInput || !previewName) {
            return;
        }

        const value =
            nameInput.value.trim();

        previewName.textContent =
            value || "Service Name";
    }


    /* =====================================================
       UPDATE CATEGORY
    ====================================================== */

    function updateCategoryPreview() {

        if (!categoryInput || !previewCategory) {
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
       UPDATE DESCRIPTION
    ====================================================== */

    function updateDescriptionPreview() {

        if (!descriptionInput || !previewDescription) {
            return;
        }

        const value =
            descriptionInput.value.trim();

        previewDescription.textContent =
            value ||
            "Your service description will appear here.";
    }


    /* =====================================================
       UPDATE DESCRIPTION COUNTER
    ====================================================== */

    function updateDescriptionCounter() {

        if (
            !descriptionInput ||
            !descriptionCounter
        ) {
            return;
        }

        const length =
            descriptionInput.value.length;

        const max =
            descriptionInput.getAttribute(
                "maxlength"
            ) || 1000;

        descriptionCounter.textContent =
            `${length} / ${max}`;

    }


    /* =====================================================
       UPDATE PRICE
    ====================================================== */

    function updatePricePreview() {

        if (!priceInput || !previewPrice) {
            return;
        }

        previewPrice.textContent =
            formatPrice(
                priceInput.value
            );
    }


    /* =====================================================
       UPDATE DURATION
    ====================================================== */

    function updateDurationPreview() {

        if (
            !durationInput ||
            !previewDuration
        ) {
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

    function updateStatus() {

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


        if (
            !statusTitle ||
            !statusDescription
        ) {
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
       UPDATE ALL
    ====================================================== */

    function updatePreview() {

        updateNamePreview();

        updateCategoryPreview();

        updateDescriptionPreview();

        updateDescriptionCounter();

        updatePricePreview();

        updateDurationPreview();

        updateStatus();

    }


    /* =====================================================
       INPUT EVENTS
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
            updateStatus
        );

    }


    /* =====================================================
       FORM SUBMIT
    ====================================================== */

    if (form && saveButton) {

        form.addEventListener(
            "submit",
            function () {

                saveButton.disabled = true;

                saveButton.classList.add(
                    "is-loading"
                );

                saveButton.innerHTML = `
                    <span class="button-spinner"></span>
                    <span>Saving...</span>
                `;

            }
        );

    }


    /* =====================================================
       INITIALIZE
    ====================================================== */

    updatePreview();

});
