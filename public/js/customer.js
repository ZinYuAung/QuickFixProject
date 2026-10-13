/* =====================================================
   QUICKFIX CUSTOMER DASHBOARD
   CUSTOMER SIDEBAR
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const layout =
        document.getElementById("customerLayout");

    const sidebarToggle =
        document.getElementById("customerSidebarToggle");

    const mobileMenu =
        document.getElementById("customerMobileMenu");

    const overlay =
        document.getElementById("customerOverlay");

    const customerLogo =
        document.querySelector(".customer-logo");


    /* =================================================
       DESKTOP SIDEBAR TOGGLE
    ================================================= */

    if (sidebarToggle) {

        sidebarToggle.addEventListener("click", function () {

            if (window.innerWidth <= 767) {

                layout.classList.remove(
                    "sidebar-collapsed"
                );

                layout.classList.toggle(
                    "mobile-sidebar-open"
                );

            } else {

                layout.classList.toggle(
                    "sidebar-collapsed"
                );

            }

        });

    }


    /* =================================================
       CLICK QUICKFIX LOGO

       If sidebar is collapsed:
       expand sidebar instead of going home.
    ================================================= */

    if (customerLogo) {

        customerLogo.addEventListener("click", function (event) {

            if (
                window.innerWidth > 767 &&
                layout.classList.contains("sidebar-collapsed")
            ) {

                event.preventDefault();

                layout.classList.remove(
                    "sidebar-collapsed"
                );

            }

        });

    }


    /* =================================================
       MOBILE MENU
    ================================================= */

    if (mobileMenu) {

        mobileMenu.addEventListener("click", function () {

            layout.classList.add(
                "mobile-sidebar-open"
            );

        });

    }


    /* =================================================
       OVERLAY
    ================================================= */

    if (overlay) {

        overlay.addEventListener("click", function () {

            layout.classList.remove(
                "mobile-sidebar-open"
            );

        });

    }


    /* =================================================
       CLOSE MOBILE SIDEBAR
       AFTER MENU CLICK
    ================================================= */

    const menuLinks =
        document.querySelectorAll(
            ".customer-menu-link"
        );


    menuLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (window.innerWidth <= 767) {

                layout.classList.remove(
                    "mobile-sidebar-open"
                );

            }

        });

    });


    /* =================================================
       WINDOW RESIZE
    ================================================= */

    window.addEventListener("resize", function () {

        if (window.innerWidth > 767) {

            layout.classList.remove(
                "mobile-sidebar-open"
            );

        }

    });

});


/* =====================================================
   CUSTOMER NOTIFICATIONS
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const notificationButton =
        document.getElementById('notificationButton');

    const notificationDropdown =
        document.getElementById('notificationDropdown');

    const notificationBadge =
        document.getElementById('notificationBadge');

    const notificationUnreadText =
        document.getElementById('notificationUnreadText');


    if (!notificationButton || !notificationDropdown) {
        return;
    }


    /* =================================================
       GET CSRF TOKEN
    ================================================= */

    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute('content');


    /* =================================================
       UPDATE UNREAD COUNT
    ================================================= */

    function updateUnreadCount() {

        const unreadItems =
            document.querySelectorAll(
                '.customer-notification-item.unread'
            );

        const unreadCount =
            unreadItems.length;


        /* Update header text */

        if (notificationUnreadText) {

            notificationUnreadText.textContent =
                unreadCount + ' unread';

        }


        /* Update badge */

        if (unreadCount > 0) {

            if (notificationBadge) {

                notificationBadge.textContent =
                    unreadCount > 99
                        ? '99+'
                        : unreadCount;

            }

        } else {

            if (notificationBadge) {

                notificationBadge.remove();

            }

        }

    }


    /* =================================================
       MARK ONE NOTIFICATION AS READ
    ================================================= */

    function markNotificationAsRead(
        notificationId,
        item
    ) {

        if (!notificationId || !item) {
            return Promise.resolve();
        }


        const url =
            `/customer/notifications/${notificationId}/read`;


        return fetch(url, {

            method: 'POST',

            headers: {

                'Content-Type':
                    'application/json',

                'X-CSRF-TOKEN':
                    csrfToken,

                'Accept':
                    'application/json'

            },

            body: JSON.stringify({})

        })
        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Failed to mark notification as read.'
                );

            }

            return response.json();

        })
        .then(function () {

            /* Remove unread style */

            item.classList.remove('unread');


            /* Remove unread dot */

            const unreadDot =
                item.querySelector(
                    '.customer-notification-unread-dot'
                );

            if (unreadDot) {
                unreadDot.remove();
            }


            /* Change Read button to Read label */

            const readButton =
                item.querySelector(
                    '.mark-read-btn'
                );

            if (readButton) {

                const readLabel =
                    document.createElement('span');

                readLabel.className =
                    'customer-notification-read-label';

                readLabel.textContent =
                    'Read';

                readButton.replaceWith(
                    readLabel
                );

            }


            /* Update badge/count */

            updateUnreadCount();

        });

    }


    /* =================================================
       POLL FOR NEW UNREAD NOTIFICATIONS
       EVERY 1 SECOND
    ================================================= */

    function pollUnreadNotifications() {

        fetch('/customer/notifications/unread', {

            method: 'GET',

            headers: {

                'Accept':
                    'application/json'

            }

        })
        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Failed to fetch unread notifications.'
                );

            }

            return response.json();

        })
        .then(function (data) {

            if (!data.success) {
                return;
            }


            /* =================================================
               UPDATE BADGE
            ================================================= */

            let badge =
                document.getElementById(
                    'notificationBadge'
                );


            if (data.count > 0) {

                if (!badge) {

                    badge =
                        document.createElement('span');

                    badge.className =
                        'customer-notification-badge';

                    badge.id =
                        'notificationBadge';

                    notificationButton.appendChild(
                        badge
                    );

                }

                badge.textContent =
                    data.count > 99
                        ? '99+'
                        : data.count;

            } else {

                if (badge) {
                    badge.remove();
                }

            }


            /* =================================================
               UPDATE UNREAD TEXT
            ================================================= */

            if (notificationUnreadText) {

                notificationUnreadText.textContent =
                    data.count + ' unread';

            }


            /* =================================================
               GET DROPDOWN LIST
            ================================================= */

            const notificationList =
                notificationDropdown.querySelector(
                    '.customer-notification-list'
                );


            if (!notificationList) {
                return;
            }


            /* =================================================
               EMPTY
            ================================================= */

            if (data.notifications.length === 0) {

                notificationList.innerHTML = `

                    <div class="customer-notification-empty">

                        <i class="fa-regular fa-bell-slash"></i>

                        <p>
                            No notifications
                        </p>

                    </div>

                `;

                return;
            }


            /* =================================================
               BUILD NOTIFICATION LIST
            ================================================= */

            notificationList.innerHTML =
                data.notifications.map(
                    function (notification) {

                        let notificationLink = '#';


                        /* Payment notification */

                        if (
                            (
                                notification.type === 'payment_verified' ||
                                notification.type === 'payment_rejected'
                            ) &&
                            notification.payment_id &&
                            notification.booking_id
                        ) {

                            notificationLink =
                                `/customer/bookings/${notification.booking_id}/payment`;

                        }


                        /* Normal booking notification */

                        else if (
                            notification.booking_id
                        ) {

                            notificationLink =
                                `/customer/bookings/${notification.booking_id}`;

                        }


                        return `

                            <div
                                class="customer-notification-item unread"
                                data-id="${notification.id}"
                            >

                                <div
                                    class="customer-notification-item-icon"
                                >

                                    <i
                                        class="fa-solid fa-bell"
                                    ></i>

                                </div>


                                <div
                                    class="customer-notification-item-content"
                                >

                                    ${
                                        notificationLink !== '#'
                                        ? `
                                            <a
                                                href="${notificationLink}"
                                                class="customer-notification-link"
                                                data-notification-id="${notification.id}"
                                            >

                                                <strong>
                                                    ${notification.title}
                                                </strong>

                                                <p>
                                                    ${notification.message}
                                                </p>

                                            </a>
                                        `
                                        : `
                                            <strong>
                                                ${notification.title}
                                            </strong>

                                            <p>
                                                ${notification.message}
                                            </p>
                                        `
                                    }


                                    <small>
                                        ${notification.created_at}
                                    </small>

                                </div>


                                <div
                                    class="customer-notification-actions"
                                >

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light mark-read-btn"
                                        data-id="${notification.id}"
                                        title="Mark as read"
                                    >

                                        <i
                                            class="fa-solid fa-check"
                                        ></i>

                                        Read

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger delete-notification-btn"
                                        data-id="${notification.id}"
                                        title="Delete notification"
                                    >

                                        <i
                                            class="fa-solid fa-trash"
                                        ></i>

                                    </button>

                                </div>


                                <span
                                    class="customer-notification-unread-dot"
                                ></span>

                            </div>

                        `;

                    }
                ).join('');

        })
        .catch(function (error) {

            console.error(
                'Notification polling error:',
                error
            );

        });

    }


    /* Start polling every 1 second */

    setInterval(
        pollUnreadNotifications,
        1000
    );


    /* =================================================
       OPEN / CLOSE DROPDOWN
    ================================================= */

    notificationButton.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            notificationDropdown.classList.toggle(
                'show'
            );

        }
    );


    /* =================================================
       READ BUTTON
       EVENT DELEGATION
    ================================================= */

    notificationDropdown.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.mark-read-btn'
                );

            if (!button) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();


            const notificationId =
                button.dataset.id;


            const item =
                button.closest(
                    '.customer-notification-item'
                );


            markNotificationAsRead(
                notificationId,
                item
            )
            .catch(function (error) {

                console.error(
                    'Failed to mark notification as read:',
                    error
                );

            });

        }
    );


    /* =================================================
       CLICK NOTIFICATION
       MARK READ THEN OPEN DETAIL
       EVENT DELEGATION
    ================================================= */

    notificationDropdown.addEventListener(
        'click',
        function (event) {

            const link =
                event.target.closest(
                    '.customer-notification-link'
                );


            if (!link) {
                return;
            }


            const notificationId =
                link.dataset.notificationId;


            const item =
                link.closest(
                    '.customer-notification-item'
                );


            if (
                !item ||
                !item.classList.contains('unread')
            ) {
                return;
            }


            event.preventDefault();


            markNotificationAsRead(
                notificationId,
                item
            )
            .then(function () {

                window.location.href =
                    link.href;

            })
            .catch(function () {

                window.location.href =
                    link.href;

            });

        }
    );


    /* =================================================
       DELETE NOTIFICATION
       EVENT DELEGATION
    ================================================= */

    notificationDropdown.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.delete-notification-btn'
                );


            if (!button) {
                return;
            }


            event.preventDefault();
            event.stopPropagation();


            const notificationId =
                button.dataset.id;


            const item =
                button.closest(
                    '.customer-notification-item'
                );


            if (!notificationId || !item) {
                return;
            }


            fetch(
                `/customer/notifications/${notificationId}`,
                {

                    method: 'DELETE',

                    headers: {

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'Accept':
                            'application/json'

                    }

                }
            )
            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        'Failed to delete notification.'
                    );

                }

                return response.json();

            })
            .then(function (data) {

                if (!data.success) {
                    return;
                }


                /* Remove notification from UI */

                item.remove();


                /* Update unread count */

                updateUnreadCount();

            })
            .catch(function (error) {

                console.error(
                    'Delete notification error:',
                    error
                );

            });

        }
    );


    /* =================================================
       PREVENT DROPDOWN FROM CLOSING
    ================================================= */

    notificationDropdown.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

        }
    );


    /* =================================================
       CLICK OUTSIDE
    ================================================= */

    document.addEventListener(
        'click',
        function () {

            notificationDropdown.classList.remove(
                'show'
            );

        }
    );

});


/* =====================================================
   CUSTOMER CHAT UNREAD SIDEBAR COUNT
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const chatUnreadBadge =
        document.getElementById(
            "customerSidebarChatUnread"
        );


    /* If this page does not have the Chat badge,
       do nothing. */

    if (!chatUnreadBadge) {
        return;
    }


    function updateCustomerChatUnreadCount() {

        fetch(
            "/customer/chats/unread-counts",
            {

                method: "GET",

                headers: {

                    "Accept":
                        "application/json"

                }

            }
        )
        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    "Failed to fetch chat unread count."
                );

            }

            return response.json();

        })
        .then(function (data) {

            if (!data.success) {
                return;
            }


            const totalUnread =
                Number(data.total || 0);


            if (totalUnread > 0) {

                chatUnreadBadge.textContent =
                    totalUnread > 99
                        ? "99+"
                        : totalUnread;

                chatUnreadBadge.style.display =
                    "inline-flex";

            } else {

                chatUnreadBadge.style.display =
                    "none";

            }

        })
        .catch(function (error) {

            console.error(
                "Chat unread count error:",
                error
            );

        });

    }


    /* Get count immediately */

    updateCustomerChatUnreadCount();


    /* Update automatically every 1 second */

    setInterval(
        updateCustomerChatUnreadCount,
        1000
    );

});


/* =====================================================
   CUSTOMER DASHBOARD
   SERVICE SEARCH + CITY + AREA FILTER
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('serviceSearch');

    const cityFilter =
        document.getElementById('cityFilter');

    const areaFilter =
        document.getElementById('areaFilter');

    const resultsContainer =
        document.getElementById('customerSearchResults');

    const serviceAreasElement =
        document.getElementById('customerServiceAreas');


    /* =================================================
       CHECK REQUIRED ELEMENTS
    ================================================= */

    if (
        !searchInput ||
        !cityFilter ||
        !areaFilter ||
        !resultsContainer ||
        !serviceAreasElement
    ) {
        return;
    }


    /* =================================================
       GET SERVICE AREA DATA FROM BLADE
    ================================================= */

    let serviceAreas = [];


    try {

        serviceAreas =
            JSON.parse(
                serviceAreasElement.textContent
            );

    } catch (error) {

        console.error(
            'Failed to load service area data:',
            error
        );

        return;

    }


    /* =================================================
       FILTER SERVICES
    ================================================= */

   function filterServices() {

    const keyword =
        searchInput.value
            .trim()
            .toLowerCase();

    const cityId =
        cityFilter.value;

    const areaId =
        areaFilter.value;

    const results =
        resultsContainer.querySelectorAll(
            '.customer-search-result-item'
        );

    /*
     * Show services only when
     * Search OR City OR Area is selected.
     */
    const hasFilter =
        keyword !== '' ||
        cityId !== '' ||
        areaId !== '';

    results.forEach(function (result) {

        if (!hasFilter) {
            result.style.display = 'none';
            return;
        }

        const serviceName =
            result.dataset.serviceName || '';

        const areaIds =
            (result.dataset.areaIds || '')
                .split(',')
                .filter(Boolean);

        /* Search */
        const matchesSearch =
            keyword === '' ||
            serviceName.includes(keyword);

        /* City */
        const matchesCity =
            cityId === '' ||
            areaIds.some(function (id) {

                const area =
                    serviceAreas.find(function (area) {

                        return String(area.id) ===
                            String(id);

                    });

                return area &&
                    String(area.city_id) ===
                    String(cityId);
            });

        /* Area */
        const matchesArea =
            areaId === '' ||
            areaIds.includes(String(areaId));

        /*
         * All selected filters must match.
         */
        if (
            matchesSearch &&
            matchesCity &&
            matchesArea
        ) {
            result.style.display = '';
        } else {
            result.style.display = 'none';
        }
    });
}


    /* =================================================
       CITY CHANGE
       LOAD AREAS AUTOMATICALLY
    ================================================= */

    cityFilter.addEventListener(
        'change',
        function () {

            const selectedCityId =
                this.value;


            /* Clear previous areas */

            areaFilter.innerHTML = `
                <option value="">
                    All Areas
                </option>
            `;


            /* =================================================
               NO CITY SELECTED
            ================================================= */

            if (!selectedCityId) {

                areaFilter.disabled = true;

                filterServices();

                return;

            }


            /* =================================================
               FIND AREAS BELONGING TO SELECTED CITY
            ================================================= */

            const cityAreas =
                serviceAreas.filter(
                    function (area) {

                        return String(
                            area.city_id
                        ) === String(
                            selectedCityId
                        );

                    }
                );


            /* =================================================
               ADD AREAS
            ================================================= */

            cityAreas.forEach(
                function (area) {

                    const option =
                        document.createElement(
                            'option'
                        );


                    option.value =
                        area.id;


                    option.textContent =
                        area.name;


                    areaFilter.appendChild(
                        option
                    );

                }
            );


            /* =================================================
               ENABLE / DISABLE AREA
            ================================================= */

            areaFilter.disabled =
                cityAreas.length === 0;


            /* =================================================
               APPLY FILTER
            ================================================= */

            filterServices();

        }
    );


    /* =================================================
       AREA CHANGE
    ================================================= */

    areaFilter.addEventListener(
        'change',
        function () {

            filterServices();

        }
    );


    /* =================================================
       SERVICE SEARCH
       SUPPORTS ONE LETTER / PARTIAL WORD

       Example:
       p      -> Plumbing
       pl     -> Plumbing
       clean  -> Cleaning
    ================================================= */

    searchInput.addEventListener(
        'input',
        function () {

            filterServices();

        }
    );


    /* =================================================
       INITIAL FILTER
    ================================================= */

    filterServices();

});