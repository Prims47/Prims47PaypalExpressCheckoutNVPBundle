Prims47PaypalExpressCheckoutNVP
===============================

Configuration
-------------

Read `here <https://github.com/Prims47/Prims47PaypalExpressCheckoutNVPBundle/tree/master/Resources/doc/details_configuration.rst>`_

Usage simple
------------

Create a details basket
^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
        Use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;
        // ...
        public function indexAction()
        {
            return $this->render('YourBundle:Basket:basket.html.twig', array(
                'basket' => array(
                    array(
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT  => 100, // The price without VAT
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME => 'Iphone 4',
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY  => '1',
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_DESC => 'Apple Iphone 4 ! Amazing device !',
                    ),
                    array(
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT  => 10, // The price without VAT
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME => 'Coque Iphone 4',
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY  => '1',
                        PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_DESC => 'Apple Iphone 4 ! Amazing coque device !',
                    )
                )
            ));
        }


Use Twig Helpers for Button Paypal
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can use Twig helpers

* prims47_paypal_express_checkout_nvp_details

Parameters of Twig helper

* Basket
* VAT percentage
* Costs


Exemple using simple Twig helper
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


.. code-block::

    <a href="{{ prims47_paypal_express_checkout_nvp_details(basket, '7.5', '33') }}" target="_blank">Paid</a>


Each transaction is save in your database
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
