<?php


namespace App\PaymentGateway\Domain\Usecase;

use App\PaymentGateway\Domain\Model\PaymentRepository;
use App\PaymentGateway\Domain\Usecase\Notification\CompletePaymentNotification;
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
     * @var CompletePaymentNotification
     */
    private $completePaymentNotification;

    /**
     * CompletePayment constructor.
     * @param PaymentRepository $paymentRepository
     * @param CompletePaymentNotification $completePaymentNotification
     */
    public function __construct(PaymentRepository $paymentRepository,
                                CompletePaymentNotification $completePaymentNotification)
    {
        $this->paymentRepository = $paymentRepository;
        $this->completePaymentNotification = $completePaymentNotification;
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

        $this->completePaymentNotification->notifyCompletedFailed($paymentId, $now);
    }

    public function markAsCompletedSuccess(UuidInterface $paymentId, DateTime $now, array $metadata = null)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $payment->markAsCompletedSuccess($now, $metadata);
        $this->paymentRepository->save($payment);

        $this->completePaymentNotification->notifyCompletedSuccess($paymentId, $now);
    }
}
