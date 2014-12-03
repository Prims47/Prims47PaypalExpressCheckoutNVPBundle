<?php

namespace Paypal\Bundle\ExpressCheckoutBundle\Event;

use Paypal\Bundle\ExpressCheckoutBundle\Entity\BasePaypalExpressCheckoutNVPTransactionDetails;

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