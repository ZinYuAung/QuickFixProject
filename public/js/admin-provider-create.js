document.addEventListener('DOMContentLoaded', function () {
/* =====================================================
   PASSWORD SHOW / HIDE
====================================================== */

const passwordToggles =
    document.querySelectorAll('.admin-password-toggle');

passwordToggles.forEach(function (button) {

    button.addEventListener('click', function () {

        const targetId = button.dataset.target;

        const input =
            document.getElementById(targetId);

        if (!input) {
            return;
        }

        const icon =
            button.querySelector('i');


        if (input.type === 'password') {

            input.type = 'text';

            if (icon) {

                icon.classList.remove('fa-eye');

                icon.classList.add('fa-eye-slash');

            }

            button.setAttribute(
                'title',
                'Hide password'
            );

            button.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            input.type = 'password';

            if (icon) {

                icon.classList.remove('fa-eye-slash');

                icon.classList.add('fa-eye');

            }

            button.setAttribute(
                'title',
                'Show password'
            );

            button.setAttribute(
                'aria-label',
                'Show password'
            );

        }

    });

});


/* =====================================================
   CITY → SERVICE AREA
====================================================== */

const citySelect =
    document.getElementById('city_id');

const areaSelect =
    document.getElementById('area_id');


if (citySelect && areaSelect) {

    const allAreas =
        Array.from(
            areaSelect.options
        ).slice(1);


    function filterAreas(resetArea = false) {

        const selectedCity =
            citySelect.value;


        allAreas.forEach(function (option) {

            const areaCity =
                option.dataset.city;


            if (
                !selectedCity ||
                areaCity === selectedCity
            ) {

                option.hidden = false;

            } else {

                option.hidden = true;

            }

        });


        if (resetArea) {

            areaSelect.value = '';

        }

    }


    /* Initial page load */

    filterAreas(false);


    /* City changed */

    citySelect.addEventListener(
        'change',
        function () {

            filterAreas(true);

        }
    );

}

});
