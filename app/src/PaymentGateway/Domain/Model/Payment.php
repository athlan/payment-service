<?php


namespace App\PaymentGateway\Domain\Model;


use Money\Money;
use Ramsey\Uuid\Uuid;
use DateTime;
use Exception;
use RuntimeException;

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
     * @var Status
     */
    private $status;

    /**
     * Payment constructor.
     * @param Money $amount
     * @param string $description
     * @param DateTime $createdAt
     * @param string $sourceSystem
     */
    public function __construct(Money $amount, string $description, DateTime $createdAt, string $sourceSystem)
    {
        $this->paymentId = $this->generateId();
        $this->amount = $amount;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->sourceSystem = $sourceSystem;
        $this->status = Status::NEW();
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


}
