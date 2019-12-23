define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'mage/template',
    'mage/translate',
    'text!Boolfly_ProductQuestion/template/form.html',
    'mage/url',
    'jquery/ui',
], function (Component, customerData, $, mageTemplate, $t, formTemplate, url) {
    'use strict';

    return Component.extend({

        /** @inheritdoc */
        initialize: function () {
            this._super();
            this.customer = customerData.get('customer');
        },

        loginUrl: function() {
            let loginUrl = url.build('customer/account/login/referer');
            return loginUrl + '/' + window.base64CurrentUrl;
        },

        generateForm: function (data, event) {
            let questionId = $(event.target).data('id'),
                productId = $(event.target).data('product-id'),
                progressTmpl = mageTemplate(formTemplate),
                reply = $('#reply-form-' + questionId);
            reply.empty();
            $(progressTmpl({
                'data': {
                    'action': this.postUrl,
                    'formKey': $.mage.cookies.get('form_key'),
                    'questionId': questionId,
                    'productId': productId,
                    'replyPlaceHolderText': $t("Place your reply here"),
                    'authorSubmitText' : $t('Submit')
                }
            })).appendTo(reply);
            reply.trigger('contentUpdated');
        }
    });
});