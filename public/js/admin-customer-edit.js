document.addEventListener('DOMContentLoaded', function () {
/* =====================================================
   CITY → SERVICE AREA
===================================================== */

const citySelect = document.getElementById('city_id');
const areaSelect = document.getElementById('area_id');

if (citySelect && areaSelect) {

    const areaOptions = Array.from(
        areaSelect.querySelectorAll('option[data-city-id]')
    );

    const selectedAreaId = areaSelect.dataset.selectedArea || '';


    function filterServiceAreas() {

        const selectedCityId = citySelect.value;

        areaSelect.value = '';

        areaOptions.forEach(function (option) {

            const optionCityId = option.dataset.cityId;

            if (
                selectedCityId &&
                optionCityId === selectedCityId
            ) {
                option.hidden = false;
            } else {
                option.hidden = true;
            }

        });


        /*
         * Restore the customer's current service area
         * only when it belongs to the selected city.
         */

        if (selectedCityId && selectedAreaId) {

            const matchingOption = areaOptions.find(
                function (option) {

                    return (
                        option.value === selectedAreaId &&
                        option.dataset.cityId === selectedCityId
                    );

                }
            );

            if (matchingOption) {
                areaSelect.value = selectedAreaId;
            }

        }
    }


    /*
     * Run once when page loads.
     * This is important for Edit Customer because
     * the existing city/service area must be displayed.
     */

    filterServiceAreas();


    /*
     * Filter areas when city changes.
     */

    citySelect.addEventListener(
        'change',
        function () {

            /*
             * When the admin changes city,
             * clear the old service area.
             */

            filterServiceAreas();

        }
    );

}


/* =====================================================
   PASSWORD SHOW / HIDE
===================================================== */

const passwordToggleButtons =
    document.querySelectorAll(
        '.customer-edit-password-toggle'
    );


passwordToggleButtons.forEach(function (button) {

    button.addEventListener('click', function () {

        const targetId =
            this.dataset.target;

        const input =
            document.getElementById(targetId);

        const icon =
            this.querySelector('i');


        if (!input) {
            return;
        }


        if (input.type === 'password') {

            input.type = 'text';

            if (icon) {
                icon.className =
                    'fa-regular fa-eye-slash';
            }

            this.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            input.type = 'password';

            if (icon) {
                icon.className =
                    'fa-regular fa-eye';
            }

            this.setAttribute(
                'aria-label',
                'Show password'
            );

        }

    });

});


/* =====================================================
   FORM SUBMIT PROTECTION
===================================================== */

const form =
    document.getElementById('customerEditForm');

if (form) {

    form.addEventListener('submit', function () {

        const submitButton =
            form.querySelector(
                'button[type="submit"]'
            );

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';

    });

}

});
