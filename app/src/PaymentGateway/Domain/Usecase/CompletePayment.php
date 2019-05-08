<?php


namespace App\PaymentGateway\Domain\Usecase;

use App\PaymentGateway\Domain\Model\PaymentRepository;
use DateTime;
use LogicException;
use Ramsey\Uuid\UuidInterface;

class CompletePayment
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * CompletePayment constructor.
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function markAsPending(UuidInterface $paymentId, DateTime $now)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $payment->markAsPending($now);
        $this->paymentRepository->save($payment);
    }

    public function markAsCompletedFailed(UuidInterface $paymentId, DateTime $now, array $metadata = null)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $payment->markAsCompletedFailed($now, $metadata);
        $this->paymentRepository->save($payment);
    }

    public function markAsCompletedSuccess(UuidInterface $paymentId, DateTime $now, array $metadata = null)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $payment->markAsCompletedSuccess($now, $metadata);
        $this->paymentRepository->save($payment);
    }
}
