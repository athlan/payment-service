<?php


namespace App\PaymentGateway\Domain\Model;


use Ramsey\Uuid\UuidInterface;

interface PaymentRepository
{
    function getByPaymentId(UuidInterface $paymentId) : ?Payment;

    function save(Payment $payment);
}
