# Prims47PaypalExpressCheckoutNVP

This bundle allows to use [Paypal Express Checkout NVP](https://developer.paypal.com/docs/classic/express-checkout/integration-guide/ECGettingStarted/) for Symfony2.

Account Paypal
--------------

In first time, you must have Paypal account. 
You can create a sandbox for dev environment in [Paypal Developer website](https://developer.paypal.com/)


1. Installation
---------------

### Use composer

```
php composer.phar require prims47/paypal-express-checkout-nvp-bundle
```

### Enable the bundle

``` php
<?php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        // Then add Prims47PaypalExpressCheckoutNVPBundle
        new Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Prims47PaypalExpressCheckoutNVPBundle(),
        // ...
    );
}
```
