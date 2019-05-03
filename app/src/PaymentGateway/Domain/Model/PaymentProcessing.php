<?php


namespace App\PaymentGateway\Domain\Model;


class PaymentProcessing
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * PaymentProcessing constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }
}