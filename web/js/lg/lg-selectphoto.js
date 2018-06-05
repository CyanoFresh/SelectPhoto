(function ($, window, document, undefined) {
    /**
     * From backend
     *
     * @var Array photos
     * @var int selectedPhotosCount
     * @var bool allowComment
     * @var string selectPhotoUrl
     * @var string commentPhotoLink
     * @var string submitLinkUrl
     */

    'use strict';

    const defaults = {
        SelectPhoto: false
    };

    const SelectPhoto = function (element) {

        // You can access all lightgallery variables and functions like this.
        this.core = $(element).data('lightGallery');

        this.$el = $(element);
        this.core.s = $.extend({}, defaults, this.core.s);

        this.init();

        return this;
    };

    SelectPhoto.prototype.init = function () {
        const that = this;
        let html = '<form class="SelectPhoto-controls">';

        if (allowComment) {
            html += '<div id="comment-photo-form">' +
                '<div class="input-group">' +
                '<input type="text" class="form-control hidden-xs" placeholder="Введите комментарий...">' +
                '<span class="input-group-btn">' +
                '<button class="btn btn-default hidden-xs" type="submit">Комментировать</button>';
        }

        html += '<a href="#" class="btn btn-success toggle-photo"><i class="far fa-check"></i> Выбрать</a>' +
            '<a href="#" class="btn btn-warning finish-select"><i class="far fa-envelope hidden-xs"></i> Завершить</a>';

        if (allowComment) {
            html += '</span>' +
                '</div>' +
                '</div>';
        }

        html += '</form>' +
            '<div class="action-result"></div>';

        this.core.$outer.find('.lg-toolbar').append(html);

        const $SelectPhotoControls = $('.SelectPhoto-controls');

        setTimeout(function () {
            that.core.$outer.find('.lg-toolbar #lg-counter').append(
                '<span id="lg-sp-count">' + selectedPhotosCount + '</span>'
            );

            that.core.$outer.find('.lg-thumb-item').each(function (index, element) {
                if (photos[index].selected) {
                    $(element).addClass('sp-selected');
                }
            });
        }, 100);

        $SelectPhotoControls.on('submit', function (e) {
            e.preventDefault();

            let index = $SelectPhotoControls.data('index');

            photos[index].comment = $SelectPhotoControls.find('input').val();

            that.commentPhoto(photos[index]['photo-id'], $SelectPhotoControls.find('input').val());
        });

        $('.toggle-photo').on('click.lg', function (e) {
            e.preventDefault();

            let index = $(this).parents('.SelectPhoto-controls').data('index');

            if (photos[index].selected) {
                photos[index].selected = false;
                selectedPhotosCount--;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').removeClass('sp-selected');

                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрать').removeClass('btn-primary').addClass('btn-success');
            } else {
                photos[index].selected = true;
                selectedPhotosCount++;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').addClass('sp-selected');

                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрано').removeClass('btn-success').addClass('btn-primary');
            }

            that.selectPhoto(photos[index]['photo-id']);
            that.updateSelectedCount();
        });

        $('.finish-select').on('click.lg', function (e) {
            e.preventDefault();

            that.core.destroy();
        });

        this.core.$el.on('onAfterSlide.lg.tm', function (event, prevIndex, index) {
            let $SelectPhoto = $('.SelectPhoto-controls');

            $SelectPhoto.data('index', index);

            if (photos[index].selected) {
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрано').removeClass('btn-success').addClass('btn-primary');
            } else {
                $('.toggle-photo').html('<i class="far fa-check"></i> Выбрать').removeClass('btn-primary').addClass('btn-success');
            }

            $SelectPhoto.find('input').val(photos[index].comment);
        });

        this.core.$el.on('onBeforeClose.lg', function () {
            that.submitLink();
        });
    };

    SelectPhoto.prototype.updateSelectedCount = function () {
        $('#lg-counter #lg-sp-count').html(selectedPhotosCount);
    };

    SelectPhoto.prototype.submitLink = function () {
        $.post(submitLinkUrl, function (data) {
            console.log(data);

            if (!data.ok) {
                alert('Произошла ошибка. Сообщите фотографу');
            }
        }, 'json');
    };

    SelectPhoto.prototype.selectPhoto = function (photoId) {
        $.post(selectPhotoUrl, {id: photoId}, function (data) {
            console.log(data);

            if (!data.ok) {
                alert('Произошла ошибка. Сообщите фотографу');
            }
        }, 'json');
    };

    SelectPhoto.prototype.commentPhoto = function (photoId, text) {
        $.post(commentPhotoUrl, {id: photoId, text: text}, function (data) {
            console.log(data);

            if (!data.ok) {
                return alert('Произошла ошибка. Сообщите фотографу');
            }

            $('.action-result').html('<i class="far fa-check"></i> Комментарий сохранен').fadeIn(function () {
                let $el = $(this);

                setTimeout(function () {
                    $el.fadeOut();
                }, 1000);
            });
        }, 'json');
    };

    SelectPhoto.prototype.destroy = function () {
    };

    $.fn.lightGallery.modules.SelectPhoto = SelectPhoto;
})(jQuery, window, document);
