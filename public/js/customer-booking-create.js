document.addEventListener('DOMContentLoaded', function () {


    /*
    =====================================================
    BOOKING DATA
    =====================================================
    */

    const bookingData =
        window.quickFixBookingData || {};


    const categories =
        bookingData.services || [];


    const serviceAreas =
        bookingData.serviceAreas || [];


    const providers =
        bookingData.providers || [];


    const preselectedServiceId =
        bookingData.selectedServiceId;


    const oldCityId =
        bookingData.oldCityId;


    const oldAreaId =
        bookingData.oldAreaId;


    const oldProviderId =
        bookingData.oldProviderId;


    /*
    =====================================================
    BOOKING TYPE
    =====================================================
    */

    const bookingType =
        document.getElementById('booking_type');


    const scheduledAt =
        document.getElementById('scheduled_at');


    if (bookingType && scheduledAt) {


        function getCurrentDateTime() {

            const now = new Date();

            const year =
                now.getFullYear();

            const month =
                String(now.getMonth() + 1)
                    .padStart(2, '0');

            const day =
                String(now.getDate())
                    .padStart(2, '0');

            const hours =
                String(now.getHours())
                    .padStart(2, '0');

            const minutes =
                String(now.getMinutes())
                    .padStart(2, '0');


            return (
                year +
                '-' +
                month +
                '-' +
                day +
                'T' +
                hours +
                ':' +
                minutes
            );
        }


        function updateScheduledDateTime() {

            if (bookingType.value === 'asap') {

                scheduledAt.value = '';

                scheduledAt.disabled = true;

                scheduledAt.removeAttribute(
                    'required'
                );

                scheduledAt.removeAttribute(
                    'min'
                );

            } else {

                scheduledAt.disabled = false;

                scheduledAt.setAttribute(
                    'required',
                    'required'
                );

                scheduledAt.min =
                    getCurrentDateTime();

            }

        }


        bookingType.addEventListener(
            'change',
            updateScheduledDateTime
        );


        scheduledAt.addEventListener(
            'change',
            function () {

                if (
                    bookingType.value === 'scheduled' &&
                    this.value &&
                    this.value < getCurrentDateTime()
                ) {

                    alert(
                        'Please select a future date and time.'
                    );

                    this.value =
                        getCurrentDateTime();

                }

            }
        );


        updateScheduledDateTime();

    }


    /*
    =====================================================
    SERVICE ELEMENTS
    =====================================================
    */

    const categorySelect =
        document.getElementById(
            'category_select'
        );


    const servicesContainer =
        document.getElementById(
            'bookingServicesContainer'
        );


    const selectedServicesList =
        document.getElementById(
            'selectedServicesList'
        );


    const selectedServicesCount =
        document.getElementById(
            'selectedServicesCount'
        );


    const bookingForm =
        document.getElementById(
            'bookingForm'
        );


    const continueButton =
        document.getElementById(
            'continueBookingButton'
        );


    /*
    =====================================================
    CITY / AREA ELEMENTS
    =====================================================
    */

    const citySelect =
        document.getElementById(
            'city_id'
        );


    const areaSelect =
        document.getElementById(
            'area_id'
        );


    /*
    =====================================================
    PROVIDER ELEMENTS
    =====================================================
    */

    const providerContainer =
        document.getElementById(
            'bookingProviderContainer'
        );


    const providerMessage =
        document.getElementById(
            'bookingProviderMessage'
        );


    const providerNoResults =
        document.getElementById(
            'bookingProviderNoResults'
        );


    /*
    =====================================================
    SELECTED SERVICES
    =====================================================
    */

    let selectedServices = {};


    /*
    =====================================================
    FIND SERVICE
    =====================================================
    */

    function findService(serviceId) {

        for (const category of categories) {

            const services =
                category.services || [];


            for (const service of services) {

                if (
                    Number(service.id) ===
                    Number(serviceId)
                ) {

                    return service;

                }

            }

        }

        return null;
    }


    /*
    =====================================================
    GET SELECTED SERVICE IDS
    =====================================================
    */

    function getSelectedServiceIds() {

        return Object.values(
            selectedServices
        ).map(function (service) {

            return String(service.id);

        });

    }


    /*
    =====================================================
    PRESELECT SERVICE
    =====================================================
    */

    if (preselectedServiceId) {

        const service =
            findService(
                preselectedServiceId
            );


        if (service) {

            selectedServices[
                service.id
            ] = service;


            if (categorySelect) {

                categorySelect.value =
                    service.category_id;

            }


            renderServices(
                service.category_id
            );

        }

    }


    /*
    =====================================================
    CATEGORY CHANGE
    =====================================================
    */

    if (categorySelect) {

        categorySelect.addEventListener(
            'change',
            function () {

                const categoryId =
                    this.value;


                if (!categoryId) {

                    showEmptyMessage();

                    return;

                }


                renderServices(
                    categoryId
                );

            }
        );

    }


    /*
    =====================================================
    RENDER SERVICES
    =====================================================
    */

    function renderServices(categoryId) {

        const category =
            categories.find(
                function (item) {

                    return Number(item.id) ===
                        Number(categoryId);

                }
            );


        if (
            !category ||
            !category.services ||
            category.services.length === 0
        ) {

            servicesContainer.innerHTML = `

                <div class="booking-services-empty">

                    <i class="fa-solid fa-circle-info"></i>

                    <p>
                        No services available in this category.
                    </p>

                </div>

            `;

            return;

        }


        let html = `

            <div class="booking-services-category-header">

                <i class="fa-solid fa-layer-group"></i>

                ${escapeHtml(category.name)}

            </div>

        `;


        category.services.forEach(
            function (service) {

                const checked =
                    selectedServices[service.id]
                        ? 'checked'
                        : '';


                html += `

                    <label
                        class="booking-service-checkbox"
                    >

                        <input
                            type="checkbox"
                            value="${service.id}"
                            data-service-id="${service.id}"
                            ${checked}
                        >

                        <span
                            class="booking-service-checkmark"
                        >

                            <i class="fa-solid fa-check"></i>

                        </span>

                        <span
                            class="booking-service-name"
                        >

                            ${escapeHtml(service.name)}

                        </span>

                    </label>

                `;

            }
        );


        servicesContainer.innerHTML =
            html;


        /*
        -------------------------------------------------
        Add checkbox listeners
        -------------------------------------------------
        */

        const checkboxes =
            servicesContainer.querySelectorAll(
                'input[type="checkbox"]'
            );


        checkboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {

                        const serviceId =
                            this.dataset.serviceId;


                        const service =
                            findService(
                                serviceId
                            );


                        if (!service) {

                            return;

                        }


                        if (this.checked) {

                            selectedServices[
                                service.id
                            ] = service;

                        } else {

                            delete selectedServices[
                                service.id
                            ];

                        }


                        updateSelectedServices();

                    }
                );

            }
        );


        updateSelectedServices();

    }


    /*
    =====================================================
    EMPTY CATEGORY MESSAGE
    =====================================================
    */

    function showEmptyMessage() {

        servicesContainer.innerHTML = `

            <div class="booking-services-empty">

                <i class="fa-solid fa-list-check"></i>

                <p>
                    Select a category to view
                    available services.
                </p>

            </div>

        `;

    }


    /*
    =====================================================
    UPDATE SELECTED SERVICES
    =====================================================
    */

    function updateSelectedServices() {

        const services =
            Object.values(
                selectedServices
            );


        /*
        -------------------------------------------------
        Count
        -------------------------------------------------
        */

        selectedServicesCount.textContent =
            services.length;


        /*
        -------------------------------------------------
        Continue button
        -------------------------------------------------
        */

        if (continueButton) {

            continueButton.disabled =
                services.length === 0;

        }


        /*
        -------------------------------------------------
        No services
        -------------------------------------------------
        */

        if (services.length === 0) {

            selectedServicesList.innerHTML = `

                <span class="selected-services-empty">

                    No services selected yet.

                </span>

            `;


            updateHiddenInputs();


            /*
            Provider list must disappear
            because no service is selected.
            */

            filterProviders();

            return;

        }


        /*
        -------------------------------------------------
        Selected service tags
        -------------------------------------------------
        */

        selectedServicesList.innerHTML =
            '';


        services.forEach(
            function (service) {

                const tag =
                    document.createElement(
                        'div'
                    );


                tag.className =
                    'selected-service-tag';


                tag.innerHTML = `

                    <span>

                        ${escapeHtml(
                            service.name
                        )}

                    </span>

                    <button
                        type="button"
                        class="selected-service-remove"
                        data-service-id="${service.id}"
                        title="Remove service"
                    >

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                `;


                selectedServicesList.appendChild(
                    tag
                );

            }
        );


        /*
        -------------------------------------------------
        Remove buttons
        -------------------------------------------------
        */

        selectedServicesList
            .querySelectorAll(
                '.selected-service-remove'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const serviceId =
                                this.dataset.serviceId;


                            delete selectedServices[
                                serviceId
                            ];


                            /*
                            Uncheck visible checkbox
                            */

                            const visibleCheckbox =
                                servicesContainer.querySelector(
                                    `input[data-service-id="${serviceId}"]`
                                );


                            if (visibleCheckbox) {

                                visibleCheckbox.checked =
                                    false;

                            }


                            updateSelectedServices();

                        }
                    );

                }
            );


        updateHiddenInputs();


        /*
        -------------------------------------------------
        IMPORTANT:
        Re-filter providers whenever
        selected services change.
        -------------------------------------------------
        */

        filterProviders();

    }


    /*
    =====================================================
    CREATE HIDDEN SERVICE INPUTS
    =====================================================
    */

    function updateHiddenInputs() {

        if (!bookingForm) {

            return;

        }


        /*
        Remove old inputs
        */

        bookingForm
            .querySelectorAll(
                '.selected-service-hidden'
            )
            .forEach(
                function (input) {

                    input.remove();

                }
            );


        /*
        Create one input for
        every selected service
        */

        Object.values(
            selectedServices
        ).forEach(
            function (service) {

                const input =
                    document.createElement(
                        'input'
                    );


                input.type =
                    'hidden';


                input.name =
                    'service_ids[]';


                input.value =
                    service.id;


                input.className =
                    'selected-service-hidden';


                bookingForm.appendChild(
                    input
                );

            }
        );

    }


    /*
    =====================================================
    CITY → SERVICE AREA
    =====================================================
    */

    function updateServiceAreas() {

        if (!citySelect || !areaSelect) {

            return;

        }


        const cityId =
            citySelect.value;


        /*
        Clear existing areas
        */

        areaSelect.innerHTML =
            '';


        /*
        No city
        */

        if (!cityId) {

            areaSelect.disabled =
                true;


            areaSelect.innerHTML = `

                <option value="">
                    Select a city first
                </option>

            `;


            filterProviders();

            return;

        }


        /*
        Find areas belonging
        to selected city
        */

        const filteredAreas =
            serviceAreas.filter(
                function (area) {

                    return String(area.city_id) ===
                        String(cityId);

                }
            );


        /*
        No areas
        */

        if (filteredAreas.length === 0) {

            areaSelect.disabled =
                true;


            areaSelect.innerHTML = `

                <option value="">
                    No service areas available
                </option>

            `;


            filterProviders();

            return;

        }


        /*
        Enable area select
        */

        areaSelect.disabled =
            false;


        areaSelect.innerHTML = `

            <option value="">
                Select a service area
            </option>

        `;


        /*
        Add matching areas
        */

        filteredAreas.forEach(
            function (area) {

                const option =
                    document.createElement(
                        'option'
                    );


                option.value =
                    area.id;


                option.textContent =
                    area.name;


                /*
                Restore old value
                after validation error
                */

                if (
                    oldAreaId &&
                    String(oldAreaId) ===
                    String(area.id)
                ) {

                    option.selected =
                        true;

                }


                areaSelect.appendChild(
                    option
                );

            }
        );


        filterProviders();

    }


    /*
    =====================================================
    CITY CHANGE
    =====================================================
    */

    if (citySelect) {

        citySelect.addEventListener(
            'change',
            function () {

                /*
                When city changes,
                old area is no longer reliable.
                */

                if (
                    !oldCityId ||
                    String(this.value) !==
                    String(oldCityId)
                ) {

                    if (areaSelect) {

                        areaSelect.value =
                            '';

                    }

                }


                updateServiceAreas();

            }
        );

    }


    /*
    =====================================================
    AREA CHANGE
    =====================================================
    */

    if (areaSelect) {

        areaSelect.addEventListener(
            'change',
            function () {

                filterProviders();

            }
        );

    }


    /*
    =====================================================
    PROVIDER FILTER
    =====================================================
    */

    function filterProviders() {

        if (
            !providerContainer ||
            !citySelect ||
            !areaSelect
        ) {

            return;

        }


        const selectedServiceIds =
            getSelectedServiceIds();


        const selectedCityId =
            citySelect.value;


        const selectedAreaId =
            areaSelect.value;


        /*
        -------------------------------------------------
        Reset provider display
        -------------------------------------------------
        */

        const providerOptions =
            providerContainer.querySelectorAll(
                '.booking-provider-option'
            );


        /*
        -------------------------------------------------
        Missing selection
        -------------------------------------------------
        */

        if (
            selectedServiceIds.length === 0 ||
            !selectedCityId ||
            !selectedAreaId
        ) {

            providerOptions.forEach(
                function (providerOption) {

                    providerOption.style.display =
                        'none';

                }
            );


            if (providerMessage) {

                providerMessage.classList.add(
                    'show'
                );

            }


            if (providerNoResults) {

                providerNoResults.classList.remove(
                    'show'
                );

            }


            clearHiddenProviderSelection();

            return;

        }


        /*
        -------------------------------------------------
        We have all required selections
        -------------------------------------------------
        */

        if (providerMessage) {

            providerMessage.classList.remove(
                'show'
            );

        }


        let visibleProviderCount =
            0;


        providerOptions.forEach(
            function (providerOption) {

                const providerCityId =
                    providerOption.dataset.cityId;


                const providerAreaId =
                    providerOption.dataset.areaId;


                const providerServiceIds =
                    providerOption.dataset.serviceIds
                        ? providerOption.dataset.serviceIds
                            .split(',')
                            .map(function (id) {
                                return String(id).trim();
                            })
                            .filter(Boolean)
                        : [];


                /*
                -------------------------------------------------
                City match
                -------------------------------------------------
                */

                const cityMatches =
                    String(providerCityId) ===
                    String(selectedCityId);


                /*
                -------------------------------------------------
                Area match
                -------------------------------------------------
                */

                const areaMatches =
                    String(providerAreaId) ===
                    String(selectedAreaId);


                /*
                -------------------------------------------------
                SERVICE MATCH
                -------------------------------------------------

                Provider must offer ALL
                selected services.

                Example:

                Customer:
                Fan Repair
                Pipe Repair

                Provider:
                Fan Repair
                Pipe Repair
                AC Repair

                Result:
                MATCH

                -------------------------------------------------
                */

                const servicesMatch =
                    selectedServiceIds.every(
                        function (serviceId) {

                            return providerServiceIds.includes(
                                String(serviceId)
                            );

                        }
                    );


                const matches =
                    cityMatches &&
                    areaMatches &&
                    servicesMatch;


                if (matches) {

                    providerOption.style.display =
                        'flex';

                    visibleProviderCount++;

                } else {

                    providerOption.style.display =
                        'none';


                    /*
                    If currently selected provider
                    no longer matches, uncheck it.
                    */

                    const radio =
                        providerOption.querySelector(
                            '.booking-provider-radio'
                        );


                    if (
                        radio &&
                        radio.checked
                    ) {

                        radio.checked =
                            false;

                    }

                }

            }
        );


        /*
        -------------------------------------------------
        Show no results message
        -------------------------------------------------
        */

        if (visibleProviderCount === 0) {

            if (providerNoResults) {

                providerNoResults.classList.add(
                    'show'
                );

            }


            clearHiddenProviderSelection();

        } else {

            if (providerNoResults) {

                providerNoResults.classList.remove(
                    'show'
                );

            }


            /*
            Restore old provider only
            if it is actually visible.
            */

            restoreProviderIfValid();

        }

    }


    /*
    =====================================================
    CLEAR PROVIDER SELECTION
    =====================================================
    */

    function clearHiddenProviderSelection() {

        if (!providerContainer) {

            return;

        }


        const checkedRadio =
            providerContainer.querySelector(
                '.booking-provider-radio:checked'
            );


        if (checkedRadio) {

            checkedRadio.checked =
                false;

        }

    }


    /*
    =====================================================
    RESTORE OLD PROVIDER
    =====================================================
    */

    function restoreProviderIfValid() {

        if (!oldProviderId) {

            return;

        }


        const oldRadio =
            providerContainer.querySelector(
                `.booking-provider-radio[value="${oldProviderId}"]`
            );


        if (!oldRadio) {

            return;

        }


        const providerOption =
            oldRadio.closest(
                '.booking-provider-option'
            );


        if (
            providerOption &&
            providerOption.style.display !== 'none'
        ) {

            oldRadio.checked =
                true;

        }

    }


    /*
    =====================================================
    PROVIDER RADIO CHANGE
    =====================================================
    */

    if (providerContainer) {

        providerContainer.addEventListener(
            'change',
            function (event) {

                if (
                    event.target.classList.contains(
                        'booking-provider-radio'
                    )
                ) {

                    /*
                    Browser handles radio selection.
                    CSS handles selected appearance.
                    */

                }

            }
        );

    }


    /*
    =====================================================
    FORM SUBMIT
    =====================================================
    */

    if (bookingForm) {

        bookingForm.addEventListener(
            'submit',
            function (event) {

                const serviceCount =
                    Object.keys(
                        selectedServices
                    ).length;


                /*
                -------------------------------------------------
                No service
                -------------------------------------------------
                */

                if (serviceCount === 0) {

                    event.preventDefault();


                    alert(
                        'Please select at least one service.'
                    );


                    if (categorySelect) {

                        categorySelect.focus();

                    }


                    return;

                }


                /*
                -------------------------------------------------
                City
                -------------------------------------------------
                */

                if (
                    citySelect &&
                    !citySelect.value
                ) {

                    event.preventDefault();


                    alert(
                        'Please select a city.'
                    );


                    citySelect.focus();

                    return;

                }


                /*
                -------------------------------------------------
                Area
                -------------------------------------------------
                */

                if (
                    areaSelect &&
                    !areaSelect.value
                ) {

                    event.preventDefault();


                    alert(
                        'Please select a service area.'
                    );


                    areaSelect.focus();

                    return;

                }


                /*
                -------------------------------------------------
                Provider
                -------------------------------------------------
                */

                const selectedProvider =
                    providerContainer
                        ? providerContainer.querySelector(
                            '.booking-provider-radio:checked'
                        )
                        : null;


                if (!selectedProvider) {

                    event.preventDefault();


                    alert(
                        'Please select an available provider.'
                    );


                    return;

                }


                /*
                -------------------------------------------------
                Hidden service inputs
                -------------------------------------------------
                */

                updateHiddenInputs();

            }
        );

    }


    /*
    =====================================================
    PROBLEM PHOTO
    =====================================================
    */

    const problemPhoto =
        document.getElementById(
            'problem_photo'
        );


    const photoPreview =
        document.getElementById(
            'bookingPhotoPreview'
        );


    const previewImage =
        document.getElementById(
            'bookingPhotoPreviewImage'
        );


    const photoName =
        document.getElementById(
            'bookingPhotoName'
        );


    const photoSize =
        document.getElementById(
            'bookingPhotoSize'
        );


    if (
        problemPhoto &&
        photoPreview &&
        previewImage
    ) {

        problemPhoto.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];


                if (!file) {

                    photoPreview.classList.remove(
                        'show'
                    );


                    previewImage.src =
                        '';


                    return;

                }


                /*
                -------------------------------------------------
                Allowed image types
                -------------------------------------------------
                */

                const allowedTypes = [

                    'image/jpeg',

                    'image/png',

                    'image/webp'

                ];


                if (
                    !allowedTypes.includes(
                        file.type
                    )
                ) {

                    alert(
                        'Please select a JPG, PNG, or WebP image.'
                    );


                    this.value =
                        '';


                    photoPreview.classList.remove(
                        'show'
                    );


                    return;

                }


                /*
                -------------------------------------------------
                Maximum 5 MB
                -------------------------------------------------
                */

                const maxSize =
                    5 * 1024 * 1024;


                if (file.size > maxSize) {

                    alert(
                        'The image must be smaller than 5 MB.'
                    );


                    this.value =
                        '';


                    photoPreview.classList.remove(
                        'show'
                    );


                    return;

                }


                /*
                -------------------------------------------------
                Preview
                -------------------------------------------------
                */

                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        previewImage.src =
                            event.target.result;


                        photoPreview.classList.add(
                            'show'
                        );


                        if (photoName) {

                            photoName.textContent =
                                file.name;

                        }


                        if (photoSize) {

                            const sizeMB =
                                (
                                    file.size /
                                    (1024 * 1024)
                                ).toFixed(2);


                            photoSize.textContent =
                                sizeMB + ' MB';

                        }

                    };


                reader.readAsDataURL(file);

            }
        );

    }


    /*
    =====================================================
    INITIALIZE CITY / AREA
    =====================================================
    */

    if (
        citySelect &&
        citySelect.value
    ) {

        updateServiceAreas();

    }


    /*
    =====================================================
    ESCAPE HTML
    =====================================================
    */

    function escapeHtml(value) {

        const element =
            document.createElement(
                'div'
            );


        element.textContent =
            value;


        return element.innerHTML;

    }


});