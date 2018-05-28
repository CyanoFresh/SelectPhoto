// const selectphotoDefaults = {
//     selectphoto: false,
// };
//
// const SelectPhotoPlugin = function (element) {
//
//     this.el = element;
//
//     // You can access all lightgallery variables and functions like this.
//     this.core = window.lgData[this.el.getAttribute('lg-uid')];
//     this.core.s = Object.assign({}, selectphotoDefaults, this.core.s);
//
//     if (this.core.s.selectphoto) {
//         this.init();
//     }
//
//     return this;
// };
//
// SelectPhotoPlugin.prototype.init = function () {
//     this.core.outer.querySelector('.lg-toolbar').insertAdjacentHTML('beforeend', '<a class="btn btn-primary btn-select-photo"><i class="fal fa-check"></i> Выбрать фото</a>');
//
//     var that = this;
//
//     $('.btn-select-photo').click(function (e) {
//         e.preventDefault();
//
//         let isChecked = $(this).hasClass('btn-success');
//         let photoId = that.core.outer;
//
//         console.log(that.core.outer);
//
//         if (isChecked) {
//             $(this).removeClass('btn-success').addClass('btn-primary').html('<i class="fal fa-check"></i> Выбрать фото').unbind('mouseenter mouseleave');
//         } else {
//             $(this).removeClass('btn-primary').addClass('btn-success').html('<i class="fal fa-check"></i> Фото выбрано')
//                 .hover(function () {
//                     $(this).html('<i class="fal fa-times"></i> Отменить выбор');
//                 }, function () {
//                     $(this).html('<i class="fal fa-check"></i> Фото выбрано');
//                 });
//         }
//     });
// };
//
// SelectPhotoPlugin.prototype.destroy = function () {
//
// };
//
// window.lgModules.selectphoto = SelectPhotoPlugin;

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
        var that = this;
        
        this.core.$outer.find('.lg-toolbar').append('<div class="SelectPhoto-controls">' +
            '<a href="#" class="btn btn-primary finish-select"><i class="far fa-envelope"></i> Завершить</a>' +
            '<a href="#" class="btn btn-success toggle-photo"><i class="far fa-check"></i> Выбрать</a>' +
            '</div>');

        this.core.$el.on('onAfterSlide.lg.tm', function(event, prevIndex, index) {

            setTimeout(function() {
            }, 100);
        });
    };

    SelectPhoto.prototype.destroy = function () {

    };

    // Attach your module with lightgallery
    $.fn.lightGallery.modules.SelectPhoto = SelectPhoto;


})(jQuery, window, document);


$(document).ready(function () {
    $("#lightgallery").lightGallery({
        download: false,
        loop: false,
        SelectPhoto: true,
        share: false,
        zoom: false,
        autoplayControls: false,
        hideBarsDelay: 1000 * 60 * 6,
    });
    // let $lightgallery = $('#lightgallery');
    //
    // lightGallery($lightgallery.get(0), {
    //     download: false,
    //     loop: false,
    //     selectphoto: true,
    //     hideBarsDelay: 1000 * 60 * 6,
    // });
    //
        $('#lightgallery > a:first').trigger('click');
});
