/*
|--------------------------------------------------------------------------
| SIPCUTI MANAGER PANEL
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", () => {

    /*
    |--------------------------------------------------------------------------
    | Live Clock
    |--------------------------------------------------------------------------
    */

    const clock = document.getElementById("liveClock");

    function updateClock() {

        if (!clock) return;

        const now = new Date();

        clock.innerHTML = now.toLocaleTimeString("id-ID", {

            hour: "2-digit",

            minute: "2-digit",

            second: "2-digit",

        });

    }

    updateClock();

    setInterval(updateClock, 1000);

    /*
    |--------------------------------------------------------------------------
    | Dropdown Profile
    |--------------------------------------------------------------------------
    */

    const profileBtn = document.getElementById("profileBtn");

    const dropdown = document.getElementById("dropdownMenu");

    if (profileBtn && dropdown) {

        profileBtn.addEventListener("click", function (e) {

            e.stopPropagation();

            dropdown.classList.toggle("active");

        });

        document.addEventListener("click", function () {

            dropdown.classList.remove("active");

        });

        dropdown.addEventListener("click", function (e) {

            e.stopPropagation();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Sidebar Mobile
    |--------------------------------------------------------------------------
    */

    const sidebar = document.querySelector(".sidebar");

    const sidebarToggle = document.getElementById("sidebarToggle");

    if (sidebarToggle && sidebar) {

        sidebarToggle.addEventListener("click", () => {

            sidebar.classList.toggle("show");

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Ripple Button Effect
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(".btn").forEach(button => {

        button.addEventListener("click", function (e) {

            const circle = document.createElement("span");

            const diameter = Math.max(this.clientWidth, this.clientHeight);

            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;

            circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;

            circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;

            circle.classList.add("ripple");

            const ripple = this.getElementsByClassName("ripple")[0];

            if (ripple) {

                ripple.remove();

            }

            this.appendChild(circle);

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Card Hover Animation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(".summary-card").forEach(card => {

        card.addEventListener("mouseenter", () => {

            card.style.transform = "translateY(-6px)";

        });

        card.addEventListener("mouseleave", () => {

            card.style.transform = "translateY(0)";

        });

    });

});