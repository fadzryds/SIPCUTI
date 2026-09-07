document.addEventListener("DOMContentLoaded", () => {

    const hamburger = document.getElementById("hamburger");
    const mobileMenu = document.getElementById("mobileMenu");
    const overlay = document.getElementById("menuOverlay");
    const navbar = document.querySelector(".navbar");

    if (!hamburger || !mobileMenu) return;

    function openMenu() {

        hamburger.classList.add("active");
        mobileMenu.classList.add("show");
        overlay.classList.add("show");

        document.body.classList.add("menu-open");

    }

    function closeMenu() {

        hamburger.classList.remove("active");
        mobileMenu.classList.remove("show");
        overlay.classList.remove("show");

        document.body.classList.remove("menu-open");

    }

    hamburger.addEventListener("click", function (e) {

        e.stopPropagation();

        if (mobileMenu.classList.contains("show")) {

            closeMenu();

        } else {

            openMenu();

        }

    });

    overlay.addEventListener("click", closeMenu);

    document.querySelectorAll("#mobileMenu a").forEach(link => {

        link.addEventListener("click", closeMenu);

    });

    window.addEventListener("resize", () => {

        if (window.innerWidth > 900) {

            closeMenu();

        }

    });

    window.addEventListener("scroll", () => {

        if (window.scrollY > 10) {

            navbar.classList.add("scrolled");

        } else {

            navbar.classList.remove("scrolled");

        }

    });

});