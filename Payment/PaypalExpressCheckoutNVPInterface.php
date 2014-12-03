<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment;

interface PaypalExpressCheckoutNVPInterface
{
    const L_PAYMENTREQUEST_NAME = 'L_PAYMENTREQUEST_NAME';
    const L_PAYMENTREQUEST_DESC = 'L_PAYMENTREQUEST_DESC';
    const L_PAYMENTREQUEST_AMT  = 'L_PAYMENTREQUEST_AMT';
    const L_PAYMENTREQUEST_QTY  = 'L_PAYMENTREQUEST_QTY';

    const PAYPAL_METHOD_SET_EXPRESS_CHECKOUT         = 'SetExpressCheckout';
    const PAYPAL_METHOD_GET_EXPRESS_CHECKOUT_DETAILS = 'GetExpressCheckoutDetails';
    const PAYPAL_METHOD_DO_EXPRESS_CHECKOUT_PAYMENT  = 'DoExpressCheckoutPayment';

    const L_PAYMENTREQUEST = 'L_PAYMENTREQUEST';

    const PAYPAL_TOTAL_DUTY = 'TotalDuty';

    const PAYPAL_PAYMENT_ACTION_COMPLETED            = 'PaymentActionCompleted';
    const PAYPAL_PAYMENT_ACTION_SUCCESS              = 'Success';
    const PAYPAL_PAYMENT_ACTION_SUCCESS_WITH_WARNING = 'SuccessWithWarning';

    const PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_TOTAL_DUTY  = 'PaypalSetExpressCheckoutNVPSessionTotalDutyFree';
    const PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_VAT         = 'PaypalSetExpressCheckoutNVPSessionVAT';
    const PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_COSTS       = 'PaypalSetExpressCheckoutNVPSessionCosts';
    const PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_FULL_BASKET = 'PaypalSetExpressCheckoutNVPSessionFullBasket';

    const PAYPAL_PAYMENTACTION = 'Sale';

    const PAYPAL_EXPRESS_CHECKOUT_DETAILS_SESSION_CLEAN_UP = 'PaypalExpressCheckoutNVPDetailsSessionCleanUp';

    /**
     * Return url callback for Paypal with token.
     *
     * @param array $basket
     * @param float $vatPercentage
     * @param float $costs
     *
     * @return string
     */
    public function setExpressCheckout(array $basket, $vatPercentage = null, $costs = null);

    /**
     * Return an array of response of Paypal.
     *
     * @param string $token
     *
     * @throws \Exception
     */
    public function getExpressCheckoutDetails($token);

    /**
     * Return route redirect success.
     *
     * @param string $payerId
     * @param string $token
     *
     * @return string
     *
     * @throws \Exception
     */
    public function doExpressCheckoutPayment($payerId, $token);
} 