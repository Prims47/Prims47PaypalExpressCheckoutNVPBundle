<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Twig\Extension;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul\Calcul;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPDetails;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPSimple;

class Prims47PaypalExpressCheckoutNVPExtension extends \Twig_Extension
{
    /**
     * @var PaypalExpressCheckoutNVPDetails
     */
    protected $paypalExpressCheckoutDetails;

    /**
     * @var PaypalExpressCheckoutNVPSimple
     */
    protected $paypalExpressCheckoutSimple;

    /**
     * @var Calcul
     */
    protected $calcul;

    public function __construct(PaypalExpressCheckoutNVPDetails $paypalExpressCheckout, PaypalExpressCheckoutNVPSimple $paypalExpressCheckoutSimple, Calcul $calcul)
    {
        $this->paypalExpressCheckout       = $paypalExpressCheckout;
        $this->paypalExpressCheckoutSimple = $paypalExpressCheckoutSimple;
        $this->calcul                      = $calcul;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'prims47_paypal_express_checkout_nvp_details'            => new \Twig_Function_Method($this, 'LinkToPaidDetails'),
            'prims47_paypal_express_checkout_nvp_simple'             => new \Twig_Function_Method($this, 'LinkToPaidSimple'),
            'prims47_paypal_express_checkout_nvp__calcul_total_duty' => new \Twig_Function_Method($this, 'calculTotalHT'),
            'prims47_paypal_express_checkout_nvp__calcul_vat'        => new \Twig_Function_Method($this, 'calculVAT'),
        );
    }

    /**
     * Return url link for paid via Paypal.
     *
     * @param array $basket
     * @param float $vatPercentage
     * @param float $costs
     *
     * @return string
     */
    public function LinkToPaidDetails(array $basket, $vatPercentage = null, $costs = null)
    {
        return $this->paypalExpressCheckout->setExpressCheckout($basket, $vatPercentage, $costs);
    }

    /**
     * Return url link for paid via Paypal.
     *
     * @param array $basket
     * @param float $vatPercentage
     * @param float $costs
     *
     * @return string
     */
    public function LinkToPaidSimple(array $basket, $vatPercentage = null, $costs = null)
    {
        return $this->paypalExpressCheckoutSimple->setExpressCheckout($basket, $vatPercentage, $costs);
    }

    /**
     * Return the amount total of basket
     *
     * @param array $basket
     *
     * @return float
     */
    public function calculTotalHT(array $basket)
    {
        return $this->calcul->calculBasketTotalDuty($basket);
    }

    /**
     * Calcul VAT by total and vat percentage
     *
     * @param float $totalDuty
     * @param float $vatPercentage
     *
     * @return float
     */
    public function calculVAT($totalDuty, $vatPercentage)
    {
        return $this->calcul->calculVAT($totalDuty, $vatPercentage);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'prims47_paypal_express_checkout_nvp_extension';
    }
}