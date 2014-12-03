<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul\CalculInterface;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;

class Calcul implements CalculInterface
{
    /**
     * {@inheritdoc}
     */
    public function calculBasketTotalDuty(array $basket)
    {
        $totalDuty = 0;

        foreach ($basket as $basketElement) {
            if (!array_key_exists(PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT, $basketElement)
              && !array_key_exists(PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY, $basketElement)) {
                continue;
            }

            $totalDuty += (floatval($basketElement[PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT])* (int) $basketElement[PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY]);
        }

        return round($totalDuty, 2);
    }

    /**
     * {@inheritdoc}
     */
    public function calculVAT($totalDuty, $vatPercentage)
    {
        $vat = (floatval($totalDuty) / 100) * floatval($vatPercentage);

        return round($vat, 2);
    }
} 