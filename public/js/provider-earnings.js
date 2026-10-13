document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    |
    | Press Enter in the search box to submit the filter form.
    |
    */

    const searchInput = document.querySelector('.earnings-search');

    if (searchInput) {

        searchInput.addEventListener('keydown', function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                const form = searchInput.closest('form');

                if (form) {
                    form.submit();
                }
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | View Payment
    |--------------------------------------------------------------------------
    |
    | Prevent accidental double-clicks and show loading state.
    |
    */

    const viewButtons =
        document.querySelectorAll('.earning-view-button');

    viewButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            if (button.dataset.clicked === 'true') {
                return;
            }

            button.dataset.clicked = 'true';

            const originalHtml = button.innerHTML;

            button.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

            /*
            | Restore button if navigation does not happen.
            */

            setTimeout(function () {

                if (document.body.contains(button)) {

                    button.innerHTML = originalHtml;

                    button.dataset.clicked = 'false';
                }

            }, 1000);

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Filter Form Loading
    |--------------------------------------------------------------------------
    |
    | Disable the search button while the form is being submitted.
    |
    */

    const filterForm =
        document.querySelector('.earnings-filter-form');

    if (filterForm) {

        filterForm.addEventListener('submit', function () {

            const searchButton =
                filterForm.querySelector(
                    '.earnings-search-button'
                );

            if (searchButton) {

                searchButton.disabled = true;

                searchButton.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i>';

            }

        });

    }

});