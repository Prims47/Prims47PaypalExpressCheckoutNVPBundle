<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Controller;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPDetails;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPSimple;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectApiDetailsAction()
    {
        /** @var Request $request */
        $request = $this->get('request');

        /** @var PaypalExpressCheckoutNVPDetails $paypal */
        $paypal = $this->get('prims47.bundle.paypal_express_checkout_nvp.details');

        return $this->redirect($this->expressCheckoutAndRedirect($paypal, $request));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectApiSimpleAction()
    {
        /** @var Request $request */
        $request = $this->get('request');

        /** @var PaypalExpressCheckoutNVPSimple $paypal */
        $paypal = $this->get('prims47.bundle.paypal_express_checkout_nvp.simple');

        return $this->redirect($this->expressCheckoutAndRedirect($paypal, $request));
    }

    /**
     * @param PaypalExpressCheckoutNVPInterface $paypal
     * @param Request $request
     *
     * @return string
     */
    private function expressCheckoutAndRedirect(PaypalExpressCheckoutNVPInterface $paypal, Request $request)
    {
        $getExpressCheckoutDetails = $paypal->getExpressCheckoutDetails($request->get('token'));

        if (!empty($getExpressCheckoutDetails)) {
            return $this->generateUrl($getExpressCheckoutDetails);
        }

        $urlRedirect = $paypal->doExpressCheckoutPayment($request->get('PayerID'), $request->get('token'));

        return $this->generateUrl($urlRedirect);
    }
} 