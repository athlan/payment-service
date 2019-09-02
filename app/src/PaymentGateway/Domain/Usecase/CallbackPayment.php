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
use Exception;

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
     * @var CompletePayment
     */
    private $completePayment;

    /**
     * CallbackPayment constructor.
     * @param PaymentRepository $paymentRepository
     * @param GatewaySelection $gatewaySelection
     * @param CompletePayment $completePayment
     */
    public function __construct(PaymentRepository $paymentRepository,
                                GatewaySelection $gatewaySelection,
                                CompletePayment $completePayment)
    {
        $this->paymentRepository = $paymentRepository;
        $this->gatewaySelection = $gatewaySelection;
        $this->completePayment = $completePayment;
    }

    public function callbackPayment(UuidInterface $paymentId, array $params): ResponseInterface
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        $now = new DateTime();

        $notificationData = [
            'success' => true,
            'params' => $params,
        ];

        try {
            $gatewayId = $this->getGatewayId($payment);
            if ($gatewayId === null) {
                throw new LogicException("Cannot find gatewayId for payment");
            }

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
                    $this->completePayment->markAsPending($paymentId, $now);
                }
                if ($response->getTransactionStatus() === NotificationInterface::STATUS_FAILED) {
                    $this->completePayment->markAsCompletedFailed($paymentId, $now);
                }
                if ($response->getTransactionStatus() === NotificationInterface::STATUS_COMPLETED) {
                    $this->completePayment->markAsCompletedSuccess($paymentId, $now);
                }
            }

            $payment->callbackNotification($now, $notificationData);
            $this->paymentRepository->save($payment);

            return $response;
        } catch (Exception $e) {
            $notificationData['success'] = false;

            $payment->callbackNotification($now, $notificationData);
            $this->paymentRepository->save($payment);

            throw $e;
        }
    }

    private function getGatewayId(Payment $payment)
    {
        foreach ($payment->getEvents() as $event) {
            if ($event->getEventType()->equals(PaymentEventType::PROCESS_START())) {
                return $event->getData()['gatewayId'];
            }
        }

        return null;
    }
}
