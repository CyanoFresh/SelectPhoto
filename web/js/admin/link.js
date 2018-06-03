Dropzone.options.dropzone = {
    paramName: 'LinkUploadForm[file]',
    acceptedFiles: 'image/jpeg,image/png',
    error: function (file, message) {
        console.log(message);
        $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(message);
    },
};

function copy(id) {
    /* Select the text field */
    document.getElementById(id).select();

    /* Copy the text inside the text field */
    document.execCommand("copy");
}


$('.photo').click(function (e) {
    e.preventDefault();

    const $photo = $(this);

    $.post(deletePhotoUrl, {id: $photo.data('id')}, function (res) {
        console.log(res);

        if (!res.ok) {
            return alert('Ошибка при удалении фото');
        }

        $photo.fadeOut(function () {
            $photo.remove();
        });
    }, 'json');
});
