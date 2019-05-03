<?php


namespace App\PaymentGateway\Domain\Usecase;


use App\PaymentGateway\Domain\Model\Payment;
use App\PaymentGateway\Domain\Model\PaymentProcessing;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use App\PaymentGateway\Domain\Model\Status;
use App\PaymentGateway\Domain\Usecase\GatewaySelection\GatewaySelection;
use LogicException;
use Omnipay\Common\Message\ResponseInterface;
use Ramsey\Uuid\UuidInterface;

class ProcessPayment
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
     * ProcessPayment constructor.
     * @param PaymentRepository $paymentRepository
     * @param GatewaySelection $gatewaySelection
     */
    public function __construct(PaymentRepository $paymentRepository, GatewaySelection $gatewaySelection)
    {
        $this->paymentRepository = $paymentRepository;
        $this->gatewaySelection = $gatewaySelection;
    }

    public function processPayment(UuidInterface $paymentId, $gatewayType, $returnUrl, $callbackUrl): ResponseInterface
    {
        $payment = $this->paymentRepository->getByPaymentId($paymentId);

        if ($payment === null) {
            throw new LogicException("Payment does not exists");
        }

        if (!$payment->hasStatus(Status::NEW())) {
            throw new LogicException("Payment is not new");
        }

        $paymentProcessing = new PaymentProcessing($payment, $gatewayType);

        $gatewayFactory = $this->gatewaySelection->selectGateway($paymentProcessing);
        if ($gatewayFactory === null) {
            throw new LogicException("No gateway supports this payment");
        }

        $gateway = $gatewayFactory->createGateway();

        $purchaseParams = $this->createPurchaseParams($payment, $returnUrl, $callbackUrl);
        $purchaseParams += $gatewayFactory->createPurchaseParams($payment);

        $response = $gateway->purchase($purchaseParams)
            ->send();

        if ($response->isRedirect()) {
            return $response;
        } else if ($response->isSuccessful()) {
            // TODO confirm
        } else {
            throw new LogicException("Illegal state");
        }
    }

    private function createPurchaseParams(Payment $payment, $returnUrl, $callbackUrl)
    {
        $params = array(
            'amount' => $payment->getAmount()->getAmount(),
            'currency' => $payment->getAmount()->getCurrency(),
            'description' => $payment->getDescription(),
            'returnUrl' => $returnUrl,
            'notifyUrl' => $callbackUrl,
        );

        return $params;
    }
}
