<?php


namespace App\PaymentGateway\Gateway\Dotpay;


use App\PaymentGateway\Domain\Model\GatewayFactory as IGatewayFactory;
use App\PaymentGateway\Domain\Model\Payment;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

class GatewayFactory implements IGatewayFactory
{
    /**
     * @var array
     */
    private $params;

    /**
     * GatewayFactory constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
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