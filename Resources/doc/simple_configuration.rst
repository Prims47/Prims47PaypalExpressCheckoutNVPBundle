Prims47PaypalExpressCheckoutNVP
===============================

Configuration
-------------

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
        is_prod:                #Define if you is prod environnement or dev. By default is false