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