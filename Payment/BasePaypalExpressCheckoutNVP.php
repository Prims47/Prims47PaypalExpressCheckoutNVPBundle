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

use Doctrine\Bundle\DoctrineBundle\Registry;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response as GuzzleResponse;

use Monolog\Logger;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Entity\BasePaypalExpressCheckoutNVPTransactionDetails;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul\Calcul;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Validation\Validation;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

abstract class BasePaypalExpressCheckoutNVP implements PaypalExpressCheckoutNVPInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var float
     */
    protected $costs;

    /**
     * @var array
     */
    protected $optionsRequest;

    /**
     * @var string
     */
    protected $urlApi;

    /**
     * @var boolean
     */
    protected $isSSL;

    /**
     * @var string
     */
    protected $pathSSL;

    /**
     * @var float
     */
    protected $vatPercentage;

    /**
     * @var string
     */
    protected $urlCmd;

    /**
     * @var Calcul
     */
    protected $calcul;

    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $urlRedirectSuccess;

    /**
     * @var string
     */
    protected $returnRouteSimple;

    /**
     * @var string
     */
    protected $returnRouteDetails;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(
      Router $router,
      Session $session,
      Registry $doctrine,
      Client $client,
      $user,
      $signature,
      $pwd,
      $returnRouteSimple,
      $returnRouteDetails,
      $cancelRoute,
      $urlRedirectSuccess,
      $costs,
      $currencyCode,
      $version,
      $isSSL,
      $pathSSL,
      $vatPercentage,
      $isProd,
      $urlApi,
      $urlCmd,
      Calcul $calcul,
      Validation $validation,
      Logger $logger,
      EventDispatcherInterface $dispatcher
    )
    {
        $this->router       = $router;
        $this->session      = $session;
        $this->doctrine     = $doctrine;
        $this->client       = $client;
        $this->costs        = $costs;

        $this->optionsRequest = array(
          'USER'                          => $user,
          'SIGNATURE'                     => $signature,
          'PWD'                           => $pwd,
          'PAYMENTREQUEST_0_CURRENCYCODE' => $currencyCode,
          'VERSION'                       => $version,
          'RETURNURL'                     => $this->router->generate($returnRouteSimple, array(), true),
          'CANCELURL'                     => $this->router->generate($cancelRoute, array(), true),
        );

        $this->urlApi             = $urlApi;
        $this->isSSL              = $isSSL;
        $this->pathSSL            = $pathSSL;
        $this->vatPercentage      = $vatPercentage;
        $this->urlCmd             = $urlCmd;
        $this->urlRedirectSuccess = $urlRedirectSuccess;
        $this->calcul             = $calcul;
        $this->validation         = $validation;
        $this->returnRouteDetails = $returnRouteDetails;
        $this->returnRouteSimple  = $returnRouteSimple;
        $this->logger             = $logger;
        $this->dispatcher         = $dispatcher;
    }

    /**
     * Return a Guzzle response.
     *
     * @param array $optionsRequest
     *
     * @return GuzzleResponse
     *
     * @throws \Exception
     */
    protected function sendToPaypalExpressCheckoutNVP(array $optionsRequest)
    {
        $request = $this->client->post($this->urlApi, null, $optionsRequest);
        $request->getCurlOptions()->set(CURLOPT_RETURNTRANSFER, true);
        $request->getCurlOptions()->set(CURLOPT_VERBOSE, true);

        if (!$this->isSSL) {
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
        } else {
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, true);
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, 2);

            if (empty($this->pathSSL))  {
                throw new \Exception('Add path to SSL pem in your config.');
            }

            $request->getCurlOptions()->set(CURLOPT_CAINFO, $this->pathSSL);
        }

        return $request->send();
    }

    /**
     * Return a Paypal response.
     *
     * @param GuzzleResponse $response
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getPaypalExpressCheckoutNVPResponse(GuzzleResponse $response)
    {
        $responseArray = array();

        parse_str($response->getBody(true), $responseArray);

        if (!empty($responseArray['ACK']) &&
          !in_array($responseArray['ACK'], array(self::PAYPAL_PAYMENT_ACTION_COMPLETED, self::PAYPAL_PAYMENT_ACTION_SUCCESS, self::PAYPAL_PAYMENT_ACTION_SUCCESS_WITH_WARNING))
        ) {
            throw new \Exception(sprintf('Error: "%s"', $responseArray['L_LONGMESSAGE0']));
        }

        if (empty($responseArray['TOKEN'])) {
            throw new \Exception('Invalid token');
        }

        return $responseArray;
    }

    /**
     * Return a basket array for Paypal with expected keys.
     *
     * @param array $basket
     *
     * @return array
     */
    protected function makeBasketPaypalExpressCheckoutNVP(array $basket)
    {
        $basketPaypal = array();

        foreach ($basket as $key => $basketElement) {

            $basketPaypal[sprintf('%s_0_NAME%d', self::L_PAYMENTREQUEST, $key)]  = $basketElement[self::L_PAYMENTREQUEST_NAME];
            $basketPaypal[sprintf('%s_0_AMT%d',  self::L_PAYMENTREQUEST, $key)]  = $basketElement[self::L_PAYMENTREQUEST_AMT];
            $basketPaypal[sprintf('%s_0_QTY%d',  self::L_PAYMENTREQUEST, $key)]  = $basketElement[self::L_PAYMENTREQUEST_QTY];

            if (array_key_exists(self::L_PAYMENTREQUEST_DESC, $basketElement)) {
                $basketPaypal[sprintf('%s_0_DESC%d', self::L_PAYMENTREQUEST, $key)] = $basketElement[self::L_PAYMENTREQUEST_DESC];
            }
        }

        return $basketPaypal;
    }

    /**
     * Return a session by name.
     *
     * @param string $sessionName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function getSessionByName($sessionName)
    {
        if (!$this->session->has($sessionName)) {
            throw new \Exception('Session don\'t exist');
        }

        return $this->session->get($sessionName);
    }

    /**
     * Return TRUE if payment is completed.
     *
     * @param array $responsePaypal
     *
     * @return boolean
     */
    protected function checkPaymentIsCompleted(array $responsePaypal)
    {
        if (!empty($responsePaypal['CHECKOUTSTATUS']) && self::PAYPAL_PAYMENT_ACTION_COMPLETED == $responsePaypal['CHECKOUTSTATUS']) {
            return true;
        }

        return false;
    }

    /**
     * Return an array without sensitive data.
     *
     * @param array $responsePaypal
     *
     * @return array
     */
    protected function cleanUpResponseGetExpressCheckoutDetails(array $responsePaypal)
    {
        $checkoutDetails = array();

        $checkoutDetails['FIRSTNAME']         = $responsePaypal['FIRSTNAME'];
        $checkoutDetails['LASTNAME']          = $responsePaypal['LASTNAME'];
        $checkoutDetails['COUNTRYCODE']       = $responsePaypal['COUNTRYCODE'];
        $checkoutDetails['SHIPTOCOUNTRYNAME'] = $responsePaypal['SHIPTOCOUNTRYNAME'];
        $checkoutDetails['SHIPTOSTREET']      = $responsePaypal['SHIPTOSTREET'];
        $checkoutDetails['SHIPTOCITY']        = $responsePaypal['SHIPTOCITY'];
        $checkoutDetails['SHIPTOSTATE']       = $responsePaypal['SHIPTOSTATE'];
        $checkoutDetails['SHIPTOZIP']         = $responsePaypal['SHIPTOZIP'];
        $checkoutDetails['CURRENCYCODE']      = $responsePaypal['CURRENCYCODE'];
        $checkoutDetails['TAXAMT']            = $responsePaypal['TAXAMT'];
        $checkoutDetails['HANDLINGAMT']       = $responsePaypal['HANDLINGAMT'];

        return $checkoutDetails;
    }

    /**
     * Return TRUE if Total Duty from Drupal is equal to Total Duty if your Session.
     *
     * @param array $responsePaypal
     *
     * @return boolean
     *
     * @throws \Exception
     */
    protected function checkTotalDutyFromPaypalExpressCheckoutNVP(array $responsePaypal)
    {
        $totalDutyFree = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_TOTAL_DUTY);
        $vat           = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_VAT);
        $costs         = $this->getSessionByName(self::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_COSTS);

        if (!empty($responsePaypal['PAYMENTREQUEST_0_AMT']) && $responsePaypal['PAYMENTREQUEST_0_AMT'] != ($totalDutyFree + $vat + $costs)) {
            throw new \Exception('Invalid total duty. Please check your basket.');
        }

        return true;
    }

    /**
     * Save transaction details from Paypal.
     *
     * @param array $paymentDetails
     *
     * @return BasePaypalExpressCheckoutNVPTransactionDetails
     */
    protected function saveTransactionDetails(array $paymentDetails)
    {
        $transactionDetails = new BasePaypalExpressCheckoutNVPTransactionDetails();
        $transactionDetails->setFirstName($paymentDetails['FIRSTNAME']);
        $transactionDetails->setLastName($paymentDetails['LASTNAME']);
        $transactionDetails->setCountryCode($paymentDetails['COUNTRYCODE']);
        $transactionDetails->setCountryName($paymentDetails['SHIPTOCOUNTRYNAME']);
        $transactionDetails->setStreet($paymentDetails['SHIPTOSTREET']);
        $transactionDetails->setCity($paymentDetails['SHIPTOCITY']);
        $transactionDetails->setState($paymentDetails['SHIPTOSTATE']);
        $transactionDetails->setZip($paymentDetails['SHIPTOZIP']);
        $transactionDetails->setCurrencyCode($paymentDetails['CURRENCYCODE']);

        $transactionDetails->setOrderTime(new \DateTime($paymentDetails['PAYMENTINFO_0_ORDERTIME']));

        $transactionDetails->setTransactionId($paymentDetails['PAYMENTINFO_0_TRANSACTIONID']);
        $transactionDetails->setTotalDuty($paymentDetails['ITEMAMT']);
        $transactionDetails->setCosts($paymentDetails['HANDLINGAMT']);
        $transactionDetails->setTax($paymentDetails['TAXAMT']);

        $em = $this->doctrine->getManager();
        $em->persist($transactionDetails);
        $em->flush();

        return $transactionDetails;
    }
} 