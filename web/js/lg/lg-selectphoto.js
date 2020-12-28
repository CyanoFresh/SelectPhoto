(function ($, window, document) {
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

        if (LINK.options.allowComment) {
            html +=
                '<a href="#" class="btn btn-default btn-round open-comment"><i class="fas fa-comment"></i> <span class="hidden-xs">Комментировать</span></a>'
            ;
        }

        html +=
            '<a href="#" class="btn btn-primary btn-round toggle-photo"><i class="fas fa-check hidden-xs"></i> Выбрать</a>' +
            '<a href="#" class="btn btn-warning btn-round finish-select"><i class="fas fa-cloud-upload hidden-xs"></i> Завершить</a>'
        ;

        html +=
            '</div>' +
            '<div class="action-result"></div>'
        ;

        this.core.$outer.find('.lg-toolbar').append(html);

        this.initSelectedPhotos();

        let $openComment = false;

        if (LINK.options.allowComment) {
            $openComment = $('.open-comment');

            $openComment.popover({
                title: false,
                sanitize: false,
                content: function () {
                    const $this = $(this);
                    const index = $this.parents('.SelectPhoto-controls').data('index');

                    let comment = LINK.photos[index].comment || '';

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
                LINK.photos[index].comment = commentText;

                // Save comment to the backend
                that.commentPhoto(LINK.photos[index]['photo-id'], commentText);
            });
        }

        $('.toggle-photo').on('click.lg', function (e) {
            e.preventDefault();

            const index = $(this).parents('.SelectPhoto-controls').data('index');

            if (LINK.photos[index].selected) {
                LINK.photos[index].selected = false;
                LINK.selectedPhotosCount--;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').removeClass('sp-selected');

                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрать').removeClass('btn-success').addClass('btn-primary');
            } else {
                if (LINK.options.maxPhotos && LINK.selectedPhotosCount >= LINK.options.maxPhotos) {
                    return showDialog('Превышен лимит', '<p>Вы можете выбрать только ' + LINK.options.maxPhotos + ' фото. Отмените выбор других и попробуйте снова</p>');
                }

                LINK.photos[index].selected = true;
                LINK.selectedPhotosCount++;

                that.core.$outer.find('.lg-thumb-item:eq(' + index + ')').addClass('sp-selected');

                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрано').removeClass('btn-primary').addClass('btn-success');
            }

            that.selectPhoto(LINK.photos[index]['photo-id']);
            that.updateSelectedCount();
        });

        $('.finish-select').on('click.lg', function (e) {
            e.preventDefault();

            that.core.destroy();
        });

        this.core.$el.on('onBeforeSlide.lg', function (event, prevIndex, index) {
            const $SelectPhoto = $('.SelectPhoto-controls');

            $SelectPhoto.data('index', index);

            if (LINK.photos[index].selected) {
                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрано').removeClass('btn-primary').addClass('btn-success');
            } else {
                $('.toggle-photo').html('<i class="fas fa-check hidden-xs"></i> Выбрать').removeClass('btn-success').addClass('btn-primary');
            }

            if (LINK.options.allowComment && $openComment) {
                $openComment.popover('hide');
            }
        });

        this.core.$el.on('onBeforeClose.lg', function () {
            that.submitLink();
        });
    };

    SelectPhoto.prototype.initSelectedPhotos = function () {
        const that = this;

        setTimeout(function () {
            let html = LINK.selectedPhotosCount;

            if (LINK.options.maxPhotos !== 0) {
                html += ' / ' + LINK.options.maxPhotos;
            }

            that.core.$outer.find('.lg-toolbar #lg-counter').append(
                '<span id="lg-sp-count">' + html + '</span>'
            );

            // Mark selected thumbnails
            that.core.$outer.find('.lg-thumb-item').each(function (index, element) {
                if (LINK.photos[index].selected) {
                    $(element).addClass('sp-selected');
                }
            });
        }, 100);
    };

    SelectPhoto.prototype.updateSelectedCount = function () {
        let html = LINK.selectedPhotosCount;

        if (LINK.options.maxPhotos !== 0) {
            html += ' / ' + LINK.options.maxPhotos;
        }

        $('#lg-counter #lg-sp-count').html(html);
    };

    SelectPhoto.prototype.submitLink = function (callback) {
        $.post(URL.submitLink, function (data) {
            console.log(data);

            if (!data.ok) {
                return alert('Произошла ошибка. Сообщите фотографу');
            }

            return typeof callback === 'function' ? callback(data) : true;
        }, 'json');
    };

    SelectPhoto.prototype.selectPhoto = function (photoId, callback) {
        $.post(URL.selectPhoto, {id: photoId}, function (data) {
            console.log(data);

            if (!data.ok) {
                return alert('Произошла ошибка. Сообщите фотографу');
            }

            return typeof callback === 'function' ? callback(data) : true;
        }, 'json');
    };

    SelectPhoto.prototype.commentPhoto = function (photoId, text, callback) {
        $.post(URL.commentPhoto, {id: photoId, text: text}, function (data) {
            console.log(data);

            if (!data.ok) {
                return alert('Произошла ошибка. Сообщите фотографу');
            }

            // Show notification
            $('.action-result').html('<i class="far fa-check"></i> Комментарий сохранен').fadeIn(function () {
                let $el = $(this);

                setTimeout(function () {
                    $el.fadeOut();
                }, 1000);
            });

            return typeof callback === 'function' ? callback(data) : true;
        }, 'json');
    };

    SelectPhoto.prototype.destroy = function () {
    };

    $.fn.lightGallery.modules.SelectPhoto = SelectPhoto;
})(jQuery, window, document);
