<?php


namespace App\PaymentGateway\Domain\Usecase\Notification;


use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
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
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }
}