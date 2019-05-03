<?php


namespace App\PaymentGateway\Delivery\Controller;


use App\PaymentGateway\Domain\Usecase\ProcessPayment;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use LogicException;

class ProcessPaymentController extends AbstractController
{
    /**
     * @var ProcessPayment
     */
    private $processPayment;

    /**
     * PaymentRequestController constructor.
     * @param ProcessPayment $processPayment
     */
    public function __construct(ProcessPayment $processPayment)
    {
        $this->processPayment = $processPayment;
    }

    public function processPayment(Request $request, $paymentId)
    {
        $returnUrl = $this->getReturnUrl($request);
        $callbackUrl = $this->getCallbackUrl($paymentId);

        try {
            $response = $this->processPayment->processPayment(
                Uuid::fromString($paymentId),
                $returnUrl,
                $callbackUrl
            );

            if ($response->isRedirect()) {
                $response->redirect();
            }
        }
        catch (LogicException $e) {
            // TODO
            echo $e->getMessage();
        }
    }

    private function getReturnUrl(Request $request)
    {
        $referer = $request->headers->get('referer');
        return $request->query->get('return', $referer);
    }

    private function getCallbackUrl($paymentId)
    {
        return $this->generateUrl('gateway.payment.callback', [
            'paymentId' => $paymentId,
        ]);
    }
}
