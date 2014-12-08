Prims47PaypalExpressCheckoutNVP
===============================

Configuration
-------------

For paypal information you can read this Paypal_

.. _Paypal: https://developer.paypal.com/docs/classic/api/apiCredentials/

.. code-block:: yaml

    # app/config/config.yml
    prims47_paypal_express_checkout_nvp:
        user:                          #Your user account
        signature:                     #Your user signature
        pwd:                           #Your password
        route_redirect_success:        #Your success route
        costs:                         #Your global costs
        cancel_route:                  #Your cancel route
        currency_code:                 #Your currency code. By default is EUR
        version:                       #Paypal Express Checkout Version; By default is 111
        is_ssl:                        #If your website use ssl. By default is false
        path_ssl:                      #Path certifcat SSL. By default is null
        vat_percentage:                #Global VAT percentage. By default is 0
        is_prod:                       #Define if you is prod environnement or dev. By default is false
        url_api_dev:                   #Url api dev environnement. By default is https://api-3t.sandbox.paypal.com/nvp
        url_api_prod:                  #Url api prod environnement. By default is https://api-3t.paypal.com/nvp
        url_cmd_express_checkout_dev:  #Url command paypal express checkout in dev environnement. By default is https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit
        url_cmd_express_checkout_prod: #Url command paypal express checkout in prod environnement. By default is https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit
