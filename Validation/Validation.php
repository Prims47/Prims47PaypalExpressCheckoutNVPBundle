<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Validation;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Validation\ValidationInterface;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;

class Validation implements ValidationInterface
{
    /**
     * {@inheritdoc}
     */
    public function validNumber($number)
    {
        if (!is_numeric($number)) {
            throw new \Exception(sprintf('You must given number, "%s" given.', gettype($number)));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validPaypalFullBasket(array $basket)
    {
        if (empty($basket)) {
            throw new \Exception('You must given an array of your basket.');
        }

        foreach ($basket as $basketElement) {
            if (!array_key_exists(PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME, $basketElement)) {
                throw new \Exception(sprintf('You must given a key for indicate "name" for Paypal. "%s" expected.', PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME));
            }

            if (!array_key_exists(PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT, $basketElement)) {
                throw new \Exception(sprintf('You must given an key for indicate "total" for Paypal. "%s" expected.', PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT));
            }

            if (!array_key_exists(PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY, $basketElement)) {
                throw new \Exception(sprintf('You must given an key for indicate "quantity" for Paypal. "%s" expected.', PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY));
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validPaypalSimpleBasket(array $basket)
    {
        if (empty($basket[PaypalExpressCheckoutNVPInterface::PAYPAL_TOTAL_DUTY])) {
            throw new \Exception('You must given a total duty free in your basket.');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validString($string, $exceptionMessage)
    {
        if (empty($string)) {
            throw new \Exception(sprintf('%s', $exceptionMessage));
        }

        return true;
    }
} 