/* =====================================================
   QUICKFIX PROVIDER CHAT JAVASCRIPT
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const form =
        document.getElementById("providerChatForm");

    const input =
        document.getElementById("providerMessageInput");

    const messagesBox =
        document.getElementById("providerChatMessages");

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");




    /*
    |-----------------------------------------------------
    | Current provider ID
    |-----------------------------------------------------
    */

    const providerUserId =
        Number(window.providerUserId);
            /* 
    |----------------------------------------------------- 
    | PROVIDER CHAT UNREAD COUNTS
    |----------------------------------------------------- 
    */

    const chatUsers =
        document.querySelectorAll(".provider-chat-user");

    async function fetchUnreadCounts() {

        try {

            const response =
                await fetch(
                    "/provider/chats/unread-counts",
                    {
                        method: "GET",

                        headers: {
                            "Accept":
                                "application/json",

                            "X-Requested-With":
                                "XMLHttpRequest"
                        },

                        cache: "no-store"
                    }
                );


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            if (!data.success) {
                return;
            }


            const counts =
                data.counts || {};

            const totalUnread =
                Number(data.total || 0);


            /* 
             * Sidebar total unread
             */
            const sidebarBadge =
                document.getElementById(
                    "providerSidebarChatUnread"
                );


            if (sidebarBadge) {

                if (totalUnread > 0) {

                    sidebarBadge.textContent =
                        totalUnread > 99
                            ? "99+"
                            : totalUnread;

                    sidebarBadge.style.display =
                        "inline-flex";

                } else {

                    sidebarBadge.style.display =
                        "none";

                }

            }


            /* 
             * Individual customer unread
             */
            chatUsers.forEach(
                function (user) {

                    const customerId =
                        user.dataset.customerId;


                    const unreadCount =
                        Number(
                            counts[customerId] || 0
                        );


                    let badge =
                        user.querySelector(
                            ".provider-customer-chat-unread"
                        );


                    if (unreadCount > 0) {

                        if (!badge) {

                            badge =
                                document.createElement(
                                    "span"
                                );

                            badge.className =
                                "provider-customer-chat-unread";

                            user.appendChild(badge);

                        }


                        badge.textContent =
                            unreadCount > 99
                                ? "99+"
                                : unreadCount;

                    } else {

                        if (badge) {
                            badge.remove();
                        }

                    }

                }
            );

        } catch (error) {

            console.error(
                "Provider unread count error:",
                error
            );

        }

    }


    /*
    |-----------------------------------------------------
    | START UNREAD COUNT REFRESH
    |-----------------------------------------------------
    */

    fetchUnreadCounts();

    setInterval(
        fetchUnreadCounts,
        1000
    );
    /*
|-----------------------------------------------------
| No active customer
|-----------------------------------------------------
*/

if (!form || !input || !messagesBox) {
    return;
}


    /*
    |-----------------------------------------------------
    | ADD MESSAGE TO CHAT
    |-----------------------------------------------------
    */

    function addMessage(message) {

        /*
         * Prevent duplicate messages.
         */

        if (
            messagesBox.querySelector(
                `[data-message-id="${message.id}"]`
            )
        ) {
            return;
        }


        /*
         * Remove empty message.
         */

        const emptyMessage =
            messagesBox.querySelector(
                ".provider-chat-empty"
            );

        if (emptyMessage) {
            emptyMessage.remove();
        }


        /*
         * Create message wrapper.
         */

        const wrapper =
            document.createElement("div");


        wrapper.className =
            Number(message.sender_id) === providerUserId
                ? "provider-message provider"
                : "provider-message customer";


        wrapper.setAttribute(
            "data-message-id",
            message.id
        );


        /*
         * Message bubble.
         */

        const bubble =
            document.createElement("div");

        bubble.className =
            "provider-message-bubble";

        bubble.textContent =
            message.message;


        /*
         * Message time.
         */

        const time =
            document.createElement("span");

        time.textContent =
            message.time;


        /*
         * Append elements.
         */

        wrapper.appendChild(bubble);
        wrapper.appendChild(time);

        messagesBox.appendChild(wrapper);


        /*
         * Scroll to bottom.
         */

        messagesBox.scrollTop =
            messagesBox.scrollHeight;
    }


    /*
    |-----------------------------------------------------
    | SEND MESSAGE
    |-----------------------------------------------------
    */

    form.addEventListener(
        "submit",
        async function (event) {

            event.preventDefault();


            const message =
                input.value.trim();


            if (!message) {
                return;
            }


            /*
             * Disable input while sending.
             */

            input.disabled = true;


            try {

                const response =
                    await fetch(
                        form.action,
                        {
                            method: "POST",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "Accept":
                                    "application/json",

                                "X-CSRF-TOKEN":
                                    csrfToken,

                                "X-Requested-With":
                                    "XMLHttpRequest"
                            },

                            body: JSON.stringify({
                                message: message
                            })
                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {
                    throw new Error(
                        "Message could not be sent."
                    );
                }


                /*
                 * Add DB-saved message.
                 */

                addMessage(data.message);


                /*
                 * Clear input.
                 */

                input.value = "";

                input.focus();


            } catch (error) {

                console.error(
                    "Provider chat send error:",
                    error
                );

                alert(
                    "Unable to send message."
                );

            } finally {

                input.disabled = false;

            }

        }
    );


    /*
    |-----------------------------------------------------
    | ENTER KEY
    |-----------------------------------------------------
    */

    input.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Enter" &&
                !event.shiftKey
            ) {

                event.preventDefault();

                form.requestSubmit();

            }

        }
    );


    /*
    |-----------------------------------------------------
    | FETCH NEW MESSAGES
    |-----------------------------------------------------
    */

    async function fetchMessages() {

        const fetchUrl =
            messagesBox.dataset.fetchUrl;


        if (!fetchUrl) {
            return;
        }


        try {

            const response =
                await fetch(
                    fetchUrl,
                    {
                        method: "GET",

                        headers: {
                            "Accept":
                                "application/json",

                            "X-Requested-With":
                                "XMLHttpRequest"
                        }
                    }
                );


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            if (
                data.success &&
                Array.isArray(data.messages)
            ) {

                data.messages.forEach(
                    function (message) {

                        addMessage(message);

                    }
                );

            }


        } catch (error) {

            console.error(
                "Provider chat fetch error:",
                error
            );

        }

    }


    /*
    |-----------------------------------------------------
    | AUTO REFRESH EVERY 1 SECOND
    |-----------------------------------------------------
    */

    setInterval(
        fetchMessages,
        1000
    );


    /*
    |-----------------------------------------------------
    | INITIAL SCROLL
    |-----------------------------------------------------
    */

    messagesBox.scrollTop =
        messagesBox.scrollHeight;

});
