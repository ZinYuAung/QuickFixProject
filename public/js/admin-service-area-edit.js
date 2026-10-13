/* =====================================================
   QUICKFIX ADMIN
   EDIT SERVICE AREA JS
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const citySelect = document.getElementById("city_id");
    const nameInput = document.getElementById("name");

    const previewCity = document.getElementById("previewCity");
    const previewAreaName = document.getElementById("previewAreaName");
    const previewStatus = document.getElementById("previewStatus");

    const statusCheckbox = document.getElementById("is_active");
    const statusText = document.getElementById("statusText");
    const statusDescription = document.getElementById("statusDescription");

    const serviceCheckboxes =
        document.querySelectorAll(".service-checkbox");

    const selectedServiceCount =
        document.getElementById("selectedServiceCount");

    const form =
        document.getElementById("serviceAreaEditForm");

    const submitButton =
        document.getElementById("updateServiceAreaBtn");


    /* =================================================
       CITY PREVIEW
    ================================================= */

    function updateCityPreview() {

        if (!citySelect || !previewCity) {
            return;
        }

        const selectedOption =
            citySelect.options[citySelect.selectedIndex];

        previewCity.textContent =
            selectedOption &&
            selectedOption.value !== ""
                ? selectedOption.text
                : "Select a city";
    }


    /* =================================================
       NAME PREVIEW
    ================================================= */

    function updateNamePreview() {

        if (!nameInput || !previewAreaName) {
            return;
        }

        const name =
            nameInput.value.trim();

        previewAreaName.textContent =
            name !== ""
                ? name
                : "Service Area Name";
    }


    /* =================================================
       STATUS
    ================================================= */

    function updateStatusPreview() {

        if (!statusCheckbox) {
            return;
        }

        if (statusCheckbox.checked) {

            statusText.textContent =
                "Active";

            statusDescription.textContent =
                "This service area is currently available.";

            previewStatus.className =
                "admin-service-area-status active";

            previewStatus.innerHTML = `
                <span class="status-dot"></span>
                Active
            `;

        } else {

            statusText.textContent =
                "Inactive";

            statusDescription.textContent =
                "This service area is currently unavailable.";

            previewStatus.className =
                "admin-service-area-status inactive";

            previewStatus.innerHTML = `
                <span class="status-dot"></span>
                Inactive
            `;
        }
    }


    /* =================================================
       SERVICE COUNT
    ================================================= */

    function updateServiceCount() {

        if (!selectedServiceCount) {
            return;
        }

        let count = 0;

        serviceCheckboxes.forEach(
            function (checkbox) {

                if (checkbox.checked) {
                    count++;
                }

            }
        );

        selectedServiceCount.textContent =
            count;
    }


    /* =================================================
       EVENTS
    ================================================= */

    if (citySelect) {

        citySelect.addEventListener(
            "change",
            updateCityPreview
        );

    }

    if (nameInput) {

        nameInput.addEventListener(
            "input",
            updateNamePreview
        );

    }

    if (statusCheckbox) {

        statusCheckbox.addEventListener(
            "change",
            updateStatusPreview
        );

    }

    serviceCheckboxes.forEach(
        function (checkbox) {

            checkbox.addEventListener(
                "change",
                updateServiceCount
            );

        }
    );


    /* =================================================
       SUBMIT
    ================================================= */

    if (form && submitButton) {

        form.addEventListener(
            "submit",
            function () {

                submitButton.disabled = true;

                submitButton.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Saving...</span>
                `;

            }
        );

    }


    /* =================================================
       INITIAL
    ================================================= */

    updateCityPreview();
    updateNamePreview();
    updateStatusPreview();
    updateServiceCount();

});