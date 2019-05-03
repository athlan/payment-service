<?php


namespace App\PaymentGateway\Domain\Model;

use DateTime;

class PaymentEvent
{
    /**
     * @var PaymentEventType
     */
    private $eventType;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var array
     */
    private $data;

    /**
     * PaymentEvent constructor.
     * @param PaymentEventType $eventType
     * @param DateTime $date
     * @param array $data
     */
    public function __construct(PaymentEventType $eventType, DateTime $date, array $data = [])
    {
        $this->eventType = $eventType;
        $this->date = $date;
        $this->data = $data;
    }

    /**
     * @return PaymentEventType
     */
    public function getEventType(): PaymentEventType
    {
        return $this->eventType;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
