<?php


namespace App\PaymentGateway\Domain\Usecase;


use App\PaymentGateway\Domain\Model\Payment;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use Money\Money;
use DateTime;
use Ramsey\Uuid\Uuid;

class RegisterPayment
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * RegisterPayment constructor.
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function registerPayment(
        Money $amount,
        string $description,
        string $sourceSystem,
        string $paymentType) : Uuid
    {
        $now = new DateTime();
        $payment = new Payment(
            $amount,
            $description,
            $now,
            $sourceSystem,
            $paymentType
        );

        $this->paymentRepository->save($payment);

        return $payment->getPaymentId();
    }
}
