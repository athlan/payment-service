<?php


namespace App\PaymentGateway\Delivery\Controller;

use Swagger\Annotations as Api;
use App\PaymentGateway\Domain\Usecase\ProcessPayment;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use LogicException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @Api\Get
     * @Api\Response(
     *     response=200,
     *     description="Follows the payment processing. Displays the page or does a redirect to off-site flows."
     * )
     * @Api\Parameter(
     *     name="paymentId",
     *     in="path",
     *     type="string",
     * )
     * @Api\Parameter(
     *     name="return",
     *     in="query",
     *     type="string",
     *     description="Return URL where to redirect user after payment process. If not provided HTTP Referer will be used."
     * )
     * @Api\Tag(name="payments")
     */
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
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
