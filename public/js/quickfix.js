document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       GET NAVBAR LINKS
    ===================================================== */

    const navLinks =
        document.querySelectorAll(
            ".quickfix-navbar .nav-link"
        );


    /* =====================================================
       GET SECTIONS
    ===================================================== */

    const sections = [

        document.getElementById("home"),

        document.getElementById("services"),

        document.getElementById("how-it-works"),

        document.getElementById("about")

    ];


    /* =====================================================
       UPDATE ACTIVE NAVBAR
    ===================================================== */

    function updateActiveNav() {

        let currentSection = "home";

        const scrollPosition =
            window.scrollY;


        sections.forEach(function (section) {

            if (!section) {
                return;
            }


            /*
             * Navbar height
             */

            const navbarHeight =
                document.querySelector(
                    ".quickfix-navbar"
                ).offsetHeight;


            /*
             * Section position
             */

            const sectionTop =
                section.offsetTop -
                navbarHeight -
                50;


            /*
             * If current scroll position
             * reaches this section
             */

            if (
                scrollPosition >= sectionTop
            ) {

                currentSection =
                    section.getAttribute("id");

            }

        });


        /* =================================================
           REMOVE ACTIVE FROM ALL LINKS
        ================================================= */

        navLinks.forEach(function (link) {

            link.classList.remove("active");

        });


        /* =================================================
           ADD ACTIVE TO CURRENT SECTION
        ================================================= */

        const activeLink =
            document.querySelector(
                '.quickfix-navbar .nav-link[href="#' +
                currentSection +
                '"]'
            );


        if (activeLink) {

            activeLink.classList.add("active");

        }

    }


    /* =====================================================
       RUN WHEN PAGE LOADS
    ===================================================== */

    updateActiveNav();


    /* =====================================================
       RUN WHEN USER SCROLLS
    ===================================================== */

    window.addEventListener(
        "scroll",
        updateActiveNav
    );


    /* =====================================================
       NAVBAR CLICK
    ===================================================== */

    navLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function (event) {


                const targetId =
                    this.getAttribute("href");


                /*
                 * Only handle #home,
                 * #services, etc.
                 */

                if (
                    !targetId ||
                    !targetId.startsWith("#")
                ) {

                    return;

                }


                event.preventDefault();


                const target =
                    document.querySelector(
                        targetId
                    );


                if (!target) {

                    return;

                }


                /*
                 * Get navbar height
                 */

                const navbar =
                    document.querySelector(
                        ".quickfix-navbar"
                    );


                const navbarHeight =
                    navbar.offsetHeight;


                /*
                 * Calculate target position
                 */

                const targetPosition =
                    target.offsetTop -
                    navbarHeight;


                /*
                 * Smooth scroll
                 */

                window.scrollTo({

                    top: targetPosition,

                    behavior: "smooth"

                });


            }
        );

    });


    /* =====================================================
       MOBILE NAVBAR CLOSE
    ===================================================== */

    navLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function () {


                if (
                    window.innerWidth < 992
                ) {


                    const navbar =
                        document.getElementById(
                            "quickfixNavbar"
                        );


                    if (
                        navbar &&
                        navbar.classList.contains(
                            "show"
                        )
                    ) {


                        const collapse =
                            bootstrap.Collapse
                                .getInstance(
                                    navbar
                                );


                        if (collapse) {

                            collapse.hide();

                        }

                    }

                }

            }
        );

    });


});
/* =====================================================
   DARK / LIGHT MODE
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const themeToggle =
        document.getElementById("themeToggle");

    const themeIcon =
        document.getElementById("themeIcon");

    if (!themeToggle || !themeIcon) {
        return;
    }

    /* Get saved theme */
    const savedTheme =
        localStorage.getItem("quickfix-theme");


    /* Apply saved dark mode */
    if (savedTheme === "dark") {

        document.body.classList.add("dark-mode");

        themeIcon.classList.remove("fa-moon");

        themeIcon.classList.add("fa-sun");

        themeToggle.setAttribute(
            "title",
            "Switch to light mode"
        );

    }


    /* Click button */
    themeToggle.addEventListener("click", function () {

        document.body.classList.toggle("dark-mode");


        /* DARK MODE */
        if (
            document.body.classList.contains("dark-mode")
        ) {

            themeIcon.classList.remove("fa-moon");

            themeIcon.classList.add("fa-sun");

            themeToggle.setAttribute(
                "title",
                "Switch to light mode"
            );

            themeToggle.setAttribute(
                "aria-label",
                "Switch to light mode"
            );

            localStorage.setItem(
                "quickfix-theme",
                "dark"
            );

        }


        /* LIGHT MODE */
        else {

            themeIcon.classList.remove("fa-sun");

            themeIcon.classList.add("fa-moon");

            themeToggle.setAttribute(
                "title",
                "Switch to dark mode"
            );

            themeToggle.setAttribute(
                "aria-label",
                "Switch to dark mode"
            );

            localStorage.setItem(
                "quickfix-theme",
                "light"
            );

        }

    });

});