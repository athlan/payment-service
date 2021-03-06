<?php


namespace App\PaymentGateway\Domain\Model;


use Omnipay\Common\GatewayInterface;

interface GatewayFactory
{
    /**
     * @return string
     */
    function gatewayId() : string;

    /**
     * @return GatewayInterface
     */
    function createGateway() : GatewayInterface;

    /**
     * @param Payment $payment
     * @return array
     */
    function createPurchaseParams(Payment $payment) : array ;
}
