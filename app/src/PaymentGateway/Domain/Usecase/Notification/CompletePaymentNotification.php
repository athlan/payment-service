<?php


namespace App\PaymentGateway\Domain\Usecase\Notification;

use App\PaymentGateway\Domain\Model\Notification\PaymentCompletedSuccess;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use App\PaymentGateway\Domain\Model\Status;
use DateTime;
use LogicException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CompletePaymentNotification
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CompletePayment constructor.
     * @param PaymentRepository $paymentRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(PaymentRepository $paymentRepository,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->paymentRepository = $paymentRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function notifyCompletedFailed(UuidInterface $paymentId, DateTime $now, array $metadata = null)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }
        if (!$payment->hasStatus(Status::COMPLETED_FAILURE())) {
            throw new LogicException("Payment is not in expected COMPLETED_FAILURE state.");
        }

        // TODO
    }

    public function notifyCompletedSuccess(UuidInterface $paymentId, DateTime $now, array $metadata = null)
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }
        if (!$payment->hasStatus(Status::COMPLETED_SUCCESS())) {
            throw new LogicException("Payment is not in expected COMPLETED_SUCCESS state.");
        }

        $notification = new PaymentCompletedSuccess();
        $notification->paymentId = $paymentId;

        $event = NotificationEvent::of($notification);
        $this->eventDispatcher->dispatch($event->getName(), $event);
    }
}
