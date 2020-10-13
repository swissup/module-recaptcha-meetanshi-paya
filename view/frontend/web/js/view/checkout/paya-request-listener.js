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
            var me = this;

            $(document).on('ajaxSend', function (e, jqxhr, settings) {
                var recaptcha = recaptchaAssigner.getRecaptcha();

                if (settings.type === 'GET' &&
                    settings.url.indexOf('paya/checkout/request') > 0
                ) {
                    settings.url += '&form_id=' + me.formId;
                    settings.url += '&recaptcha=' + recaptcha.getResponse();
                }
            });

            this._super();
        }
    });
});
