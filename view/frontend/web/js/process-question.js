define([
    'jquery',
    'ko'
    ], function ($, ko) {
    'use strict';
    let el = $('#product-question-container');
    function processQuestion(url, fromPages) {
        $.ajax({
            url: url,
            cache: false,
            dataType: 'html',
            showLoader: true
        }).done(function (data) {
            el.html(data);
            el.trigger('contentUpdated');
            ko.cleanNode(document.getElementById('product-question-container'));
            el.applyBindings();
            $('[data-role="product-question"] .pages a').each(function (index, element) {
                $(element).click(function (event) { //eslint-disable-line max-nested-callbacks
                    processQuestion($(element).attr('href'), true);
                    event.preventDefault();
                });
            });

        }).complete(function () {
            if (fromPages == true) { //eslint-disable-line eqeqeq
                $('html, body').animate({
                    scrollTop: el.offset().top - 50
                }, 300);
            }
        });
    }

    return function (config) {
        processQuestion(config.productQuestionUrl);
    };
});