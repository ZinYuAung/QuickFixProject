document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENTS
    ====================================================== */

    const form =
        document.getElementById('providerEditForm');

    const citySelect =
        document.getElementById('city_id');

    const areaSelect =
        document.getElementById('area_id');

    const bio =
        document.getElementById('bio');

    const bioCharacterCount =
        document.getElementById('bioCharacterCount');

    const selectedServiceCount =
        document.getElementById('selectedServiceCount');

    const saveProviderBtn =
        document.getElementById('saveProviderBtn');


    /* =====================================================
       SERVICE COUNT
    ====================================================== */

    function updateServiceCount() {

        if (!selectedServiceCount) {
            return;
        }

        const checkedServices =
            document.querySelectorAll(
                '.admin-provider-service-checkbox input[type="checkbox"]:checked'
            );

        selectedServiceCount.textContent =
            checkedServices.length;
    }


    const serviceCheckboxes =
        document.querySelectorAll(
            '.admin-provider-service-checkbox input[type="checkbox"]'
        );


    serviceCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateServiceCount
        );

    });


    updateServiceCount();


    /* =====================================================
       CITY → SERVICE AREA FILTER
    ====================================================== */

    function filterServiceAreas() {

        if (!citySelect || !areaSelect) {
            return;
        }

        const selectedCity =
            citySelect.value;

        const options =
            areaSelect.querySelectorAll(
                'option[data-city]'
            );

        let selectedAreaIsValid = false;


        options.forEach(function (option) {

            const optionCity =
                option.getAttribute('data-city');


            if (
                !selectedCity ||
                optionCity === selectedCity
            ) {

                option.hidden = false;


                if (
                    option.selected &&
                    optionCity === selectedCity
                ) {

                    selectedAreaIsValid = true;

                }

            } else {

                option.hidden = true;


                if (option.selected) {

                    option.selected = false;

                }

            }

        });


        if (
            selectedCity &&
            !selectedAreaIsValid
        ) {

            areaSelect.value = '';

        }

    }


    if (citySelect) {

        citySelect.addEventListener(
            'change',
            filterServiceAreas
        );

        filterServiceAreas();

    }


    /* =====================================================
       PASSWORD SHOW / HIDE
    ====================================================== */

    const passwordToggles =
        document.querySelectorAll(
            '.admin-provider-password-toggle'
        );


    passwordToggles.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const targetId =
                    button.getAttribute('data-target');

                const input =
                    document.getElementById(targetId);


                if (!input) {
                    return;
                }


                const icon =
                    button.querySelector('i');


                if (
                    input.type === 'password'
                ) {

                    input.type = 'text';


                    if (icon) {

                        icon.classList.remove(
                            'fa-eye'
                        );

                        icon.classList.add(
                            'fa-eye-slash'
                        );

                    }


                    button.setAttribute(
                        'aria-label',
                        'Hide password'
                    );

                } else {

                    input.type = 'password';


                    if (icon) {

                        icon.classList.remove(
                            'fa-eye-slash'
                        );

                        icon.classList.add(
                            'fa-eye'
                        );

                    }


                    button.setAttribute(
                        'aria-label',
                        'Show password'
                    );

                }

            }
        );

    });


    /* =====================================================
       BIO CHARACTER COUNT
    ====================================================== */

    function updateBioCharacterCount() {

        if (!bio || !bioCharacterCount) {
            return;
        }

        bioCharacterCount.textContent =
            bio.value.length;

    }


    if (bio) {

        bio.addEventListener(
            'input',
            updateBioCharacterCount
        );

        updateBioCharacterCount();

    }


    /* =====================================================
       FORM CHANGE DETECTION
    ====================================================== */

    let formChanged = false;


    if (form) {

        const fields =
            form.querySelectorAll(
                'input, select, textarea'
            );


        fields.forEach(function (field) {

            field.addEventListener(
                'change',
                function () {

                    formChanged = true;

                }
            );


            field.addEventListener(
                'input',
                function () {

                    formChanged = true;

                }
            );

        });

    }


    /* =====================================================
       FORM SUBMIT PROTECTION
    ====================================================== */

    if (form) {

        form.addEventListener(
            'submit',
            function () {

                if (
                    form.classList.contains(
                        'is-saving'
                    )
                ) {

                    return;

                }


                form.classList.add(
                    'is-saving'
                );


                formChanged = false;


                if (saveProviderBtn) {

                    saveProviderBtn.disabled =
                        true;


                    const icon =
                        saveProviderBtn.querySelector(
                            'i'
                        );


                    const text =
                        saveProviderBtn.querySelector(
                            'span'
                        );


                    if (icon) {

                        icon.className =
                            'fa-solid fa-spinner fa-spin';

                    }


                    if (text) {

                        text.textContent =
                            'Saving Changes...';

                    }

                }

            }
        );

    }


    /* =====================================================
       UNSAVED CHANGE WARNING
    ====================================================== */

    window.addEventListener(
        'beforeunload',
        function (event) {

            if (
                formChanged &&
                form &&
                !form.classList.contains(
                    'is-saving'
                )
            ) {

                event.preventDefault();

                event.returnValue = '';

            }

        }
    );


    /* =====================================================
       CANCEL / NAVIGATION RESET
    ====================================================== */

    const navigationLinks =
        document.querySelectorAll(
            '.admin-provider-edit-page a'
        );


    navigationLinks.forEach(function (link) {

        link.addEventListener(
            'click',
            function () {

                formChanged = false;

            }
        );

    });

});