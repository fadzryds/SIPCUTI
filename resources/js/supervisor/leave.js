document.addEventListener('DOMContentLoaded', () => {

    const canvas = document.getElementById('signature-pad');

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');

    let drawing = false;

    function resizeCanvas() {

        const ratio = Math.max(
            window.devicePixelRatio || 1,
            1
        );

        const rect = canvas.getBoundingClientRect();

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        ctx.scale(ratio, ratio);

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

    }

    resizeCanvas();

    window.addEventListener(
        'resize',
        resizeCanvas
    );


    function getPosition(event) {

        const rect =
            canvas.getBoundingClientRect();

        if (event.touches) {

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


    function startDrawing(event) {

        drawing = true;

        const position =
            getPosition(event);

        ctx.beginPath();

        ctx.moveTo(
            position.x,
            position.y
        );

        event.preventDefault();
    }


    function draw(event) {

        if (!drawing) {
            return;
        }

        const position =
            getPosition(event);

        ctx.lineTo(
            position.x,
            position.y
        );

        ctx.stroke();

        event.preventDefault();
    }


    function stopDrawing() {

        drawing = false;

        ctx.closePath();
    }


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


    canvas.addEventListener(
        'touchstart',
        startDrawing,
        { passive: false }
    );

    canvas.addEventListener(
        'touchmove',
        draw,
        { passive: false }
    );

    canvas.addEventListener(
        'touchend',
        stopDrawing
    );


    /*
    |--------------------------------------------------------------------------
    | CLEAR SIGNATURE
    |--------------------------------------------------------------------------
    */

    const clearButton =
        document.getElementById(
            'clear-signature'
        );

    if (clearButton) {

        clearButton.addEventListener(
            'click',
            () => {

                ctx.clearRect(
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    const approveForm =
        document.getElementById(
            'approve-form'
        );

    if (approveForm) {

        approveForm.addEventListener(
            'submit',
            function (event) {

                const signature =
                    canvas.toDataURL(
                        'image/png'
                    );

                if (
                    !signature ||
                    signature ===
                    'data:image/png;base64,'
                ) {

                    event.preventDefault();

                    alert(
                        'Silakan isi tanda tangan terlebih dahulu.'
                    );

                    return;
                }

                document.getElementById(
                    'approve-signature'
                ).value = signature;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REJECT PANEL
    |--------------------------------------------------------------------------
    */

    const openReject =
        document.getElementById(
            'open-reject'
        );

    const rejectPanel =
        document.getElementById(
            'reject-panel'
        );

    const cancelReject =
        document.getElementById(
            'cancel-reject'
        );


    if (
        openReject &&
        rejectPanel
    ) {

        openReject.addEventListener(
            'click',
            () => {

                rejectPanel.style.display =
                    'block';

                rejectPanel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

            }
        );

    }


    if (
        cancelReject &&
        rejectPanel
    ) {

        cancelReject.addEventListener(
            'click',
            () => {

                rejectPanel.style.display =
                    'none';

            }
        );

    }

});