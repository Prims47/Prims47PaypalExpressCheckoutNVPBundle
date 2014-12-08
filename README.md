# Prims47PaypalExpressCheckoutNVP

This bundle allows to use [Paypal Express Checkout NVP](https://developer.paypal.com/docs/classic/express-checkout/gs_expresscheckout/) for Symfony2.

## How it's work?
![Paypal Express Checkout](https://www.paypalobjects.com/webstatic/en_US/developer/docs/ec/EC_hooks.gif)

## Account Paypal

In first time, you must have Paypal account. 
You can create a sandbox for dev environment in [Paypal Developer website](https://developer.paypal.com/)


## Installation

### Use composer

```
$ php composer.phar require prims47/paypal-express-checkout-nvp-bundle
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

### Routing

``` yml
# app/config/routing.yml
prims47_paypal_express_checkout_nvp:
    resource: "@Prims47PaypalExpressCheckoutNVPBundle/Resources/config/routing.xml"
    prefix:   /paypal_express_checkout_nvp
```

### Update your database schema

```
$ php app/console doctrine:schema:update --force
```

## Configuration (simple version)

For paypal information you can read this [Paypal](https://developer.paypal.com/docs/classic/api/apiCredentials/)
``` yml
# app/config/config.yml
prims47_paypal_express_checkout_nvp:
    user:                   #Your user account
    signature:              #Your user signature
    pwd:                    #Your password
    route_redirect_success: #Your success route
    costs:                  #Your global costs
    cancel_route:           #Your cancel route
    currency_code:          #Your currency code. By default is EUR
```


## Usage simple

### Create a simple basket

``` php
<?php
    Use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;
    // ... 
    public function indexAction()
    {
        return $this->render('YourBundle:Basket:basket.html.twig', array(
            'basket' => array(PaypalExpressCheckoutNVPInterface::PAYPAL_TOTAL_DUTY => 100)
        ));
    }
```

### Use Twig Helpers for Button Paypal

You can use Twig helpers 

* prims47_paypal_express_checkout_nvp_simple
* prims47_paypal_express_checkout_nvp_details

Parameters of Twig helper

* Basket
* VAT percentage
* Costs


#### Exemple using simple Twig helper

```
<a href="{{ prims47_paypal_express_checkout_nvp_simple(basket, '7.5', '3') }}" target="_blank">Paid</a>
```

### Each transaction is save in your database
