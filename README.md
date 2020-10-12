# ReCAPTCHA for Paya payments (Meetanshi)

Add server side validation for URL `paya/checkout/request`.

### Installation

```
cd <magento_root>
composer config repositories.swissup/module-recaptcha-meetanshi-paya vcs git@github.com:swissup/module-recaptcha-meetanshi-paya.git
composer require swissup/module-refined-url
bin/magento setup:upgrade
```
