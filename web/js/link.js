/**
 * From backend
 *
 * @var string selectPhotoUrl
 * @var string commentPhotoLink
 * @var string submitLinkUrl
 */

$(document).ready(function () {
    $('#gallery')
        .lightGallery({
            dynamic: true,
            dynamicEl: photos,
            download: false,
            loop: false,
            SelectPhoto: true,
            pager: true,
            closable: false,
            hideBarsDelay: 1000 * 60 * 6,
        })
        .on('onAfterOpen.lg', function () {
            $('.loader').fadeOut('slow', function () {
                $(this).remove();
            });
        })
        .on('onBeforeClose.lg', function () {
            submitLink();
        });

    // Help window
    if (!localStorage || !localStorage.hideHelpModal) {
        $('#helpModal').modal('show');
    }

    $('.btn-hide-forever').click(function (e) {
        localStorage.hideHelpModal = true;
    });
});

function submitLink() {
    $.post(submitLinkUrl, function (data) {
        console.log(data);

        if (!data.ok) {
            alert('Произошла ошибка. Сообщите фотографу');
        }
    }, 'json');
}

function selectPhoto(photoId) {
    $.post(selectPhotoUrl, {id: photoId}, function (data) {
        console.log(data);

        if (!data.ok) {
            alert('Произошла ошибка. Сообщите фотографу');
        }
    }, 'json');
}

function commentPhoto(photoId, text) {
    console.log(photoId, text);
    $.post(commentPhotoUrl, {id: photoId, text: text}, function (data) {
        console.log(data);

        if (!data.ok) {
            alert('Произошла ошибка. Сообщите фотографу');
        }
    }, 'json');
}
