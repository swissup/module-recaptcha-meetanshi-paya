<?php

namespace Swissup\RecaptchaMeetanshiPaya\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ObserverInterface;

class CheckoutRequestObserver implements ObserverInterface
{
    /**
     * @var \Magento\Captcha\Helper\Data
     */
    private $helper;

    /**
     * @var \Swissup\Recaptcha\Helper\Data
     */
    private $recaptchaHelper;

    /**
     * @param \Magento\Captcha\Helper\Data   $helper
     * @param \Swissup\Recaptcha\Helper\Data $recaptchaHelper
     */
    public function __construct(
        \Magento\Captcha\Helper\Data $helper,
        \Swissup\Recaptcha\Helper\Data $recaptchaHelper
    ) {
        $this->helper = $helper;
        $this->recaptchaHelper = $recaptchaHelper;
    }

    /**
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $formId = $this->recaptchaHelper->getCheckoutFormId();
        $request = $observer->getRequest();
        $captchaValue = $request->getParam('captcha');
        $captcha = $this->helper->getCaptcha($formId);

        if ($captcha->isRequired()
            && !$captcha->verify($captchaValue)->isSuccess()
        ) {
            $controllerAction = $observer->getControllerAction();
            $controllerAction->getActionFlag()->set(
                '',
                \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH,
                true
            );
            $controllerAction->getResponse()->representJson(
                json_encode([
                    'error' => true,
                    'message' => __('Incorrect reCAPTCHA.')
                ])
            );
        }
    }
}
