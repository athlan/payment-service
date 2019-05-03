<?php


namespace App\PaymentGateway\Domain\Usecase\GatewaySelection;


use App\PaymentGateway\Domain\Model\GatewayFactory;
use App\PaymentGateway\Domain\Model\PaymentProcessing;

class GatewayWithConditions implements Predicate
{
    /**
     * @var GatewayFactory
     */
    private $gateway;

    /**
     * @var Predicate[]
     */
    private $predicates;

    /**
     * @var int
     */
    private $priority;

    /**
     * GatewayWithConditions constructor.
     * @param GatewayFactory $gateway
     * @param Predicate[] $predicates
     * @param int $priority
     */
    public function __construct(GatewayFactory $gateway, array $predicates, int $priority)
    {
        $this->gateway = $gateway;
        $this->predicates = $predicates;
        $this->priority = $priority;
    }

    /**
     * @return GatewayFactory
     */
    public function getGateway(): GatewayFactory
    {
        return $this->gateway;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    function supportsPaymentProcessing(PaymentProcessing $paymentProcessing): bool
    {
        foreach ($this->predicates as $predicate) {
            if (!$predicate->supportsPaymentProcessing($paymentProcessing)) {
                return false;
            }
        }

        return true;
    }
}
