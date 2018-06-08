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

        this.core = $(element).data('lightGallery');

        this.$el = $(element);
        this.core.s = $.extend({}, defaults, this.core.s);

        this.init();

        return this;
    };

    SelectPhoto.prototype.init = function () {
        const that = this;
        let html = '<div class="SelectPhoto-controls">';

        if (allowComment) {
            html +=
                '<a href="#" class="btn btn-default btn-round open-comment"><i class="fas fa-comment"></i> <span class="hidden-xs">Комментировать</span></a>'
            ;
        }

        html +=
            '<a href="#" class="btn btn-success btn-round toggle-photo"><i class="fas fa-check hidden-xs"></i> Выбрать</a>' +
            '<a href="#" class="btn btn-warning btn-round finish-select"><i class="fas fa-cloud-upload hidden-xs"></i> Завершить</a>'
        ;

        html +=
            '</div>' +
            '<div class="action-result"></div>'
        ;

        this.core.$outer.find('.lg-toolbar').append(html);

        setTimeout(function () {
            // Selected photos counter
            that.core.$outer.find('.lg-toolbar #lg-counter').append(
                '<span id="lg-sp-count">' + selectedPhotosCount + '</span>'
            );

            // Mark selected thumbnails
            that.core.$outer.find('.lg-thumb-item').each(function (index, element) {
                if (photos[index].selected) {
                    $(element).addClass('sp-selected');
                }
            });
        }, 100);

        let $openComment = false;

        if (allowComment) {
            $openComment = $('.open-comment');

            $openComment.popover({
                title: false,
                content: function () {
                    const $this = $(this);
                    const index = $this.parents('.SelectPhoto-controls').data('index');

                    let comment = photos[index].comment || '';

                    return '<form id="comment-photo-form" data-index="' + index + '">' +
                        '<div class="form-group">' +
                        '<textarea class="form-control" placeholder="Введите комментарий..." id="comment-photo-text">' + comment + '</textarea>' +
                        '</div>' +
                        '<button class="btn btn-primary btn-block" type="submit"><i class="fas fa-reply"></i> Отправить</button>' +
                        '</form>';
                },
                html: true,
                placement: 'bottom',
            });

            $('body').on('submit', '#comment-photo-form', function (e) {
                e.preventDefault();

                const commentText = $('#comment-photo-text').val();
                const index = $(this).data('index');

                if (commentText === '') {
                    return false;
                }

                // Close popover
                $openComment.trigger('click');

                // Update local comment
                photos[index].comment = commentText;

                // Save comment to the backend
                that.commentPhoto(photos[index]['photo-id'], commentText);
            });
        }

        $('.toggle-photo').on('click.lg', function (e) {
            e.preventDefault();

            const index = $(this).parents('.SelectPhoto-controls').data('index');

            if (photos[index].selected) {
                photos[index].selected = false;
                selectedPhotosCount--;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').removeClass('sp-selected');

                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрать').removeClass('btn-primary').addClass('btn-success');
            } else {
                photos[index].selected = true;
                selectedPhotosCount++;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').addClass('sp-selected');

                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрано').removeClass('btn-success').addClass('btn-primary');
            }

            that.selectPhoto(photos[index]['photo-id']);
            that.updateSelectedCount();
        });

        $('.finish-select').on('click.lg', function (e) {
            e.preventDefault();

            that.core.destroy();
        });

        this.core.$el.on('onBeforeSlide.lg', function (event, prevIndex, index) {
            const $SelectPhoto = $('.SelectPhoto-controls');

            $SelectPhoto.data('index', index);

            if (photos[index].selected) {
                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрано').removeClass('btn-success').addClass('btn-primary');
            } else {
                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрать').removeClass('btn-primary').addClass('btn-success');
            }

            if (allowComment && $openComment) {
                $openComment.popover('hide');
            }
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
