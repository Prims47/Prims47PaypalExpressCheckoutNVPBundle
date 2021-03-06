Prims47PaypalExpressCheckoutNVP
===============================

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


Use Twig Helper
^^^^^^^^^^^^^^^

You can use Twig helper

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
