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
     * @var UuidInterface
     */
    private $paymentId;

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
     * @param UuidInterface $paymentId
     * @param PaymentEventType $eventType
     * @param DateTime $date
     * @param array $data
     */
    public function __construct(UuidInterface $paymentId,
                                PaymentEventType $eventType,
                                DateTime $date,
                                array $data = null)
    {
        $this->eventId = Uuid::uuid4();
        $this->paymentId = $paymentId;
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
        return $this->paymentId;
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
