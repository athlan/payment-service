<?php


namespace App\PaymentGateway\Domain\Usecase;


use App\PaymentGateway\Domain\Model\Payment;
use App\PaymentGateway\Domain\Model\PaymentEventType;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use App\PaymentGateway\Domain\Usecase\GatewaySelection\GatewaySelection;
use LogicException;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\ResponseInterface;
use Ramsey\Uuid\UuidInterface;
use DateTime;

class CallbackPayment
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @var GatewaySelection
     */
    private $gatewaySelection;

    /**
     * CallbackPayment constructor.
     * @param PaymentRepository $paymentRepository
     * @param GatewaySelection $gatewaySelection
     */
    public function __construct(PaymentRepository $paymentRepository, GatewaySelection $gatewaySelection)
    {
        $this->paymentRepository = $paymentRepository;
        $this->gatewaySelection = $gatewaySelection;
    }

    public function callbackPayment(UuidInterface $paymentId, array $params): ResponseInterface
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $now = new DateTime();

        $payment->callbackNotification($now, $params);
        $this->paymentRepository->save($payment);

        $gatewayId = $this->getGatewayId($payment);

        $gatewayFactory = $this->gatewaySelection->selectGatewayById($gatewayId);
        if ($gatewayFactory === null) {
            throw new LogicException("No gateway supports this payment");
        }

        $gateway = $gatewayFactory->createGateway();

        /* @var $response NotificationInterface */
        $response = $gateway->completePurchase($params)
            ->send();

        if ($response->isSuccessful()) {
            if ($response->getTransactionStatus() === NotificationInterface::STATUS_PENDING) {
                $payment->markAsPending($now);
            }
            if ($response->getTransactionStatus() === NotificationInterface::STATUS_FAILED) {
                $payment->markAsCompletedFailed($now);
            }
            if ($response->getTransactionStatus() === NotificationInterface::STATUS_COMPLETED) {
                $payment->markAsCompletedSuccess($now);
            }
        }

        $this->paymentRepository->save($payment);

        return $response;
    }

    private function getGatewayId(Payment $payment)
    {
        foreach ($payment->getEvents() as $event) {
            if ($event->getEventType() === PaymentEventType::PROCESS_START()) {
                return $event->getData()['gatewayId'];
            }
        }

        return null;
    }
}
