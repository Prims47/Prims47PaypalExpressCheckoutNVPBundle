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

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Event\TransactionEvent;

class PaypalExpressCheckoutNVPSimple extends BasePaypalExpressCheckoutNVP
{
    /**
     * {@inheritdoc}
     */
    public function setExpressCheckout(array $basket, $vatPercentage = null, $costs = null)
    {
        if (empty($vatPercentage)) {
            $vatPercentage = $this->vatPercentage;
        }

        if (empty($costs)) {
            $costs = $this->costs;
        }

        $this->validation->validPaypalSimpleBasket($basket);

        $this->validation->validNumber($basket[self::PAYPAL_TOTAL_DUTY]);
        $this->validation->validNumber($vatPercentage);
        $this->validation->validNumber($costs);

        $totalDuty = $basket[self::PAYPAL_TOTAL_DUTY];
        $vat       = $this->calcul->calculVAT($totalDuty, $vatPercentage);

        $this->optionsRequest['RETURNURL']                    = $this->router->generate($this->returnRouteSimple, array(), true);
        $this->optionsRequest['METHOD']                       = self::PAYPAL_METHOD_SET_EXPRESS_CHECKOUT;
        $this->optionsRequest['PAYMENTREQUEST_0_AMT']         = $totalDuty + $costs + $vat;
        $this->optionsRequest['PAYMENTREQUEST_0_ITEMAMT']     = $totalDuty;
        $this->optionsRequest['PAYMENTREQUEST_0_TAXAMT']      = $vat;
        $this->optionsRequest['PAYMENTREQUEST_0_HANDLINGAMT'] = $costs;

        $this->session->set(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_TOTAL_DUTY, $totalDuty);
        $this->session->set(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_VAT, $vat);
        $this->session->set(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_COSTS, $costs);

        $response = $this->getPaypalExpressCheckoutNVPResponse($this->sendToPaypalExpressCheckoutNVP($this->optionsRequest));

        return sprintf('%s&token=%s', $this->urlCmd, $response['TOKEN']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpressCheckoutDetails($token)
    {
        $this->validation->validString($token, 'You must given a token');

        $this->optionsRequest['METHOD'] = self::PAYPAL_METHOD_GET_EXPRESS_CHECKOUT_DETAILS;
        $this->optionsRequest['TOKEN']  = $token;

        $response = $this->getPaypalExpressCheckoutNVPResponse($this->sendToPaypalExpressCheckoutNVP($this->optionsRequest));

        $this->checkTotalDutyFromPaypalExpressCheckoutNVP($response);

        if ($this->checkPaymentIsCompleted($response)) {
            return $this->urlRedirectSuccess;
        }

        $checkoutDetails = $this->cleanUpResponseGetExpressCheckoutDetails($response);
        $this->session->set(self::PAYPAL_EXPRESS_CHECKOUT_DETAILS_SESSION_CLEAN_UP, $checkoutDetails);
    }

    /**
     * {@inheritdoc}
     */
    public function doExpressCheckoutPayment($payerId, $token)
    {
        $this->validation->validString($payerId, 'You must given a Payer Id');
        $this->validation->validString($token, 'You must given a token');

        $totalDuty = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_TOTAL_DUTY);
        $vat           = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_VAT);
        $costs         = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_COSTS);

        $this->optionsRequest['METHOD']                       = self::PAYPAL_METHOD_DO_EXPRESS_CHECKOUT_PAYMENT;
        $this->optionsRequest['TOKEN']                        = $token;
        $this->optionsRequest['PAYERID']                      = $payerId;
        $this->optionsRequest['PAYMENTACTION']                = self::PAYPAL_PAYMENTACTION;
        $this->optionsRequest['PAYMENTREQUEST_0_ITEMAMT']     = $totalDuty;
        $this->optionsRequest['PAYMENTREQUEST_0_AMT']         = $totalDuty + $vat + $costs;
        $this->optionsRequest['PAYMENTREQUEST_0_TAXAMT']      = $vat;
        $this->optionsRequest['PAYMENTREQUEST_0_HANDLINGAMT'] = $costs;

        $response = $this->getPaypalExpressCheckoutNVPResponse($this->sendToPaypalExpressCheckoutNVP($this->optionsRequest));

        if (!empty($response)) {
            $paymentDetails = array_merge($this->getSessionByName(self::PAYPAL_EXPRESS_CHECKOUT_DETAILS_SESSION_CLEAN_UP), $response, array('ITEMAMT' => $totalDuty));

            $transaction = $this->saveTransactionDetails($paymentDetails);

            $this->logger->info(sprintf('%s %s paid transaction id %d for %s %s', $paymentDetails['FIRSTNAME'], $paymentDetails['LASTNAME'], $transaction->getId(), $totalDuty, $paymentDetails['CURRENCYCODE']));

            $this->dispatcher->dispatch('prims47.paypal_express_checkout_nvp_success_transaction', new TransactionEvent($transaction));

            return $this->urlRedirectSuccess;
        }
    }
} 