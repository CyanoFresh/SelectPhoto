var photos;

(function ($, window, document, undefined) {

    'use strict';

    var defaults = {
        SelectPhoto: false
    };

    var SelectPhoto = function (element) {

        // You can access all lightgallery variables and functions like this.
        this.core = $(element).data('lightGallery');

        this.$el = $(element);
        this.core.s = $.extend({}, defaults, this.core.s);

        this.init();

        return this;
    };

    SelectPhoto.prototype.init = function () {
        this.core.$outer.find('.lg-toolbar').append('<div class="SelectPhoto-controls">' +
            '<a href="#" class="btn btn-primary finish-select"><i class="far fa-envelope hidden-xs"></i> Завершить</a>' +
            '<a href="#" class="btn btn-success toggle-photo"><i class="far fa-check"></i> Выбрать</a>' +
            '</div>');

        $('.toggle-photo').on('click.lg', function (e) {
            e.preventDefault();

            let index = $(this).parent().data('index');

            if (photos[index].selected) {
                photos[index].selected = false;
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрать');
            } else {
                photos[index].selected = true;
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрано');
            }

            $.post(selectPhotoUrl, {id: photos[index]['photo-id']}, function (data) {
                console.log(data);
            }, 'json');
        });

        this.core.$el.on('onAfterSlide.lg.tm', function (event, prevIndex, index) {
            let $SelectPhoto = $('.SelectPhoto-controls');

            $SelectPhoto.data('index', index);

            if (photos[index].selected) {
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрано');
            } else {
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрать');
            }
        });
    };

    SelectPhoto.prototype.destroy = function () {

    };

    $.fn.lightGallery.modules.SelectPhoto = SelectPhoto;
})(jQuery, window, document);

$('.btn-hide-forever').click(function (e) {
    localStorage.hideHelp = true;
});

$(document).ready(function () {
    $('.gallery').lightGallery({
        dynamic: true,
        dynamicEl: photos,
        download: false,
        loop: false,
        SelectPhoto: true,
        share: false,
        zoom: false,
        closable: false,
        autoplayControls: false,
        hideBarsDelay: 1000 * 60 * 6,
    });

    if (!localStorage || !localStorage.hideHelp) {
        $('#myModal').modal('show');
    }
});
