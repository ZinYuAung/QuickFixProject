document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('appAlertModal');

    if (!modal) return;

    const title = document.getElementById('appAlertTitle');
    const message = document.getElementById('appAlertMessage');
    const okButton = document.getElementById('appAlertOk');
    const cancelButton = document.getElementById('appAlertCancel');

    let confirmResolver = null;
    let confirmMode = false;

    function closeAlert() {
        modal.style.display = 'none';
        okButton.textContent = 'OK';
        cancelButton.style.display = 'none';
    }

    // Existing alert: show message and OK button.
    window.showAppAlert = function (text, heading = 'Notice') {
        title.textContent = heading;
        message.textContent = String(text);

        confirmMode = false;
        title.focus?.();

        closeAlert();
        modal.style.display = 'flex';
        okButton.focus();
    };

    // Confirmation: returns true for Confirm, false for Cancel.
    window.showAppConfirm = function (
        text,
        heading = 'Confirm Action',
        confirmText = 'Confirm'
    ) {
        // Avoid leaving an earlier confirmation unresolved.
        if (confirmResolver) {
            confirmResolver(false);
            confirmResolver = null;
        }

        title.textContent = heading;
        message.textContent = String(text);
        okButton.textContent = confirmText;
        cancelButton.style.display = 'inline-block';
        confirmMode = true;

        modal.style.display = 'flex';
        cancelButton.focus();

        return new Promise(function (resolve) {
            confirmResolver = resolve;
        });
    };

    function finishConfirmation(result) {
        if (!confirmMode) {
            closeAlert();
            return;
        }

        const resolve = confirmResolver;

        confirmResolver = null;
        confirmMode = false;

        closeAlert();

        if (resolve) {
            resolve(result);
        }
    }

    okButton.addEventListener('click', function () {
        if (confirmMode) {
            finishConfirmation(true);
        } else {
            closeAlert();
        }
    });

    cancelButton.addEventListener('click', function () {
        finishConfirmation(false);
    });

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            if (confirmMode) {
                finishConfirmation(false);
            } else {
                closeAlert();
            }
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display !== 'none') {
            if (confirmMode) {
                finishConfirmation(false);
            } else {
                closeAlert();
            }
        }
    });
});
