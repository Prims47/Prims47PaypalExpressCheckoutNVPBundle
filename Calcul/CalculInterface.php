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

interface CalculInterface
{
    /**
     * Calcul total duty of the basket.
     *
     * @param array $basket
     *
     * @return float
     */
    public function calculBasketTotalDuty(array $basket);

    /**
     * Calcul VAT.
     *
     * @param float $totalDutyFree
     * @param float $vatPercentage
     *
     * @return float
     */
    public function calculVAT($totalDutyFree, $vatPercentage);
} 