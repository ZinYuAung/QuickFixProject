/* =====================================================
   QUICKFIX CUSTOMER PROFILE
   Profile Page JavaScript
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* =================================================
       PROFILE EDIT FORM
    ================================================= */

    const profileForm =
        document.querySelector(".profile-edit-form");

    const saveButton =
        document.querySelector(".profile-save-button");

    if (profileForm && saveButton) {

        profileForm.addEventListener("submit", function () {

            /*
             * Prevent multiple clicks while submitting.
             */

            saveButton.disabled = true;

            saveButton.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin"></i>
                Saving...
            `;
        });
    }


    /* =================================================
       PHONE INPUT
    ================================================= */

    const phoneInput =
        document.getElementById("phone");

    if (phoneInput) {

        phoneInput.addEventListener("input", function () {

            /*
             * Allow numbers, spaces, +, -, and parentheses.
             */

            this.value = this.value.replace(
                /[^0-9+\-\s()]/g,
                ""
            );
        });
    }


    /* =================================================
       CHANGE PASSWORD BUTTON
    ================================================= */

    const securityButton =
        document.querySelector(".security-button");

    if (securityButton) {

        securityButton.addEventListener("click", function () {

            /*
             * Password feature can be connected later.
             */

            alert(
                "Change Password feature will be available soon."
            );
        });
    }


    /* =================================================
       EDIT PROFILE BUTTON
    ================================================= */

    const editButton =
        document.querySelector(".profile-top-edit");

    if (editButton) {

        editButton.addEventListener("click", function () {

            /*
             * Navigation is handled normally by the link.
             * This section is reserved for future profile
             * specific behavior.
             */

        });
    }


    /* =================================================
       PROFILE IMAGE PREVIEW
    ================================================= */

    const profileImageInput =
        document.getElementById("profile_image");

    const profileImagePreview =
        document.getElementById("profileImagePreview");

    if (profileImageInput && profileImagePreview) {

        profileImageInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            /* Make sure selected file is an image */

            if (!file.type.startsWith("image/")) {

                this.value = "";

                return;
            }

            const imageUrl =
                URL.createObjectURL(file);

            profileImagePreview.innerHTML = `
                <img
                    src="${imageUrl}"
                    alt="Selected profile image"
                >
            `;
        });
    }

});