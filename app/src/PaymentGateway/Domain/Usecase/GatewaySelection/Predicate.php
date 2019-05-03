<?php


namespace App\PaymentGateway\Domain\Usecase\GatewaySelection;


use App\PaymentGateway\Domain\Model\PaymentProcessing;

interface Predicate
{
    function supportsPaymentProcessing(PaymentProcessing $paymentProcessing) : bool;
}
