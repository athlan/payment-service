<?php


namespace App\PaymentGateway\Domain\Model\Notification;


use Ramsey\Uuid\UuidInterface;

class PaymentCompletedFailure
{
    /**
     * @var UuidInterface
     */
    public $paymentId;
}
