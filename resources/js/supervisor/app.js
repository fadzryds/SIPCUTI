document.addEventListener("DOMContentLoaded", () => {

    const sidebar =
        document.getElementById("supervisor-sidebar");

    const toggle =
        document.getElementById("sidebar-toggle");

    const userMenuButton =
        document.getElementById("user-menu-button");

    const userDropdown =
        document.getElementById("user-dropdown");


    /*
    |--------------------------------------------------------------------------
    | SIDEBAR OVERLAY
    |--------------------------------------------------------------------------
    */

    let overlay =
        document.querySelector(
            ".supervisor-sidebar-overlay"
        );

    if (!overlay) {

        overlay =
            document.createElement("div");

        overlay.className =
            "supervisor-sidebar-overlay";

        document.body.appendChild(overlay);
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE SIDEBAR
    |--------------------------------------------------------------------------
    */

    if (toggle && sidebar) {

        toggle.addEventListener(
            "click",
            () => {

                sidebar.classList.toggle("open");

                overlay.classList.toggle(
                    "show"
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE SIDEBAR
    |--------------------------------------------------------------------------
    */

    overlay.addEventListener(
        "click",
        () => {

            if (sidebar) {

                sidebar.classList.remove(
                    "open"
                );

            }

            overlay.classList.remove(
                "show"
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE SIDEBAR WHEN MENU CLICKED
    |--------------------------------------------------------------------------
    */

    if (sidebar) {

        const links =
            sidebar.querySelectorAll(
                ".sidebar-link"
            );

        links.forEach((link) => {

            link.addEventListener(
                "click",
                () => {

                    if (
                        window.innerWidth <= 800
                    ) {

                        sidebar.classList.remove(
                            "open"
                        );

                        overlay.classList.remove(
                            "show"
                        );

                    }

                }
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | USER DROPDOWN
    |--------------------------------------------------------------------------
    */

    if (
        userMenuButton &&
        userDropdown
    ) {

        userMenuButton.addEventListener(
            "click",
            (event) => {

                event.stopPropagation();

                userDropdown.classList.toggle(
                    "show"
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE DROPDOWN OUTSIDE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "click",
        (event) => {

            if (
                userDropdown &&
                !userDropdown.contains(event.target) &&
                userMenuButton &&
                !userMenuButton.contains(event.target)
            ) {

                userDropdown.classList.remove(
                    "show"
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ESC CLOSE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "keydown",
        (event) => {

            if (event.key !== "Escape") {
                return;
            }


            if (userDropdown) {

                userDropdown.classList.remove(
                    "show"
                );

            }


            if (sidebar) {

                sidebar.classList.remove(
                    "open"
                );

            }

            overlay.classList.remove(
                "show"
            );

        }
    );

});