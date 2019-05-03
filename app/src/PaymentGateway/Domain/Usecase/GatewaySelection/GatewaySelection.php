<?php


namespace App\PaymentGateway\Domain\Usecase\GatewaySelection;


use App\PaymentGateway\Domain\Model\GatewayFactory;
use App\PaymentGateway\Domain\Model\PaymentProcessing;

class GatewaySelection
{
    /**
     * @var GatewayWithConditions[]
     */
    private $gateways = [];

    /**
     * @param GatewayFactory $gatewayFactory
     * @param Predicate[] $predicates
     * @param int $priority
     * @return GatewaySelection
     */
    public function registerGatewayFactory(GatewayFactory $gatewayFactory, array $predicates, int $priority = 1): GatewaySelection
    {
        $this->gateways[] = new GatewayWithConditions($gatewayFactory, $predicates, $priority);
        return $this;
    }

    public function selectGateway(PaymentProcessing $paymentProcessing) : ?GatewayFactory
    {
        $candidates = [];

        foreach ($this->gateways as $gateway) {
            if ($gateway->supportsPaymentProcessing($paymentProcessing)) {
                $candidates[] = $gateway;
            }
        }
        if (count($candidates) > 0) {
            usort($candidates, function (GatewayWithConditions $a, GatewayWithConditions $b) {
                return $a->getPriority() - $b->getPriority() * -1;
            });

            return $candidates[0]->getGateway();
        }

        return null;
    }
}
