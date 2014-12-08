Prims47PaypalExpressCheckoutNVP
===============================


This bundle allows to use Paypal_ Express Checkout NVP for Symfony2.

.. _Paypal: https://developer.paypal.com/docs/classic/express-checkout/gs_expresscheckout/

How it's work?
--------------
.. image:: https://www.paypalobjects.com/webstatic/en_US/developer/docs/ec/EC_hooks.gif

Account Paypal
--------------

In first time, you must have Paypal account. 
You can create a sandbox for dev environment in Paypal Developer website_ 

.. _website: https://developer.paypal.com/


Installation
------------

Use composer
^^^^^^^^^^^^

.. code-block:: bash

$ php composer.phar require prims47/paypal-express-checkout-nvp-bundle


Enable the bundle
^^^^^^^^^^^^^^^^^

.. code-block:: php

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

Routing
^^^^^^^

.. code-block:: yaml

    # app/config/routing.yml
    prims47_paypal_express_checkout_nvp:
        resource: "@Prims47PaypalExpressCheckoutNVPBundle/Resources/config/routing.xml"
        prefix:   /paypal_express_checkout_nvp


Update your database schema
^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: bash

    $ php app/console doctrine:schema:update --force


Configuration (simple version)
------------------------------

For paypal information you can read this Paypal_

.. _Paypal: https://developer.paypal.com/docs/classic/api/apiCredentials/

.. code-block:: yaml

    # app/config/config.yml
    prims47_paypal_express_checkout_nvp:
        user:                   #Your user account
        signature:              #Your user signature
        pwd:                    #Your password
        route_redirect_success: #Your success route
        costs:                  #Your global costs
        cancel_route:           #Your cancel route
        currency_code:          #Your currency code. By default is EUR

Usage simple
------------

Create a simple basket
^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
        Use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;
        // ...
        public function indexAction()
        {
            return $this->render('YourBundle:Basket:basket.html.twig', array(
                'basket' => array(PaypalExpressCheckoutNVPInterface::PAYPAL_TOTAL_DUTY => 100) // The price without VAT
            ));
        }


Use Twig Helpers for Button Paypal
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can use Twig helpers 

* prims47_paypal_express_checkout_nvp_simple
* prims47_paypal_express_checkout_nvp_details

Parameters of Twig helper

* Basket
* VAT percentage
* Costs


Exemple using simple Twig helper
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


.. code-block::

    <a href="{{ prims47_paypal_express_checkout_nvp_simple(basket, '7.5', '3') }}" target="_blank">Paid</a>


Each transaction is save in your database
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
