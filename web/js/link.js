$(document).ready(function () {
    $('#gallery')
        .lightGallery({
            dynamic: true,
            dynamicEl: photos,
            download: false,
            loop: false,
            SelectPhoto: true,
            // pager: true,
            closable: false,
            hideBarsDelay: 1000 * 60 * 6,
        })
        .on('onAfterOpen.lg', function () {
            let $loader = $('.loader');

            $loader.fadeOut('slow', function () {
                $loader.remove();
            });
        });

    // Help window
    if (!localStorage || !localStorage.hideHelpModal) {
        $('#helpModal').modal('show');
    }

    $('.btn-hide-forever').click(function (e) {
        localStorage.hideHelpModal = true;
    });
});
