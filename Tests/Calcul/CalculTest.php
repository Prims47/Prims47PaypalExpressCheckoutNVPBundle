<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Tests\Calcul;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Calcul\Calcul;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;

class CalculTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Calcul
     */
    protected $calcul;

    protected function setUp()
    {
        $this->calcul = new Calcul();
    }

    public function testCalculBasketTotalDuty()
    {
        $basket = array(
            array(
                PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT => 10,
                PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY => 2,
            ),
        );

        $this->assertEquals(20, $this->calcul->calculBasketTotalDuty($basket));

        $basket = array(
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT => '18.47',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY => 4,
          ),
        );

        $this->assertEquals('73.88', $this->calcul->calculBasketTotalDuty($basket));

        $basket = array(
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT => '18.47',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY => 4,
          ),
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT => '10',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY => 2,
          ),
        );

        $this->assertEquals('93.88', $this->calcul->calculBasketTotalDuty($basket));

    }

    public function testCalculVAT()
    {
        $totalDuty     = 10;
        $vatPercentage = 20;

        $this->assertEquals(2 , $this->calcul->calculVAT($totalDuty, $vatPercentage));

        $totalDuty     = '119.47';
        $vatPercentage = '6.8';

        $this->assertEquals('8.12' , $this->calcul->calculVAT($totalDuty, $vatPercentage));
    }
}
