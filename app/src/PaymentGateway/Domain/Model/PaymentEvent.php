<?php


namespace App\PaymentGateway\Domain\Model;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PaymentEvent
{
    /**
     * @var UuidInterface
     */
    private $eventId;

    /**
     * @var Payment
     */
    private $payment;

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
     * @param Payment $payment
     * @param PaymentEventType $eventType
     * @param DateTime $date
     * @param array $data
     */
    public function __construct(Payment $payment,
                                PaymentEventType $eventType,
                                DateTime $date,
                                array $data = null)
    {
        $this->eventId = Uuid::uuid4();
        $this->payment = $payment;
        $this->eventType = $eventType;
        $this->date = $date;
        $this->data = $data;
    }

    /**
     * @return UuidInterface
     */
    public function getEventId(): UuidInterface
    {
        return $this->eventId;
    }

    /**
     * @return UuidInterface
     */
    public function getPaymentId(): UuidInterface
    {
        return $this->payment->getPaymentId();
    }

    /**
     * @return PaymentEventType
     */
    public function getEventType(): PaymentEventType
    {
        return new PaymentEventType($this->eventType);
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
