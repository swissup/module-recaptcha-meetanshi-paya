<?php

namespace Swissup\RecaptchaMeetanshiPaya\Observer;

use Magento\Framework\App\RequestInterface;

class CheckoutRequestObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Captcha\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @param RequestInterface                            $request
     * @param \Magento\Captcha\Helper\Data                $helper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        \Magento\Captcha\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
    }

    /**
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $formId = $this->request->getParam('form_id');
        $captcha = $this->helper->getCaptcha($formId);
        $gResponse = $this->request->getParam('recaptcha');
        if (!$captcha->verify($gResponse)->isSuccess()) {
            $this->messageManager->addErrorMessage(__('Incorrect reCAPTCHA.'));
            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
        }
    }
}
