<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Tests\Validation;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Validation\Validation;
use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Payment\PaypalExpressCheckoutNVPInterface;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @var array
     */
    protected $basket;

    protected function setUp()
    {
        $this->validation = new Validation();
        $this->basket     = array(
          array(
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME => 'Macbook Pro Retina',
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT  => 3000,
            PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY  => 1,
          )
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testValidNumber()
    {
        $this->assertTrue($this->validation->validNumber(10));
        $this->assertTrue($this->validation->validNumber('10'));
        $this->validation->validNumber('Prims47');
    }

    /**
     * @expectedException \Exception
     */
    public function testValidPaypalFullBasket()
    {
        $this->assertTrue($this->validation->validPaypalFullBasket($this->basket));

        $this->validation->validPaypalFullBasket(array());
    }

    /**
     * @expectedException \Exception
     */
    public function testValidPaypalFullBasketNameIsEmpty()
    {
        unset($this->basket[0][PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_NAME]);

        $this->validation->validPaypalFullBasket($this->basket);
    }

    /**
     * @expectedException \Exception
     */
    public function testValidPaypalFullBasketAMTIsEmpty()
    {
        unset($this->basket[0][PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_AMT]);

        $this->validation->validPaypalFullBasket($this->basket);
    }

    /**
     * @expectedException \Exception
     */
    public function testValidPaypalFullBasketQuantityIsEmpty()
    {
        unset($this->basket[0][PaypalExpressCheckoutNVPInterface::L_PAYMENTREQUEST_QTY]);

        $this->validation->validPaypalFullBasket($this->basket);
    }

    /**
     * @expectedException \Exception
     */
    public function testValidString()
    {
        $this->assertTrue($this->validation->validString('Prims47', 'You must given a string'));

        $this->validation->validString('', 'You must given a string');
    }

    /**
     * @expectedException \Exception
     */
    public function testValidPaypalSimpleBasket()
    {
        $basket[PaypalExpressCheckoutNVPInterface::PAYPAL_TOTAL_DUTY] = 10;

        $this->assertTrue($this->validation->validPaypalSimpleBasket($basket));

        $basket[PaypalExpressCheckoutNVPInterface::PAYPAL_TOTAL_DUTY] = '';

        $this->assertTrue($this->validation->validPaypalSimpleBasket($basket));
    }
}
 