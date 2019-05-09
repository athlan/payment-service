<?php


namespace App\PaymentGateway\Domain\Model\Notification;


use Ramsey\Uuid\UuidInterface;

class PaymentCompletedSuccess
{
    /**
     * @var UuidInterface
     */
    public $paymentId;
}
