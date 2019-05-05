<?php


namespace App\PaymentGateway\Domain\Model;


use Money\Money;
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
     * @var
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
        $this->events[] = new PaymentEvent(PaymentEventType::CREATED(), $createdAt);
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
        return $this->status->equals($expected);
    }

    /**
     * @return PaymentEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function process(DateTime $processAt, string $gatewayId)
    {
        if (!$this->hasStatus(Status::NEW())) {
            throw new LogicException("Payment cannot be processed because it's not new");
        }

        $this->status = Status::IN_PROCESS();
        $this->events[] = new PaymentEvent(PaymentEventType::PROCESS_START(), $processAt, [
            'gatewayId' => $gatewayId,
        ]);
    }

    public function callbackNotification(DateTime $processAt, array $data)
    {
        $this->events[] = new PaymentEvent(PaymentEventType::CALLBACK_NOTIFICATION(), $processAt, $data);
    }

    public function pending(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->events[] = new PaymentEvent(PaymentEventType::NOTIFICATION_PENDING(), $processAt);
    }

    public function completedFailed(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->status = Status::COMPLETED_FAILURE();
        $this->events[] = new PaymentEvent(PaymentEventType::COMPLETED_FAILURE(), $processAt);
    }

    public function completedSuccess(DateTime $processAt)
    {
        if (!$this->hasStatus(Status::IN_PROCESS())) {
            throw new LogicException("Payment cannot be processed because it's not in process");
        }

        $this->status = Status::COMPLETED_SUCCESS();
        $this->events[] = new PaymentEvent(PaymentEventType::COMPLETED_SUCCESS(), $processAt);
    }
}
