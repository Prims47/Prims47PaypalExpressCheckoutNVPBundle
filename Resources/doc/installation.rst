Prims47PaypalExpressCheckoutNVP
===============================

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