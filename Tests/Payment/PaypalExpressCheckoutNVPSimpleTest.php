<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Tests\Payment;

use Guzzle\Http\Message\Response as GuzzleResponse;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPSimple;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class PaypalExpressCheckoutNVPSimpleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaypalExpressCheckoutNVPSimple
     */
    protected $paypalExpressCheckoutNVPSimple;

    /**
     * @var GuzzleResponse
     */
    protected $guzzleResponse;

    protected $session;

    protected function setUp()
    {
        $this->session                        = new Session(new MockArraySessionStorage());
        $this->paypalExpressCheckoutNVPSimple = $this->preparePaypalExpressCheckoutNVPSimple();
        $this->guzzleResponse                 = $this->getMockBuilder('Guzzle\Http\Message\Response')->disableOriginalConstructor()->getMock();
    }

    public function testGetPaypalExpressCheckoutNVPResponse()
    {
        $this->guzzleResponse->expects($this->any())->method('getBody')->will($this->returnValue('TOKEN=EC%2d28D57488PP8394723&TIMESTAMP=2014%2d12%2d04T12%3a58%3a11Z&CORRELATIONID=5baf198ba3852&ACK=Success&VERSION=111&BUILD=14086142'));

        $this->assertNotEmpty($this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'getPaypalExpressCheckoutNVPResponse', array($this->guzzleResponse)));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetPaypalExpressCheckoutNVPNoTokenResponse()
    {
        $this->guzzleResponse->expects($this->any())->method('getBody')->will($this->returnValue('TIMESTAMP=2014%2d12%2d04T12%3a58%3a11Z&CORRELATIONID=5baf198ba3852&ACK=Success&VERSION=111&BUILD=14086142'));

        $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'getPaypalExpressCheckoutNVPResponse', array($this->guzzleResponse));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetPaypalExpressCheckoutNVPActionCompletedResponse()
    {
        $this->guzzleResponse->expects($this->any())->method('getBody')->will($this->returnValue('TOKEN=E47C%2d28D57488PP8394723&TIMESTAMP=2014%2d12%2d04T12%3a58%3a11Z&CORRELATIONID=5baf198ba3852&ACK=Prims47&VERSION=111&BUILD=14086142&L_LONGMESSAGE0=Exception'));

        $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'getPaypalExpressCheckoutNVPResponse', array($this->guzzleResponse));
    }

    public function testMakeBasketPaypalExpressCheckoutNVP()
    {
        $basket = array(
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME => 'Macbook Pro Retina',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT  => '3000',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY  => '1',
          ),
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME => 'Clavier sans file',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT  => '50',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY  => '1',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_DESC => 'Super clavier sans file apple !',
          ),
        );

        $paypalBasket = $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'makeBasketPaypalExpressCheckoutNVP', array($basket));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_NAME0', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_NAME0']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_AMT0', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_AMT0']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_QTY0', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_QTY0']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_NAME1', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_NAME1']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_AMT1', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_AMT1']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_QTY1', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_QTY1']));

        $this->assertTrue(array_key_exists('L_PAYMENTREQUEST_0_DESC1', $paypalBasket));
        $this->assertTrue(!empty($paypalBasket['L_PAYMENTREQUEST_0_DESC1']));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetSessionByName()
    {
        $this->session->set('sessionPrims47PaypalExpressCheckoutNVP','Prims47PaypalExpressCheckoutNVP');

        $this->assertEquals('Prims47PaypalExpressCheckoutNVP', $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'getSessionByName', array('sessionPrims47PaypalExpressCheckoutNVP')));

        $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'getSessionByName', array('notSession'));
    }

    public function testCheckPaymentIsCompleted()
    {
        $responsePaypal = array(
          'CHECKOUTSTATUS' => PaypalExpressCheckoutNVPInterface::PAYPAL_PAYMENT_ACTION_COMPLETED
        );

        $this->assertTrue($this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'checkPaymentIsCompleted', array($responsePaypal)));

        unset($responsePaypal['CHECKOUTSTATUS']);
        $this->assertFalse($this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'checkPaymentIsCompleted', array($responsePaypal)));
    }

    public function testCleanUpResponseGetExpressCheckoutDetails()
    {
        $responsePaypal = array(
          'FIRSTNAME'         => 'Ilan',
          'LASTNAME'          => 'B',
          'COUNTRYCODE'       => 'FR',
          'SHIPTOCOUNTRYNAME' => 'France',
          'SHIPTOSTREET'      => '157 rue Anatole France',
          'SHIPTOCITY'        => 'Levallois',
          'SHIPTOZIP'         => '92',
          'CURRENCYCODE'      => 'EUR',
          'TAXAMT'            => '10',
          'HANDLINGAMT'       => '10'
        );

        $checkoutDetails = $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'cleanUpResponseGetExpressCheckoutDetails', array($responsePaypal));

        $this->assertTrue(array_key_exists('FIRSTNAME', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['FIRSTNAME']));

        $this->assertTrue(array_key_exists('LASTNAME', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['LASTNAME']));

        $this->assertTrue(array_key_exists('COUNTRYCODE', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['COUNTRYCODE']));

        $this->assertTrue(array_key_exists('SHIPTOCOUNTRYNAME', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['SHIPTOCOUNTRYNAME']));

        $this->assertTrue(array_key_exists('SHIPTOSTREET', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['SHIPTOSTREET']));

        $this->assertTrue(array_key_exists('SHIPTOCITY', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['SHIPTOCITY']));

        $this->assertTrue(array_key_exists('SHIPTOZIP', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['SHIPTOZIP']));

        $this->assertTrue(array_key_exists('CURRENCYCODE', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['CURRENCYCODE']));

        $this->assertTrue(array_key_exists('TAXAMT', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['TAXAMT']));

        $this->assertTrue(array_key_exists('HANDLINGAMT', $checkoutDetails));
        $this->assertTrue(!empty($checkoutDetails['HANDLINGAMT']));
    }

    /**
     * @expectedException \Exception
     */
    public function testCheckTotalDutyFromPaypalExpressCheckoutNVP()
    {
        $this->session->set(PaypalExpressCheckoutNVPInterface::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_TOTAL_DUTY, '10');
        $this->session->set(PaypalExpressCheckoutNVPInterface::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_VAT, '0');
        $this->session->set(PaypalExpressCheckoutNVPInterface::PAYPAL_SET_EXPRESS_CHECKOUT_SESSION_COSTS, '10');

        $responsePaypal = array('PAYMENTREQUEST_0_AMT' => '20');

        $this->assertTrue($this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'checkTotalDutyFromPaypalExpressCheckoutNVP', array($responsePaypal)));

        $this->invokeMethod($this->paypalExpressCheckoutNVPSimple, 'checkTotalDutyFromPaypalExpressCheckoutNVP', array(array()));
    }

    /**
     * Prepare PaypalExpressCheckoutNVPSimple
     *
     * @return PaypalExpressCheckoutNVPSimple
     */
    private function preparePaypalExpressCheckoutNVPSimple()
    {
        return new PaypalExpressCheckoutNVPSimple(
          $this->getMockBuilder('Symfony\Component\Routing\Router')->disableOriginalConstructor()->getMock(),
          $this->session,
          $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')->disableOriginalConstructor()->getMock(),
          $this->getMockBuilder('Guzzle\Http\Client')->disableOriginalConstructor()->getMock(),
          'Prims47',
          'Prims47Signature',
          'Prims47Pwd',
          'route_simple',
          'route_details',
          'cancel_route',
          'url_redirect_success',
          '0',
          'EUR',
          '111',
          false,
          '',
          '0',
          false,
          'https://api-3t.sandbox.paypal.com/nvp',
          'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit',
          $this->getMockBuilder('Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul\Calcul')->getMock(),
          $this->getMockBuilder('Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Validation\Validation')->getMock(),
          $this->getMockBuilder('Monolog\Logger')->disableOriginalConstructor()->getMock(),
          $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->disableOriginalConstructor()->getMock()
        );
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
