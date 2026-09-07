document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('leave-form');

    const leaveType =
        document.getElementById('leave_type_id');

    const startDate =
        document.getElementById('start_date');

    const endDate =
        document.getElementById('end_date');

    const totalDays =
        document.getElementById('total_days');

    const availability =
        document.getElementById('leave-availability');

    const endDateInfo =
        document.getElementById('end-date-info');

    const canvas =
        document.getElementById('signature-pad');

    const signatureInput =
        document.getElementById('signature');

    const clearButton =
        document.getElementById('clear-signature');


    /*
    |--------------------------------------------------------------------------
    | HOLIDAYS
    |--------------------------------------------------------------------------
    */

    const holidays =
        Array.isArray(window.leaveHolidays)
            ? window.leaveHolidays
            : [];


    const holidaySet =
        new Set();


    holidays.forEach(function (holiday) {

        if (!holiday) {
            return;
        }

        let date = null;


        if (typeof holiday === 'string') {

            date = holiday;

        }


        else if (
            typeof holiday === 'object' &&
            holiday.date
        ) {

            date = holiday.date;

        }


        if (date) {

            holidaySet.add(
                String(date).substring(0, 10)
            );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | DATE HELPERS
    |--------------------------------------------------------------------------
    */

    function parseDate(value) {

        if (!value) {
            return null;
        }


        const parts =
            value.split('-');


        if (parts.length !== 3) {
            return null;
        }


        const year =
            Number(parts[0]);

        const month =
            Number(parts[1]) - 1;

        const day =
            Number(parts[2]);


        const date =
            new Date(
                year,
                month,
                day
            );


        if (
            isNaN(date.getTime())
        ) {

            return null;

        }


        return date;

    }


    function formatDate(date) {

        if (!date) {
            return '';
        }


        const year =
            date.getFullYear();


        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, '0');


        const day =
            String(
                date.getDate()
            ).padStart(2, '0');


        return (
            year +
            '-' +
            month +
            '-' +
            day
        );

    }


    function cloneDate(date) {

        return new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDate()
        );

    }


    /*
    |--------------------------------------------------------------------------
    | WEEKEND
    |--------------------------------------------------------------------------
    */

    function isWeekend(date) {

        const day =
            date.getDay();


        return (
            day === 0 ||
            day === 6
        );

    }


    /*
    |--------------------------------------------------------------------------
    | HOLIDAY
    |--------------------------------------------------------------------------
    */

    function isHoliday(date) {

        return holidaySet.has(
            formatDate(date)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | WORKING DAY
    |--------------------------------------------------------------------------
    */

    function isWorkingDay(date) {

        return (
            !isWeekend(date) &&
            !isHoliday(date)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GET REMAINING
    |--------------------------------------------------------------------------
    */

    function getRemainingDays() {

        if (!leaveType) {
            return 0;
        }


        const selectedOption =
            leaveType.options[
                leaveType.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            return 0;

        }


        const value =
            selectedOption.dataset.availableDays;


        const remaining =
            parseInt(
                value || '0',
                10
            );


        if (
            isNaN(remaining) ||
            remaining <= 0
        ) {

            return 0;

        }


        return remaining;

    }


    /*
    |--------------------------------------------------------------------------
    | COUNT WORKING DAYS
    |--------------------------------------------------------------------------
    */

    function countWorkingDays(
        start,
        end
    ) {

        if (
            !start ||
            !end ||
            end < start
        ) {

            return 0;

        }


        const current =
            cloneDate(start);


        let total = 0;


        while (
            current <= end
        ) {

            if (
                isWorkingDay(current)
            ) {

                total++;

            }


            current.setDate(
                current.getDate() + 1
            );

        }


        return total;

    }


    /*
    |--------------------------------------------------------------------------
    | FIND MAXIMUM END DATE
    |--------------------------------------------------------------------------
    |
    | remaining = JUMLAH HARI KERJA YANG BOLEH DIGUNAKAN
    |
    | Contoh:
    |
    | remaining = 15
    |
    | maka tanggal maksimum adalah
    | tanggal kerja ke-15 dari start date.
    |
    */

    function getMaximumEndDate(
        start,
        remaining
    ) {

        if (
            !start ||
            remaining <= 0
        ) {

            return null;

        }


        const current =
            cloneDate(start);


        let workingDays = 0;


        /*
        | Safety limit
        */

        for (
            let i = 0;
            i < 5000;
            i++
        ) {

            if (
                isWorkingDay(current)
            ) {

                workingDays++;

            }


            /*
            | Hari kerja ke-N
            */

            if (
                workingDays === remaining
            ) {

                return cloneDate(current);

            }


            current.setDate(
                current.getDate() + 1
            );

        }


        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTAL
    |--------------------------------------------------------------------------
    */

    function calculateLeaveDays() {

        if (
            !startDate ||
            !endDate ||
            !totalDays
        ) {

            return;

        }


        const start =
            parseDate(
                startDate.value
            );


        const end =
            parseDate(
                endDate.value
            );


        if (
            !start ||
            !end
        ) {

            totalDays.value = '';

            totalDays.placeholder =
                'Pilih tanggal terlebih dahulu';

            return;

        }


        if (
            end < start
        ) {

            totalDays.value = '';

            totalDays.placeholder =
                'Tanggal tidak valid';

            return;

        }


        const remaining =
            getRemainingDays();


        const maximum =
            getMaximumEndDate(
                start,
                remaining
            );


        if (
            !maximum ||
            end > maximum
        ) {

            totalDays.value = '';

            totalDays.placeholder =
                'Melebihi hak cuti';

            return;

        }


        const days =
            countWorkingDays(
                start,
                end
            );


        totalDays.value =
            days + ' Hari';

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    function updateAvailability() {

        if (!availability) {
            return;
        }


        if (
            !leaveType ||
            !leaveType.value
        ) {

            availability.textContent =
                'Pilih jenis cuti untuk melihat saldo yang tersedia.';

            return;

        }


        const remaining =
            getRemainingDays();


        if (remaining <= 0) {

            availability.textContent =
                'Saldo cuti Anda sudah habis.';

            return;

        }


        availability.textContent =
            'Saldo cuti tersedia: ' +
            remaining +
            ' hari kerja.';

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE END DATE LIMIT
    |--------------------------------------------------------------------------
    */

    function updateEndDateLimit() {

        if (
            !startDate ||
            !endDate
        ) {

            return;

        }


        /*
        | Tidak ada start date
        */

        if (!startDate.value) {

            endDate.min = '';
            endDate.max = '';

            endDate.value = '';

            if (endDateInfo) {

                endDateInfo.textContent =
                    'Pilih tanggal mulai terlebih dahulu.';

            }

            calculateLeaveDays();

            return;

        }


        const start =
            parseDate(
                startDate.value
            );


        if (!start) {
            return;
        }


        const remaining =
            getRemainingDays();


        /*
        |--------------------------------------------------------------------------
        | SALDO HABIS
        |--------------------------------------------------------------------------
        */

        if (
            remaining <= 0
        ) {

            endDate.min =
                formatDate(start);

            endDate.max =
                formatDate(start);


            endDate.value = '';


            if (endDateInfo) {

                endDateInfo.textContent =
                    'Saldo cuti Anda sudah habis.';

            }


            calculateLeaveDays();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CARI TANGGAL MAKSIMUM
        |--------------------------------------------------------------------------
        */

        const maximum =
            getMaximumEndDate(
                start,
                remaining
            );


        if (!maximum) {
            return;
        }


        const minDate =
            formatDate(start);


        const maxDate =
            formatDate(maximum);


        /*
        |--------------------------------------------------------------------------
        | NATIVE DATE INPUT
        |--------------------------------------------------------------------------
        |
        | Ini penting.
        |
        | min = start date
        | max = tanggal kerja terakhir
        |
        | Browser akan men-disable tanggal
        | di luar range.
        |
        */

        endDate.min =
            minDate;

        endDate.max =
            maxDate;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI VALUE LAMA
        |--------------------------------------------------------------------------
        */

        if (
            endDate.value
        ) {

            const selected =
                parseDate(
                    endDate.value
                );


            if (
                !selected ||
                selected < start ||
                selected > maximum ||
                !isWorkingDay(selected)
            ) {

                endDate.value = '';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | INFO
        |--------------------------------------------------------------------------
        */

        if (endDateInfo) {

            endDateInfo.textContent =
                'Hak cuti ' +
                remaining +
                ' hari kerja. ' +
                'Maksimal sampai ' +
                maxDate +
                '.';

        }


        calculateLeaveDays();

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE END DATE
    |--------------------------------------------------------------------------
    */

    function validateEndDate() {

        if (
            !startDate ||
            !endDate
        ) {

            return true;

        }


        if (
            !startDate.value ||
            !endDate.value
        ) {

            return true;

        }


        const start =
            parseDate(
                startDate.value
            );


        const end =
            parseDate(
                endDate.value
            );


        const remaining =
            getRemainingDays();


        const maximum =
            getMaximumEndDate(
                start,
                remaining
            );


        if (
            !start ||
            !end ||
            !maximum
        ) {

            return false;

        }


        /*
        | Sebelum start date
        */

        if (
            end < start
        ) {

            return false;

        }


        /*
        | Melebihi remaining
        */

        if (
            end > maximum
        ) {

            return false;

        }


        /*
        | Weekend / holiday
        */

        if (
            !isWorkingDay(end)
        ) {

            return false;

        }


        /*
        | Jumlah hari kerja
        */

        const workingDays =
            countWorkingDays(
                start,
                end
            );


        if (
            workingDays > remaining
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | START DATE CHANGE
    |--------------------------------------------------------------------------
    */

    if (startDate) {

        startDate.addEventListener(
            'change',
            function () {

                /*
                | Reset end date
                */

                endDate.value = '';


                /*
                | Hitung ulang range
                */

                updateEndDateLimit();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LEAVE TYPE CHANGE
    |--------------------------------------------------------------------------
    */

    if (leaveType) {

        leaveType.addEventListener(
            'change',
            function () {

                updateAvailability();


                /*
                | Reset end date
                */

                if (endDate) {

                    endDate.value = '';

                }


                /*
                | Hitung ulang batas
                */

                updateEndDateLimit();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | END DATE CHANGE
    |--------------------------------------------------------------------------
    */

    if (endDate) {

        endDate.addEventListener(
            'change',
            function () {

                /*
                | Pastikan batas selalu terbaru
                */

                updateEndDateLimit();


                /*
                | Validasi pilihan
                */

                if (
                    endDate.value &&
                    !validateEndDate()
                ) {

                    endDate.value = '';

                    calculateLeaveDays();

                    alert(
                        'Tanggal selesai tidak valid.\n\n' +
                        'Tanggal selesai harus berada dalam batas ' +
                        'sisa hak cuti dan harus merupakan hari kerja.'
                    );

                    return;

                }


                calculateLeaveDays();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INPUT MANUAL
        |--------------------------------------------------------------------------
        */

        endDate.addEventListener(
            'input',
            function () {

                if (
                    !endDate.value
                ) {

                    calculateLeaveDays();

                    return;

                }


                if (
                    !validateEndDate()
                ) {

                    endDate.value = '';

                    calculateLeaveDays();

                    return;

                }


                calculateLeaveDays();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                /*
                | Signature ditangani oleh
                | bagian signature pad.
                */


                if (
                    startDate &&
                    endDate &&
                    startDate.value &&
                    endDate.value
                ) {

                    if (
                        !validateEndDate()
                    ) {

                        event.preventDefault();


                        const start =
                            parseDate(
                                startDate.value
                            );


                        const remaining =
                            getRemainingDays();


                        const maximum =
                            getMaximumEndDate(
                                start,
                                remaining
                            );


                        alert(
                            'Tanggal selesai melebihi ' +
                            'sisa hak cuti.\n\n' +
                            'Sisa hak cuti: ' +
                            remaining +
                            ' hari kerja.\n' +
                            'Tanggal maksimum: ' +
                            (
                                maximum
                                    ? formatDate(maximum)
                                    : '-'
                            )
                        );


                        endDate.value = '';


                        calculateLeaveDays();


                        return;

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL DATE STATE
    |--------------------------------------------------------------------------
    */

    updateAvailability();

    updateEndDateLimit();

    calculateLeaveDays();


    /*
    |--------------------------------------------------------------------------
    | SIGNATURE PAD
    |--------------------------------------------------------------------------
    */

    if (
        canvas &&
        signatureInput
    ) {

        const ctx =
            canvas.getContext('2d');


        let drawing = false;

        let hasSignature = false;


        /*
        |--------------------------------------------------------------------------
        | CANVAS RESIZE
        |--------------------------------------------------------------------------
        */

        function resizeCanvas() {

            const rect =
                canvas.getBoundingClientRect();


            const ratio =
                window.devicePixelRatio || 1;


            let previousSignature = null;


            if (hasSignature) {

                previousSignature =
                    canvas.toDataURL(
                        'image/png'
                    );

            }


            canvas.width =
                Math.max(
                    1,
                    Math.floor(
                        rect.width * ratio
                    )
                );


            canvas.height =
                Math.max(
                    1,
                    Math.floor(
                        rect.height * ratio
                    )
                );


            ctx.setTransform(
                ratio,
                0,
                0,
                ratio,
                0,
                0
            );


            ctx.lineWidth = 2;

            ctx.lineCap =
                'round';

            ctx.lineJoin =
                'round';

            ctx.strokeStyle =
                '#000000';


            ctx.fillStyle =
                '#ffffff';


            ctx.fillRect(
                0,
                0,
                rect.width,
                rect.height
            );


            /*
            | Restore signature
            */

            if (previousSignature) {

                const image =
                    new Image();


                image.onload =
                    function () {

                        ctx.drawImage(
                            image,
                            0,
                            0,
                            rect.width,
                            rect.height
                        );

                    };


                image.src =
                    previousSignature;

            }

        }


        resizeCanvas();


        let resizeTimer;


        window.addEventListener(
            'resize',
            function () {

                clearTimeout(
                    resizeTimer
                );


                resizeTimer =
                    setTimeout(
                        resizeCanvas,
                        150
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | POSITION
        |--------------------------------------------------------------------------
        */

        function getPosition(event) {

            const rect =
                canvas.getBoundingClientRect();


            if (
                event.touches &&
                event.touches.length
            ) {

                return {

                    x:
                        event.touches[0].clientX -
                        rect.left,

                    y:
                        event.touches[0].clientY -
                        rect.top

                };

            }


            return {

                x:
                    event.clientX -
                    rect.left,

                y:
                    event.clientY -
                    rect.top

            };

        }


        /*
        |--------------------------------------------------------------------------
        | START DRAWING
        |--------------------------------------------------------------------------
        */

        function startDrawing(event) {

            event.preventDefault();


            drawing = true;

            hasSignature = true;


            const position =
                getPosition(event);


            ctx.beginPath();


            ctx.moveTo(
                position.x,
                position.y
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DRAW
        |--------------------------------------------------------------------------
        */

        function draw(event) {

            if (!drawing) {
                return;
            }


            event.preventDefault();


            const position =
                getPosition(event);


            ctx.lineTo(
                position.x,
                position.y
            );


            ctx.stroke();

        }


        /*
        |--------------------------------------------------------------------------
        | STOP DRAWING
        |--------------------------------------------------------------------------
        */

        function stopDrawing(event) {

            if (!drawing) {
                return;
            }


            if (event) {
                event.preventDefault();
            }


            drawing = false;


            updateSignature();

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE SIGNATURE
        |--------------------------------------------------------------------------
        */

        function updateSignature() {

            if (!hasSignature) {

                signatureInput.value =
                    '';

                return;

            }


            signatureInput.value =
                canvas.toDataURL(
                    'image/png'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR SIGNATURE
        |--------------------------------------------------------------------------
        */

        function clearSignature() {

            const rect =
                canvas.getBoundingClientRect();


            ctx.clearRect(
                0,
                0,
                rect.width,
                rect.height
            );


            ctx.fillStyle =
                '#ffffff';


            ctx.fillRect(
                0,
                0,
                rect.width,
                rect.height
            );


            hasSignature = false;


            signatureInput.value =
                '';

        }


        /*
        |--------------------------------------------------------------------------
        | MOUSE
        |--------------------------------------------------------------------------
        */

        canvas.addEventListener(
            'mousedown',
            startDrawing
        );


        canvas.addEventListener(
            'mousemove',
            draw
        );


        canvas.addEventListener(
            'mouseup',
            stopDrawing
        );


        canvas.addEventListener(
            'mouseleave',
            stopDrawing
        );


        /*
        |--------------------------------------------------------------------------
        | TOUCH
        |--------------------------------------------------------------------------
        */

        canvas.addEventListener(
            'touchstart',
            startDrawing,
            {
                passive: false
            }
        );


        canvas.addEventListener(
            'touchmove',
            draw,
            {
                passive: false
            }
        );


        canvas.addEventListener(
            'touchend',
            stopDrawing,
            {
                passive: false
            }
        );


        canvas.addEventListener(
            'touchcancel',
            stopDrawing,
            {
                passive: false
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLEAR BUTTON
        |--------------------------------------------------------------------------
        */

        if (clearButton) {

            clearButton.addEventListener(
                'click',
                clearSignature
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SIGNATURE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    updateSignature();


                    if (
                        !signatureInput.value ||
                        signatureInput.value.length < 100
                    ) {

                        event.preventDefault();


                        alert(
                            'Silakan bubuhkan tanda tangan terlebih dahulu.'
                        );

                    }

                }
            );

        }

    }

});

function openLeaveBalanceModal() {

    const modal = document.getElementById('leaveBalanceModal');

    if (!modal) {
        return;
    }

    modal.classList.add('active');

    modal.setAttribute('aria-hidden', 'false');

    document.body.style.overflow = 'hidden';

}


function closeLeaveBalanceModal() {

    const modal = document.getElementById('leaveBalanceModal');

    if (!modal) {
        return;
    }

    modal.classList.remove('active');

    modal.setAttribute('aria-hidden', 'true');

    document.body.style.overflow = '';

}


/*
|--------------------------------------------------------------------------
| CLOSE WITH ESC
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {

        closeLeaveBalanceModal();

    }

});