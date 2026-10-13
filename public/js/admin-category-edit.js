/* =========================================================
   QUICKFIX ADMIN - EDIT CATEGORY
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS
       ===================================================== */

    const form = document.getElementById("editCategoryForm");

    const nameInput = document.getElementById("name");
    const descriptionInput = document.getElementById("description");
    const activeInput = document.getElementById("is_active");

    const saveButton = document.getElementById("saveCategoryButton");

    const previewName = document.getElementById("previewName");
    const previewDescription =
        document.getElementById("previewDescription");

    const previewStatus =
        document.getElementById("previewStatus");

    const previewStatusDot =
        document.getElementById("previewStatusDot");

    const statusTitle =
        document.getElementById("statusTitle");

    const statusDescription =
        document.getElementById("statusDescription");

    const statusIcon =
        document.querySelector(".category-status-icon");

    const currentStatusText =
        document.getElementById("currentStatusText");

    const descriptionCounter =
        document.getElementById("descriptionCounter");


    /* =====================================================
       NAME PREVIEW
       ===================================================== */

    function updateNamePreview() {

        if (!nameInput || !previewName) {
            return;
        }

        const name = nameInput.value.trim();

        previewName.textContent =
            name !== ""
                ? name
                : "Category Name";
    }


    /* =====================================================
       DESCRIPTION PREVIEW
       ===================================================== */

    function updateDescriptionPreview() {

        if (!descriptionInput || !previewDescription) {
            return;
        }

        const description =
            descriptionInput.value.trim();

        previewDescription.textContent =
            description !== ""
                ? description
                : "Your category description will appear here.";
    }


    /* =====================================================
       DESCRIPTION COUNTER
       ===================================================== */

    function updateDescriptionCounter() {

        if (!descriptionInput || !descriptionCounter) {
            return;
        }

        const currentLength =
            descriptionInput.value.length;

        descriptionCounter.textContent =
            `${currentLength} / 1000`;
    }


    /* =====================================================
       STATUS
       ===================================================== */

    function updateStatus() {

        if (!activeInput) {
            return;
        }

        const isActive = activeInput.checked;


        /* =================================================
           ACTIVE
        ================================================= */

        if (isActive) {

            if (statusTitle) {
                statusTitle.textContent =
                    "Active Category";
            }

            if (statusDescription) {
                statusDescription.textContent =
                    "Active categories are available when creating services.";
            }

            if (previewStatus) {
                previewStatus.textContent =
                    "Active";
            }

            if (previewStatusDot) {
                previewStatusDot.classList.add("active");
                previewStatusDot.classList.remove("inactive");
            }

            if (currentStatusText) {

                currentStatusText.textContent =
                    "Active";

                currentStatusText.classList.add(
                    "category-status-active"
                );

                currentStatusText.classList.remove(
                    "category-status-inactive"
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
                    "Inactive Category";
            }

            if (statusDescription) {
                statusDescription.textContent =
                    "Inactive categories are not available when creating services.";
            }

            if (previewStatus) {
                previewStatus.textContent =
                    "Inactive";
            }

            if (previewStatusDot) {
                previewStatusDot.classList.remove("active");
                previewStatusDot.classList.add("inactive");
            }

            if (currentStatusText) {

                currentStatusText.textContent =
                    "Inactive";

                currentStatusText.classList.remove(
                    "category-status-active"
                );

                currentStatusText.classList.add(
                    "category-status-inactive"
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
       DESCRIPTION INPUT
       ===================================================== */

    if (descriptionInput) {

        descriptionInput.addEventListener(
            "input",
            function () {

                updateDescriptionPreview();
                updateDescriptionCounter();

            }
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
                    <span class="category-button-spinner"></span>
                    <span>Saving...</span>
                `;
            }
        );
    }


    /* =====================================================
       INITIAL STATE
       ===================================================== */

    updateNamePreview();

    updateDescriptionPreview();

    updateDescriptionCounter();

    updateStatus();

});