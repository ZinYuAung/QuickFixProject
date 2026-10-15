console.log("CUSTOMER CHAT JS LOADED");

/* =====================================================
   QUICKFIX CUSTOMER CHAT
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const searchInput =
        document.getElementById("customerChatSearch");

    const chatUsers =
        document.querySelectorAll(".customer-chat-user");

    const form =
        document.querySelector(".customer-chat-input");

    const messageInput =
        document.getElementById("customerMessageInput");

    const sendButton =
        document.getElementById("customerChatSend");

    const messagesBox =
        document.getElementById("customerChatMessages");

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

    const customerUserId =
        Number(window.customerUserId);

        /* =====================================================
   UNREAD CHAT COUNTS
===================================================== */

async function fetchUnreadCounts() {

    try {

        const response =
            await fetch(
                "/customer/chats/unread-counts",
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

const sidebarBadge =
    document.getElementById(
        "customerSidebarChatUnread"
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

        chatUsers.forEach(function (user) {

            const providerId =
                user.dataset.providerUserId;

            const unreadCount =
                Number(counts[providerId] || 0);

            let badge =
                user.querySelector(
                    ".customer-chat-unread"
                );

            if (unreadCount > 0) {

                if (!badge) {

                    badge =
                        document.createElement("span");

                    badge.className =
                        "customer-chat-unread";

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
        });

    } catch (error) {

        console.error(
            "Customer unread count error:",
            error
        );
    }
}
    /* =====================================================
       UNREAD COUNT AUTO REFRESH
    ===================================================== */

    fetchUnreadCounts();

    setInterval(
        fetchUnreadCounts,
        1000
    );
    /* =====================================================
       SEARCH
    ===================================================== */

    if (searchInput) {

        searchInput.addEventListener("input", function () {

            const searchValue =
                this.value.toLowerCase().trim();

            chatUsers.forEach(function (user) {

                const name =
                    user
                        .getAttribute("data-name")
                        .toLowerCase();

                user.style.display =
                    name.includes(searchValue)
                        ? "flex"
                        : "none";
            });
        });
    }

    /* =====================================================
       NO ACTIVE CHAT
    ===================================================== */

    if (!messagesBox) {
        return;
    }

    /* =====================================================
       ADD MESSAGE TO CHAT
    ===================================================== */

    function addMessage(message) {

        if (!message || !message.id) {
            return;
        }

        const existingMessage =
            messagesBox.querySelector(
                `[data-message-id="${message.id}"]`
            );

        if (existingMessage) {
            return;
        }

        const emptyMessage =
            messagesBox.querySelector(
                ".customer-chat-empty"
            );

        if (emptyMessage) {
            emptyMessage.remove();
        }

        const messageWrapper =
            document.createElement("div");

        messageWrapper.className =
            Number(message.sender_id) === customerUserId
                ? "customer-message customer"
                : "customer-message provider";

        messageWrapper.setAttribute(
            "data-message-id",
            message.id
        );

        const bubble =
            document.createElement("div");

        bubble.className =
            "customer-message-bubble";

        bubble.textContent =
            message.message;

        const time =
            document.createElement("span");

        time.textContent =
            message.time;

        messageWrapper.appendChild(bubble);
        messageWrapper.appendChild(time);

        messagesBox.appendChild(messageWrapper);

        messagesBox.scrollTop =
            messagesBox.scrollHeight;

        console.log(
            "CUSTOMER CHAT NEW MESSAGE DISPLAYED:",
            message
        );
    }

    /* =====================================================
       SEND MESSAGE
    ===================================================== */

    if (form && messageInput) {

        form.addEventListener(
            "submit",
            async function (event) {

                event.preventDefault();

                const message =
                    messageInput.value.trim();

                if (!message) {
                    return;
                }

                messageInput.disabled = true;

                if (sendButton) {
                    sendButton.disabled = true;
                }

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

                    addMessage(data.message);

                    messageInput.value = "";

                    messageInput.focus();

                } catch (error) {

                    console.error(
                        "Customer chat send error:",
                        error
                    );

                    showAppAlert(
                        "Unable to send message.",
                        "Send Failed"
                    );

                } finally {

                    messageInput.disabled = false;

                    if (sendButton) {
                        sendButton.disabled = false;
                    }
                }
            }
        );

        messageInput.addEventListener(
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
    }

    /* =====================================================
       FETCH MESSAGES
    ===================================================== */

    let isFetchingMessages = false;

    async function fetchMessages() {

        const fetchUrl =
            messagesBox.dataset.fetchUrl;

        console.log(
            "CUSTOMER CHAT POLLING:",
            fetchUrl
        );

        if (!fetchUrl) {
            return;
        }

        if (isFetchingMessages) {
            return;
        }

        isFetchingMessages = true;

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
                        },

                        cache: "no-store"
                    }
                );

            if (!response.ok) {

                console.error(
                    "Customer chat fetch failed:",
                    response.status,
                    response.statusText
                );

                return;
            }

            const data =
                await response.json();

            console.log(
                "CUSTOMER CHAT DATA:",
                data
            );

            if (
                !data.success ||
                !Array.isArray(data.messages)
            ) {
                return;
            }

            data.messages.forEach(
                function (message) {

                    addMessage(message);

                }
            );

        } catch (error) {

            console.error(
                "Customer chat fetch error:",
                error
            );

        } finally {

            isFetchingMessages = false;
        }
    }

    /* =====================================================
       AUTO REFRESH
    ===================================================== */

    fetchMessages();

    setInterval(
        fetchMessages,
        1000
    );

    /* =====================================================
       INITIAL SCROLL
    ===================================================== */

    messagesBox.scrollTop =
        messagesBox.scrollHeight;

});