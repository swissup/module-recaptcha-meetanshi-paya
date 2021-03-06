define([
    'jquery',
    'uiComponent',
    'Swissup_Recaptcha/js/model/recaptcha-assigner'
], function ($, Component, recaptchaAssigner) {
    'use strict';

    return Component.extend({
        defaults: {
            formId: null
        },

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            $(document).on('ajaxSend', function (e, jqxhr, settings) {
                var recaptcha = recaptchaAssigner.getRecaptcha();

                if (settings.type === 'GET' &&
                    settings.url.indexOf('paya/checkout/request') > 0
                ) {
                    settings.url += '&captcha=' + recaptcha.getResponse();
                }
            });

            this._super();
        }
    });
});
