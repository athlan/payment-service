<?php


namespace App\PaymentGateway\Domain\Model\Notification;


use Ramsey\Uuid\Uuid;
use DateTime;

class PaymentCompletedFailure
{
    /**
     * @var string
     */
    public $type = 'PaymentCompletedFailure';

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var Uuid
     */
    public $paymentId;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $sourceSystem;

}
