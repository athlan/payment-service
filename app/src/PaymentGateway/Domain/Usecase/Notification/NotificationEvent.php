<?php


namespace App\PaymentGateway\Domain\Usecase\Notification;


use App\PaymentGateway\Domain\Model\Notification\PaymentCompletedFailure;
use App\PaymentGateway\Domain\Model\Notification\PaymentCompletedSuccess;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PaymentCompletedSuccess|PaymentCompletedFailure
     */
    private $event;

    /**
     * Notification constructor.
     * @param mixed $event
     */
    private function __construct($event)
    {
        $this->name = get_class($event);
        $this->event = $event;
    }

    public static function of($event) {
        return new self($event);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PaymentCompletedSuccess|PaymentCompletedFailure
     */
    public function getEvent()
    {
        return $this->event;
    }
}