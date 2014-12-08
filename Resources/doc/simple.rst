Prims47PaypalExpressCheckoutNVP
===============================

Configuration
-------------

Read `here <https://github.com/Prims47/Prims47PaypalExpressCheckoutNVPBundle/tree/master/Resources/doc/simple_configuration.rst>`_

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
