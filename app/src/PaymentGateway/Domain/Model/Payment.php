<?php


namespace App\PaymentGateway\Domain\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Money\Money;
use MyCLabs\Enum\Enum;
use Ramsey\Uuid\Uuid;
use DateTime;
use Exception;
use RuntimeException;
use LogicException;

class Payment
{
    /**
     * @var Uuid
     */
    private $paymentId;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $sourceSystem;

    /**
     * @var string
     */
    private $paymentType;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var ArrayCollection<PaymentEvent>
     */
    private $events;

    /**
     * Payment constructor.
     * @param Money $amount
     * @param string $description
     * @param DateTime $createdAt
     * @param string $sourceSystem
     * @param string $paymentType
     */
    public function __construct(Money $amount, string $description, DateTime $createdAt, string $sourceSystem, string $paymentType)
    {
        $this->paymentId = $this->generateId();
        $this->amount = $amount;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->sourceSystem = $sourceSystem;
        $this->paymentType = $paymentType;

        $this->status = Status::NEW();

        $this->events = new ArrayCollection();
        $this->event(new PaymentEvent($this->paymentId, PaymentEventType::CREATED(), $createdAt));
    }

    private function generateId()
    {
        try {
            return Uuid::uuid4();
        }
        catch (Exception $e) {
            throw new RuntimeException("Cannot generate uuid for payment");
        }
    }

    /**
     * @return Uuid
     */
    public function getPaymentId(): Uuid
    {
        return $this->paymentId;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getSourceSystem(): string
    {
        return $this->sourceSystem;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $expected
     * @return bool
     */
    public function hasStatus(Status $expected): bool
    {
        return $expected->equals(new Status($this->status));
    }

    /**
     * @return PaymentEvent[]
     */
    public function getEvents()
    {
        return $this->events->toArray();
    }

    public function process(DateTime $processAt, string $gatewayId)
    {
        if (!$this->hasStatus(Status::NEW())) {
            throw new LogicException("Payment cannot be processed because it's not new");
        }

        $this->status = Status::IN_PROCESS();
        $this->event(new PaymentEvent($this->paymentId, PaymentEventType::PROCESS_START(), $processAt, [
            'gatewayId' => $gatewayId,
        ]));
    }

    public function callbackNotification(DateTime $processAt, array $data)
    {
        $this->events[] = new PaymentEvent($this->paymentId, PaymentEventType::CALLBACK_NOTIFICATION(), $processAt, $data);
    }

    public function markAsPending(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->event(new PaymentEvent($this->paymentId, PaymentEventType::NOTIFICATION_PENDING(), $processAt));
    }

    public function markAsCompletedFailed(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->status = Status::COMPLETED_FAILURE();
        $this->event(new PaymentEvent($this->paymentId, PaymentEventType::COMPLETED_FAILURE(), $processAt));
    }

    public function markAsCompletedSuccess(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->status = Status::COMPLETED_SUCCESS();
        $this->event(new PaymentEvent($this->paymentId, PaymentEventType::COMPLETED_SUCCESS(), $processAt));
    }

    private function event(PaymentEvent $event)
    {
        $this->events->add($event);
    }
}
