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

interface ValidationInterface
{
    /**
     * Return TRUE if number is valid.
     *
     * @param float $number
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function validNumber($number);

    /**
     * Return TRUE if basket is valid.
     *
     * @param array $basket
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function validPaypalFullBasket(array $basket);

    /**
     * Return TRUE if basket is valid.
     *
     * @param array $basket
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function validPaypalSimpleBasket(array $basket);

    /**
     * Return TRUE if string is valid.
     *
     * @param string $string
     * @param string $exceptionMessage
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function validString($string, $exceptionMessage);
} 