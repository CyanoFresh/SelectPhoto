/**
 * @param {string} title
 * @param {String} body
 * @param {Function} [before]
 */
function showDialog(title, body, before) {
    const $dialogModal = $('#dialogModal');

    $dialogModal.find('.modal-title').html(title);
    $dialogModal.find('.modal-body').html(body);

    if (before && typeof before === 'function') {
        before($dialogModal);
    }

    $dialogModal.modal('show');
}

$(document).ready(function () {
    $('#gallery')
        .lightGallery({
            dynamic: true,
            dynamicEl: LINK.photos,
            download: false,
            loop: false,
            SelectPhoto: true,
            closable: false,
            hideBarsDelay: 1000 * 60 * 6,
        })
        .on('onAfterOpen.lg', function () {
            let $loader = $('.loader');

            $loader.fadeOut('slow', function () {
                $loader.remove();
            });
        });

    $('#helpModal').modal('show');

    if (LINK.options.disableRightClick) {
        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
        });

        $(document).keydown(function (event) {
            if (event.keyCode === 123 || (event.ctrlKey && event.shiftKey && event.keyCode === 73)) { // Prevent F12 and Ctrl+Shift+I
                return false;
            }
        });
    }
});
