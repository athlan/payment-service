<?php


namespace App\PaymentGateway\Domain\Model;


class PaymentProcessing
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var string
     */
    private $gatewayType;

    /**
     * PaymentProcessing constructor.
     * @param Payment $payment
     * @param string $gatewayType
     */
    public function __construct(Payment $payment, string $gatewayType)
    {
        $this->payment = $payment;
        $this->gatewayType = $gatewayType;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function getGatewayType(): string
    {
        return $this->gatewayType;
    }
}