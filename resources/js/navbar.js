/*
|--------------------------------------------------------------------------
| SIPCUTI Navbar
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function () {

    const hamburger = document.getElementById("hamburger");
    const mobileMenu = document.getElementById("mobileMenu");
    const navbar = document.querySelector(".navbar");

    /*
    |--------------------------------------------------------------------------
    | Toggle Mobile Menu
    |--------------------------------------------------------------------------
    */

    hamburger.addEventListener("click", function () {

        hamburger.classList.toggle("active");

        mobileMenu.classList.toggle("show");

    });

    /*
    |--------------------------------------------------------------------------
    | Close menu when click outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener("click", function (e) {

        if (
            !hamburger.contains(e.target) &&
            !mobileMenu.contains(e.target)
        ) {

            hamburger.classList.remove("active");

            mobileMenu.classList.remove("show");

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Navbar Scroll Effect
    |--------------------------------------------------------------------------
    */

    window.addEventListener("scroll", function () {

        if (window.scrollY > 20) {

            navbar.style.boxShadow =
                "0 15px 40px rgba(15,23,42,.12)";

            navbar.style.background =
                "rgba(255,255,255,.96)";

        } else {

            navbar.style.boxShadow =
                "0 10px 35px rgba(15,23,42,.08)";

            navbar.style.background =
                "rgba(255,255,255,.90)";
        }

    });

});