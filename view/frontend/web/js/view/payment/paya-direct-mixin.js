define([
    'mage/utils/wrapper',
    'Swissup_Recaptcha/js/model/recaptcha-assigner'
], function (wrapper, recaptchaAssigner) {
    'use strict';

    return function (payaDirect) {
        payaDirect.prototype.placeOrderPaya = wrapper.wrap(
            payaDirect.prototype.placeOrderPaya,
            function () {
                var args = Array.prototype.slice.call(arguments),
                    originalFn = args.shift(args),
                    recaptcha = recaptchaAssigner.getRecaptcha(),
                    _me = this;

                if (recaptcha &&
                    recaptcha.options.size === 'invisible' &&
                    !recaptcha.getResponse()
                ) {
                    recaptcha.element.one('recaptchaexecuted', function () {
                        originalFn.apply(_me, args);
                    });
                    recaptcha.execute();

                    return;
                }

                return originalFn.apply(_me, args);
            }
        );

        return payaDirect;
    };
});
