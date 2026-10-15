document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       MOBILE NAVIGATION
    ====================================================== */

    const mobileToggle =
        document.querySelector('.admin-mobile-toggle');

    const navigation =
        document.querySelector('.admin-navigation');


    if (mobileToggle && navigation) {

        mobileToggle.addEventListener('click', function () {

            const isOpen =
                navigation.classList.toggle('show');

            mobileToggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );


            const icon =
                mobileToggle.querySelector('i');


            if (icon) {

                if (isOpen) {

                    icon.classList.remove('fa-bars');

                    icon.classList.add('fa-xmark');

                } else {

                    icon.classList.remove('fa-xmark');

                    icon.classList.add('fa-bars');

                }

            }

        });

    }


    /* =====================================================
       CLOSE MOBILE NAVIGATION
    ====================================================== */

    const navigationLinks =
        document.querySelectorAll(
            '.admin-navigation a'
        );


    navigationLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            if (navigation) {

                navigation.classList.remove('show');

            }


            if (mobileToggle) {

                mobileToggle.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            const icon =
                mobileToggle
                    ? mobileToggle.querySelector('i')
                    : null;


            if (icon) {

                icon.classList.remove('fa-xmark');

                icon.classList.add('fa-bars');

            }

        });

    });


    /* =====================================================
       CLOSE NAVIGATION WHEN CLICKING OUTSIDE
    ====================================================== */

    document.addEventListener('click', function (event) {

        if (
            !navigation ||
            !mobileToggle ||
            !navigation.classList.contains('show')
        ) {

            return;

        }


        const clickedInsideNavigation =
            navigation.contains(event.target);

        const clickedToggle =
            mobileToggle.contains(event.target);


        if (
            !clickedInsideNavigation &&
            !clickedToggle
        ) {

            navigation.classList.remove('show');

            mobileToggle.setAttribute(
                'aria-expanded',
                'false'
            );


            const icon =
                mobileToggle.querySelector('i');


            if (icon) {

                icon.classList.remove('fa-xmark');

                icon.classList.add('fa-bars');

            }

        }

    });


    /* =====================================================
       BOOKING PERIOD
    ====================================================== */

    const bookingPeriod =
        document.getElementById('bookingPeriod');


    if (bookingPeriod) {

        bookingPeriod.addEventListener(
            'change',
            function () {

                console.log(
                    'Selected booking period:',
                    this.value
                );

            }
        );

    }


    /* =====================================================
       ADMIN NOTIFICATION
    ====================================================== */

    const notificationButton =
        document.getElementById(
            'adminNotificationBtn'
        );


    if (notificationButton) {

        notificationButton.addEventListener(
            'click',
            function () {

                console.log(
                    'Admin notification clicked'
                );

            }
        );

    }


    /* =====================================================
       RESPONSIVE NAVIGATION RESET
    ====================================================== */

    window.addEventListener('resize', function () {

        if (
            window.innerWidth > 991 &&
            navigation
        ) {

            navigation.classList.remove('show');

        }


        if (
            window.innerWidth > 991 &&
            mobileToggle
        ) {

            mobileToggle.setAttribute(
                'aria-expanded',
                'false'
            );


            const icon =
                mobileToggle.querySelector('i');


            if (icon) {

                icon.classList.remove('fa-xmark');

                icon.classList.add('fa-bars');

            }

        }

    });

});
/* =====================================================
   ADMIN CUSTOMERS
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const customerSearch =
        document.getElementById('customerSearch');

    const customerStatusFilter =
        document.getElementById('customerStatusFilter');

    const customerLocationFilter =
        document.getElementById('customerLocationFilter');

    const customersTable =
        document.getElementById('customersTable');

    const customerResultCount =
        document.getElementById('customerResultCount');


    /* =================================================
       FILTER CUSTOMERS
    ================================================= */

    function filterCustomers() {

        if (!customersTable) {
            return;
        }


        const searchValue = customerSearch
            ? customerSearch.value.toLowerCase().trim()
            : '';


        const statusValue = customerStatusFilter
            ? customerStatusFilter.value
            : 'all';


        const locationValue = customerLocationFilter
            ? customerLocationFilter.value
            : 'all';


        const rows =
            customersTable.querySelectorAll('tbody tr');


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();


            const rowStatus =
                row.dataset.status;


            const rowLocation =
                row.dataset.location;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const matchesLocation =
                locationValue === 'all' ||
                rowLocation === locationValue;


            const shouldShow =
                matchesSearch &&
                matchesStatus &&
                matchesLocation;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        /* Update result text */

        if (customerResultCount) {

            customerResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 1,248 customers';

        }

    }


    /* =================================================
       SEARCH
    ================================================= */

    if (customerSearch) {

        customerSearch.addEventListener(
            'input',
            filterCustomers
        );

    }


    /* =================================================
       STATUS FILTER
    ================================================= */

    if (customerStatusFilter) {

        customerStatusFilter.addEventListener(
            'change',
            filterCustomers
        );

    }


    /* =================================================
       LOCATION FILTER
    ================================================= */

    if (customerLocationFilter) {

        customerLocationFilter.addEventListener(
            'change',
            filterCustomers
        );

    }


    /* =================================================
       VIEW CUSTOMER
    ================================================= */

    const viewCustomerButtons =
        document.querySelectorAll('.view-customer');


    viewCustomerButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const customerName =
                    this.dataset.customer || 'Customer';


                showAppAlert('Viewing customer: ' + customerName, 'View Customer');

            }
        );

    });


    /* =================================================
       EDIT CUSTOMER
    ================================================= */

    const editCustomerButtons =
        document.querySelectorAll('.edit-customer');


    editCustomerButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const customerName =
                    this.dataset.customer || 'Customer';


                showAppAlert('Editing customer: ' + customerName, 'Edit Customer');

            }
        );

    });


    /* =================================================
       ADD CUSTOMER
    ================================================= */

    const addCustomerButton =
        document.getElementById('addCustomerBtn');


    if (addCustomerButton) {

        addCustomerButton.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add Customer form will be available here.',
                    'Add Customer'
                );

            }
        );

    }


    /* =================================================
       EXPORT CUSTOMERS
    ================================================= */

    const exportCustomersButton =
        document.getElementById('exportCustomersBtn');


    if (exportCustomersButton) {

        exportCustomersButton.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Customer export will be available here.',
                    'Export Customers'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN PROVIDERS
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const providerSearch =
        document.getElementById('providerSearch');

    const providerStatusFilter =
        document.getElementById('providerStatusFilter');

    const providerLocationFilter =
        document.getElementById('providerLocationFilter');

    const providersTable =
        document.getElementById('providersTable');

    const providerResultCount =
        document.getElementById('providerResultCount');


    function filterProviders() {

        if (!providersTable) {
            return;
        }


        const searchValue =
            providerSearch
                ? providerSearch.value.toLowerCase().trim()
                : '';


        const statusValue =
            providerStatusFilter
                ? providerStatusFilter.value
                : 'all';


        const locationValue =
            providerLocationFilter
                ? providerLocationFilter.value
                : 'all';


        const rows =
            providersTable.querySelectorAll('tbody tr');


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();


            const rowStatus =
                row.dataset.status;


            const rowLocation =
                row.dataset.location;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const matchesLocation =
                locationValue === 'all' ||
                rowLocation === locationValue;


            const shouldShow =
                matchesSearch &&
                matchesStatus &&
                matchesLocation;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (providerResultCount) {

            providerResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 86 providers';

        }

    }


    if (providerSearch) {

        providerSearch.addEventListener(
            'input',
            filterProviders
        );

    }


    if (providerStatusFilter) {

        providerStatusFilter.addEventListener(
            'change',
            filterProviders
        );

    }


    if (providerLocationFilter) {

        providerLocationFilter.addEventListener(
            'change',
            filterProviders
        );

    }


    /* =================================================
       VIEW PROVIDER
    ================================================= */

    document
        .querySelectorAll('.view-provider')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const provider =
                        this.dataset.provider;

                    showAppAlert(
                        'View Provider: ' +
                        provider,
                        'View Provider'
                    );

                }
            );

        });


    /* =================================================
       EDIT PROVIDER
    ================================================= */

    document
        .querySelectorAll('.edit-provider')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const provider =
                        this.dataset.provider;

                    showAppAlert(
                        'Edit Provider: ' +
                        provider,
                        'Edit Provider'
                    );

                }
            );

        });


    /* =================================================
       ADD PROVIDER
    ================================================= */

    const addProviderBtn =
        document.getElementById('addProviderBtn');


    if (addProviderBtn) {

        addProviderBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add Provider feature will be connected later.',
                    'Add Provider'
                );

            }
        );

    }


    /* =================================================
       EXPORT PROVIDERS
    ================================================= */

    const exportProvidersBtn =
        document.getElementById('exportProvidersBtn');


    if (exportProvidersBtn) {

        exportProvidersBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Provider export feature will be connected later.',
                    'Export Providers'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN SERVICES
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const serviceSearch =
        document.getElementById('serviceSearch');

    const serviceCategoryFilter =
        document.getElementById('serviceCategoryFilter');

    const serviceStatusFilter =
        document.getElementById('serviceStatusFilter');

    const servicesTable =
        document.getElementById('servicesTable');

    const serviceResultCount =
        document.getElementById('serviceResultCount');


    /* =================================================
       FILTER SERVICES
    ================================================== */

    function filterServices() {

        if (!servicesTable) {
            return;
        }


        const searchValue =
            serviceSearch
                ? serviceSearch.value.toLowerCase().trim()
                : '';


        const categoryValue =
            serviceCategoryFilter
                ? serviceCategoryFilter.value
                : 'all';


        const statusValue =
            serviceStatusFilter
                ? serviceStatusFilter.value
                : 'all';


        const rows =
            servicesTable.querySelectorAll('tbody tr');


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();


            const rowCategory =
                row.dataset.category;


            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesCategory =
                categoryValue === 'all' ||
                rowCategory === categoryValue;


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const shouldShow =
                matchesSearch &&
                matchesCategory &&
                matchesStatus;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (serviceResultCount) {

            serviceResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 32 services';

        }

    }


    /* =================================================
       SEARCH
    ================================================== */

    if (serviceSearch) {

        serviceSearch.addEventListener(
            'input',
            filterServices
        );

    }


    /* =================================================
       CATEGORY FILTER
    ================================================== */

    if (serviceCategoryFilter) {

        serviceCategoryFilter.addEventListener(
            'change',
            filterServices
        );

    }


    /* =================================================
       STATUS FILTER
    ================================================== */

    if (serviceStatusFilter) {

        serviceStatusFilter.addEventListener(
            'change',
            filterServices
        );

    }


    /* =================================================
       VIEW SERVICE
    ================================================== */

    document
        .querySelectorAll('.view-service')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const service =
                        this.dataset.service;

                    showAppAlert(
                        'View Service: ' +
                        service,
                        'View Service'
                    );

                }
            );

        });


    /* =================================================
       EDIT SERVICE
    ================================================== */

    document
        .querySelectorAll('.edit-service')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const service =
                        this.dataset.service;

                    showAppAlert(
                        'Edit Service: ' +
                        service,
                        'Edit Service'
                    );

                }
            );

        });


    /* =================================================
       ADD SERVICE
    ================================================== */

    const addServiceBtn =
        document.getElementById('addServiceBtn');


    if (addServiceBtn) {

        addServiceBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add Service feature will be connected later.',
                    'Add Service'
                );

            }
        );

    }


    /* =================================================
       EXPORT SERVICES
    ================================================== */

    const exportServicesBtn =
        document.getElementById('exportServicesBtn');


    if (exportServicesBtn) {

        exportServicesBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Service export feature will be connected later.',
                    'Export Services'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN BOOKINGS
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const bookingSearch =
        document.getElementById('bookingSearch');

    const bookingStatusFilter =
        document.getElementById('bookingStatusFilter');

    const bookingServiceFilter =
        document.getElementById('bookingServiceFilter');

    const bookingDateFilter =
        document.getElementById('bookingDateFilter');

    const bookingsTable =
        document.getElementById('bookingsTable');

    const bookingResultCount =
        document.getElementById('bookingResultCount');

    const exportBookingsBtn =
        document.getElementById('exportBookingsBtn');


    /* =================================================
       FILTER BOOKINGS
    ================================================== */

    function filterBookings() {

        if (!bookingsTable) {
            return;
        }

        const searchValue =
            bookingSearch
                ? bookingSearch.value
                    .toLowerCase()
                    .trim()
                : '';

        const statusValue =
            bookingStatusFilter
                ? bookingStatusFilter.value
                : 'all';

        const serviceValue =
            bookingServiceFilter
                ? bookingServiceFilter.value
                : 'all';

        const dateValue =
            bookingDateFilter
                ? bookingDateFilter.value
                : 'all';


        const rows =
            bookingsTable.querySelectorAll(
                'tbody tr'
            );


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent
                    .toLowerCase();

            const rowStatus =
                row.dataset.status;

            const rowService =
                row.dataset.service;

            const rowDate =
                row.dataset.date;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const matchesService =
                serviceValue === 'all' ||
                rowService === serviceValue;


            const matchesDate =
                dateValue === 'all' ||
                rowDate === dateValue;


            const shouldShow =
                matchesSearch &&
                matchesStatus &&
                matchesService &&
                matchesDate;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (bookingResultCount) {

            bookingResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 320 bookings';

        }

    }


    /* =================================================
       SEARCH
    ================================================== */

    if (bookingSearch) {

        bookingSearch.addEventListener(
            'input',
            filterBookings
        );

    }


    /* =================================================
       STATUS FILTER
    ================================================== */

    if (bookingStatusFilter) {

        bookingStatusFilter.addEventListener(
            'change',
            filterBookings
        );

    }


    /* =================================================
       SERVICE FILTER
    ================================================== */

    if (bookingServiceFilter) {

        bookingServiceFilter.addEventListener(
            'change',
            filterBookings
        );

    }


    /* =================================================
       DATE FILTER
    ================================================== */

    if (bookingDateFilter) {

        bookingDateFilter.addEventListener(
            'change',
            filterBookings
        );

    }


    /* =================================================
       VIEW BOOKING
    ================================================== */

    document
        .querySelectorAll('.view-booking')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'Booking details page will be connected later.',
                        'View Booking'
                    );

                }
            );

        });


    /* =================================================
       EXPORT
    ================================================== */

    if (exportBookingsBtn) {

        exportBookingsBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Booking export will be connected later.',
                    'Export Bookings'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN CATEGORIES
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const categorySearch =
        document.getElementById('categorySearch');

    const categoryStatusFilter =
        document.getElementById('categoryStatusFilter');

    const categoriesTable =
        document.getElementById('categoriesTable');

    const categoryResultCount =
        document.getElementById('categoryResultCount');

    const addCategoryBtn =
        document.getElementById('addCategoryBtn');


    /* =================================================
       FILTER CATEGORIES
    ================================================== */

    function filterCategories() {

        if (!categoriesTable) {
            return;
        }

        const searchValue =
            categorySearch
                ? categorySearch.value
                    .toLowerCase()
                    .trim()
                : '';

        const statusValue =
            categoryStatusFilter
                ? categoryStatusFilter.value
                : 'all';


        const rows =
            categoriesTable.querySelectorAll(
                'tbody tr'
            );


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent
                    .toLowerCase();

            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const shouldShow =
                matchesSearch &&
                matchesStatus;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (categoryResultCount) {

            categoryResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 8 categories';

        }

    }


    /* =================================================
       SEARCH
    ================================================== */

    if (categorySearch) {

        categorySearch.addEventListener(
            'input',
            filterCategories
        );

    }


    /* =================================================
       STATUS FILTER
    ================================================== */

    if (categoryStatusFilter) {

        categoryStatusFilter.addEventListener(
            'change',
            filterCategories
        );

    }


    /* =================================================
       VIEW CATEGORY
    ================================================== */

    document
        .querySelectorAll('.view-category')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'Category details page will be connected later.',
                        'View Category'
                    );

                }
            );

        });


    /* =================================================
       EDIT CATEGORY
    ================================================== */

    document
        .querySelectorAll('.edit-category')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'Category edit form will be connected later.',
                        'Edit Category'
                    );

                }
            );

        });


    /* =================================================
       ADD CATEGORY
    ================================================== */

    if (addCategoryBtn) {

        addCategoryBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add category form will be connected later.',
                    'Add Category'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN CITIES
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const citySearch =
        document.getElementById('citySearch');

    const cityStatusFilter =
        document.getElementById('cityStatusFilter');

    const citiesTable =
        document.getElementById('citiesTable');

    const cityResultCount =
        document.getElementById('cityResultCount');

    const addCityBtn =
        document.getElementById('addCityBtn');


    function filterCities() {

        if (!citiesTable) {
            return;
        }

        const searchValue =
            citySearch
                ? citySearch.value
                    .toLowerCase()
                    .trim()
                : '';

        const statusValue =
            cityStatusFilter
                ? cityStatusFilter.value
                : 'all';


        const rows =
            citiesTable.querySelectorAll(
                'tbody tr'
            );


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();

            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                rowText.includes(searchValue);

            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const shouldShow =
                matchesSearch &&
                matchesStatus;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (cityResultCount) {

            cityResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 3 cities';

        }

    }


    if (citySearch) {

        citySearch.addEventListener(
            'input',
            filterCities
        );

    }


    if (cityStatusFilter) {

        cityStatusFilter.addEventListener(
            'change',
            filterCities
        );

    }


    document
        .querySelectorAll('.view-city')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'City details will be connected later.',
                        'View City'
                    );

                }
            );

        });


    document
        .querySelectorAll('.edit-city')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'City edit form will be connected later.',
                        'Edit City'
                    );

                }
            );

        });


    if (addCityBtn) {

        addCityBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add city form will be connected later.',
                    'Add City'
                );

            }
        );

    }

});
/* =====================================================
   ADMIN SERVICE AREAS
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const serviceAreaSearch =
        document.getElementById('serviceAreaSearch');

    const serviceAreaCityFilter =
        document.getElementById('serviceAreaCityFilter');

    const serviceAreaStatusFilter =
        document.getElementById('serviceAreaStatusFilter');

    const serviceAreasTable =
        document.getElementById('serviceAreasTable');

    const serviceAreaResultCount =
        document.getElementById('serviceAreaResultCount');

    const addServiceAreaBtn =
        document.getElementById('addServiceAreaBtn');


    function filterServiceAreas() {

        if (!serviceAreasTable) {
            return;
        }


        const searchValue =
            serviceAreaSearch
                ? serviceAreaSearch.value
                    .toLowerCase()
                    .trim()
                : '';


        const cityValue =
            serviceAreaCityFilter
                ? serviceAreaCityFilter.value
                : 'all';


        const statusValue =
            serviceAreaStatusFilter
                ? serviceAreaStatusFilter.value
                : 'all';


        const rows =
            serviceAreasTable.querySelectorAll(
                'tbody tr'
            );


        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();

            const rowCity =
                row.dataset.city;

            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesCity =
                cityValue === 'all' ||
                rowCity === cityValue;


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            const shouldShow =
                matchesSearch &&
                matchesCity &&
                matchesStatus;


            if (shouldShow) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        if (serviceAreaResultCount) {

            serviceAreaResultCount.textContent =
                'Showing ' +
                visibleCount +
                ' of 12 service areas';

        }

    }


    if (serviceAreaSearch) {

        serviceAreaSearch.addEventListener(
            'input',
            filterServiceAreas
        );

    }


    if (serviceAreaCityFilter) {

        serviceAreaCityFilter.addEventListener(
            'change',
            filterServiceAreas
        );

    }


    if (serviceAreaStatusFilter) {

        serviceAreaStatusFilter.addEventListener(
            'change',
            filterServiceAreas
        );

    }


    document
        .querySelectorAll('.view-service-area')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'Service area details will be connected later.',
                        'View Service Area'
                    );

                }
            );

        });


    document
        .querySelectorAll('.edit-service-area')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    showAppAlert(
                        'Service area edit form will be connected later.',
                        'Edit Service Area'
                    );

                }
            );

        });


    if (addServiceAreaBtn) {

        addServiceAreaBtn.addEventListener(
            'click',
            function () {

                showAppAlert(
                    'Add service area form will be connected later.',
                    'Add Service Area'
                );

            }
        );

    }

});

document.addEventListener('DOMContentLoaded', function () {
    const confirmButton = document.getElementById('confirmAdminLogout');
    const logoutForm = document.getElementById('adminLogoutForm');
    const logoutModalElement = document.getElementById('adminLogoutModal');

    if (confirmButton && logoutForm && logoutModalElement) {
        confirmButton.addEventListener('click', function () {
            confirmButton.disabled = true;
            confirmButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Logging out...';

            logoutForm.submit();
        });
    }
});
