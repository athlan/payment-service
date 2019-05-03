<?php


namespace App\PaymentGateway\Domain\Usecase\GatewaySelection\Predicate;


use App\PaymentGateway\Domain\Model\PaymentProcessing;
use App\PaymentGateway\Domain\Usecase\GatewaySelection\Predicate;

class ByPaymentType implements Predicate
{
    private $supportedPaymentType;

    /**
     * ByPaymentType constructor.
     * @param $supportedPaymentType
     */
    public function __construct($supportedPaymentType)
    {
        $this->supportedPaymentType = $supportedPaymentType;
    }

    function supportsPaymentProcessing(PaymentProcessing $paymentProcessing): bool
    {
        return $paymentProcessing->getPayment()->getPaymentType() === $this->supportedPaymentType;
    }
}
