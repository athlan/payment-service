<?php


namespace App\PaymentGateway\Gateway\Dotpay;


use App\PaymentGateway\Domain\Model\GatewayFactory as IGatewayFactory;
use App\PaymentGateway\Domain\Model\Payment;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

class GatewayFactory implements IGatewayFactory
{
    /**
     * @var string
     */
    private $gatewayId;

    /**
     * @var array
     */
    private $params;

    /**
     * GatewayFactory constructor.
     * @param string gatewayId
     * @param array $params
     */
    public function __construct(string $gatewayId, array $params)
    {
        $this->gatewayId = $gatewayId;
        $this->params = $params;
    }

    /**
     * @inheritdoc
     */
    function gatewayId(): string
    {
        return $this->gatewayId;
    }

    /**
     * @inheritdoc
     */
    function createGateway(): GatewayInterface
    {
        $gateway = Omnipay::create('Dotpay');

        $gateway->initialize($this->params);

        return $gateway;
    }

    /**
     * @inheritdoc
     */
    function createPurchaseParams(Payment $payment): array
    {
        return [
            'control' => $payment->getPaymentId(),
        ];
    }
}