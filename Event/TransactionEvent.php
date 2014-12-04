<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Event;

use Prims47\Bundle\PaypalExpressCheckoutNVPBundle\Entity\BasePaypalExpressCheckoutNVPTransactionDetails;

use Symfony\Component\EventDispatcher\Event;

class TransactionEvent extends Event
{
    /**
     * @var BasePaypalExpressCheckoutNVPTransactionDetails
     */
    protected $transaction;

    /**
     * Constructor.
     *
     * @param BasePaypalExpressCheckoutNVPTransactionDetails $transaction
     */
    public function __construct(BasePaypalExpressCheckoutNVPTransactionDetails $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get Transaction.
     *
     * @return BasePaypalExpressCheckoutNVPTransactionDetails
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
} 