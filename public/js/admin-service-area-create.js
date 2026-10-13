/* =====================================================
   QUICKFIX ADMIN
   CREATE SERVICE AREA JS
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
        document.getElementById("serviceAreaCreateForm");

    const submitButton =
        document.getElementById("createServiceAreaBtn");


    /* =================================================
       UPDATE CITY PREVIEW
    ================================================= */

    function updateCityPreview() {

        if (!citySelect || !previewCity) {
            return;
        }

        const selectedOption =
            citySelect.options[citySelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value !== ""
        ) {
            previewCity.textContent =
                selectedOption.text;
        } else {
            previewCity.textContent =
                "Select a city";
        }
    }


    /* =================================================
       UPDATE NAME PREVIEW
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
       UPDATE STATUS
    ================================================= */

    function updateStatusPreview() {

        if (!statusCheckbox) {
            return;
        }

        if (statusCheckbox.checked) {

            statusText.textContent =
                "Active";

            statusDescription.textContent =
                "This service area will be available for QuickFix services.";

            previewStatus.classList.remove(
                "inactive"
            );

            previewStatus.classList.add(
                "active"
            );

            previewStatus.innerHTML = `
                <span class="status-dot"></span>
                Active
            `;

        } else {

            statusText.textContent =
                "Inactive";

            statusDescription.textContent =
                "This service area will not be available for new services.";

            previewStatus.classList.remove(
                "active"
            );

            previewStatus.classList.add(
                "inactive"
            );

            previewStatus.innerHTML = `
                <span class="status-dot"></span>
                Inactive
            `;
        }
    }


    /* =================================================
       UPDATE SERVICE COUNT
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
       SUBMIT LOADING STATE
    ================================================= */

    if (form && submitButton) {

        form.addEventListener(
            "submit",
            function () {

                submitButton.disabled = true;

                submitButton.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Creating...</span>
                `;

            }
        );

    }


    /* =================================================
       INITIAL STATE
    ================================================= */

    updateCityPreview();
    updateNamePreview();
    updateStatusPreview();
    updateServiceCount();

});