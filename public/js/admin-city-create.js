/* =========================================================
QUICKFIX ADMIN
CREATE CITY PAGE
========================================================= */

document.addEventListener('DOMContentLoaded', function () {


/* =====================================================
   ELEMENTS
===================================================== */

const form =
    document.getElementById('createCityForm');

const nameInput =
    document.getElementById('name');

const activeInput =
    document.getElementById('is_active');

const createButton =
    document.getElementById('createCityButton');

const previewName =
    document.getElementById('previewName');

const previewStatus =
    document.getElementById('previewStatus');

const statusIcon =
    document.querySelector('.city-status-icon');

const statusTitle =
    document.querySelector('.city-status-information strong');

const statusDescription =
    document.querySelector('.city-status-information span');


/* =====================================================
   NAME PREVIEW
===================================================== */

function updateNamePreview() {

    if (!nameInput || !previewName) {
        return;
    }

    const value =
        nameInput.value.trim();

    previewName.textContent =
        value || 'New City';
}


/* =====================================================
   STATUS PREVIEW
===================================================== */

function updateStatusPreview() {

    if (!activeInput) {
        return;
    }

    if (activeInput.checked) {

        if (statusTitle) {
            statusTitle.textContent = 'Active City';
        }

        if (statusDescription) {
            statusDescription.textContent =
                'Customers can use QuickFix services in this city.';
        }

        if (statusIcon) {
            statusIcon.innerHTML =
                '<i class="fa-solid fa-check"></i>';
        }

        if (previewStatus) {
            previewStatus.textContent = 'Active';
        }

    } else {

        if (statusTitle) {
            statusTitle.textContent = 'Inactive City';
        }

        if (statusDescription) {
            statusDescription.textContent =
                'Customers cannot use services in this city.';
        }

        if (statusIcon) {
            statusIcon.innerHTML =
                '<i class="fa-solid fa-pause"></i>';
        }

        if (previewStatus) {
            previewStatus.textContent = 'Inactive';
        }
    }
}


/* =====================================================
   INPUT EVENTS
===================================================== */

if (nameInput) {

    nameInput.addEventListener(
        'input',
        updateNamePreview
    );

}


if (activeInput) {

    activeInput.addEventListener(
        'change',
        updateStatusPreview
    );

}


/* =====================================================
   FORM SUBMIT
===================================================== */

if (form && createButton) {

    form.addEventListener('submit', function () {

        createButton.classList.add('is-loading');

        createButton.disabled = true;

        createButton.innerHTML = `
            <span class="city-button-spinner"></span>
            <span>Creating...</span>
        `;

    });

}


/* =====================================================
   INITIAL STATE
===================================================== */

updateNamePreview();

updateStatusPreview();


});
