document.addEventListener("DOMContentLoaded", () => {

    const start = document.getElementById("start_date");
    const end = document.getElementById("end_date");
    const total = document.getElementById("total_days");

    function calculateDays() {

        if (!start.value || !end.value) {
            total.value = "";
            return;
        }

        const startDate = new Date(start.value);
        const endDate = new Date(end.value);

        const diff = endDate - startDate;

        if (diff < 0) {
            total.value = "";
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24)) + 1;

        total.value = days + " Hari";
    }

    start.addEventListener("change", calculateDays);
    end.addEventListener("change", calculateDays);

});