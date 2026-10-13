/* =========================================================
   QUICKFIX ADMIN - EDIT CITY
========================================================= */

document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       ELEMENTS
    ===================================================== */

    const form =
        document.getElementById("editCityForm");

    const nameInput =
        document.getElementById("name");

    const activeInput =
        document.getElementById("is_active");

    const saveButton =
        document.getElementById("saveCityButton");


    const previewName =
        document.getElementById("previewName");

    const previewStatus =
        document.getElementById("previewStatus");

    const previewStatusDot =
        document.getElementById("previewStatusDot");


    const statusTitle =
        document.getElementById("statusTitle");

    const statusDescription =
        document.getElementById("statusDescription");

    const statusIcon =
        document.querySelector(".city-status-icon");


    const currentStatusText =
        document.getElementById("currentStatusText");


    /* =====================================================
       NAME PREVIEW
    ===================================================== */

    function updateNamePreview() {

        if (!nameInput || !previewName) {
            return;
        }

        const name =
            nameInput.value.trim();

        previewName.textContent =
            name !== ""
                ? name
                : "City Name";
    }


    /* =====================================================
       STATUS
    ===================================================== */

    function updateStatus() {

        if (!activeInput) {
            return;
        }

        const isActive =
            activeInput.checked;


        /* =================================================
           ACTIVE
        ================================================= */

        if (isActive) {

            if (statusTitle) {

                statusTitle.textContent =
                    "Active City";
            }


            if (statusDescription) {

                statusDescription.textContent =
                    "Customers can use QuickFix services in this city.";
            }


            if (previewStatus) {

                previewStatus.textContent =
                    "Active";
            }


            if (previewStatusDot) {

                previewStatusDot.classList.add(
                    "active"
                );

                previewStatusDot.classList.remove(
                    "inactive"
                );
            }


            if (currentStatusText) {

                currentStatusText.textContent =
                    "Active";

                currentStatusText.classList.add(
                    "city-status-active"
                );

                currentStatusText.classList.remove(
                    "city-status-inactive"
                );
            }


            if (statusIcon) {

                statusIcon.classList.remove(
                    "inactive"
                );

                statusIcon.innerHTML =
                    '<i class="fa-solid fa-check"></i>';
            }
        }


        /* =================================================
           INACTIVE
        ================================================= */

        else {

            if (statusTitle) {

                statusTitle.textContent =
                    "Inactive City";
            }


            if (statusDescription) {

                statusDescription.textContent =
                    "Customers cannot use QuickFix services in this city.";
            }


            if (previewStatus) {

                previewStatus.textContent =
                    "Inactive";
            }


            if (previewStatusDot) {

                previewStatusDot.classList.remove(
                    "active"
                );

                previewStatusDot.classList.add(
                    "inactive"
                );
            }


            if (currentStatusText) {

                currentStatusText.textContent =
                    "Inactive";

                currentStatusText.classList.remove(
                    "city-status-active"
                );

                currentStatusText.classList.add(
                    "city-status-inactive"
                );
            }


            if (statusIcon) {

                statusIcon.classList.add(
                    "inactive"
                );

                statusIcon.innerHTML =
                    '<i class="fa-solid fa-pause"></i>';
            }
        }
    }


    /* =====================================================
       NAME INPUT
    ===================================================== */

    if (nameInput) {

        nameInput.addEventListener(
            "input",
            updateNamePreview
        );
    }


    /* =====================================================
       STATUS INPUT
    ===================================================== */

    if (activeInput) {

        activeInput.addEventListener(
            "change",
            updateStatus
        );
    }


    /* =====================================================
       FORM SUBMIT
    ===================================================== */

    if (form && saveButton) {

        form.addEventListener(
            "submit",
            function () {

                saveButton.disabled = true;

                saveButton.innerHTML = `
                    <span class="city-edit-button-spinner"></span>
                    <span>Saving...</span>
                `;
            }
        );
    }


    /* =====================================================
       INITIAL STATE
    ===================================================== */

    updateNamePreview();

    updateStatus();

});