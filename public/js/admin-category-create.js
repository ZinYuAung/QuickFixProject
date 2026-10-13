/* =========================================================
   QUICKFIX ADMIN - CREATE CATEGORY
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       DESCRIPTION CHARACTER COUNTER
    ====================================================== */

    const description =
        document.getElementById('description');

    const counter =
        document.getElementById('categoryDescriptionCounter');


    if (description && counter) {

        function updateDescriptionCounter() {

            const currentLength =
                description.value.length;

            counter.textContent =
                `${currentLength} / 1000`;

        }

        description.addEventListener(
            'input',
            updateDescriptionCounter
        );

        updateDescriptionCounter();

    }


    /* =====================================================
       FORM SUBMIT LOADING
    ====================================================== */

    const form =
        document.getElementById('createCategoryForm');

    const button =
        document.getElementById('createCategoryButton');


    if (form && button) {

        form.addEventListener('submit', function () {

            button.classList.add('is-loading');

            button.disabled = true;

            button.innerHTML = `
                <span class="category-button-spinner"></span>
                <span>Creating...</span>
            `;

        });

    }

});