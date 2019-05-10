<?php


namespace App\PaymentGateway\Domain\Model\Notification;


use Ramsey\Uuid\Uuid;
use DateTime;

class PaymentCompletedSuccess
{
    /**
     * @var string
     */
    public $type = 'PaymentCompletedSuccess';

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var Uuid
     */
    public $paymentId;

    /**
     * @var Status
     */
    public $status;
}
