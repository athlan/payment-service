<?php


namespace App\PaymentGateway\Domain\Usecase\GatewaySelection\Predicate;


use App\PaymentGateway\Domain\Model\PaymentProcessing;
use App\PaymentGateway\Domain\Usecase\GatewaySelection\Predicate;

class ByGatewayType implements Predicate
{
    private $supportedGatewayType;

    /**
     * ByGatewayType constructor.
     * @param $supportedGatewayType
     */
    public function __construct($supportedGatewayType)
    {
        $this->supportedGatewayType = $supportedGatewayType;
    }

    function supportsPaymentProcessing(PaymentProcessing $paymentProcessing): bool
    {
        return $paymentProcessing->getGatewayType() === $this->supportedGatewayType;
    }
}
