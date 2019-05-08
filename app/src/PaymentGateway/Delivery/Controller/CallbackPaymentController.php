<?php


namespace App\PaymentGateway\Delivery\Controller;

use App\PaymentGateway\Domain\Usecase\CallbackPayment;
use FOS\RestBundle\View\View;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as Api;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CallbackPaymentController extends AbstractController
{
    /**
     * @var CallbackPayment
     */
    private $callbackPayment;

    /**
     * RegisterPaymentController constructor.
     * @param CallbackPayment $callbackPayment
     */
    public function __construct(CallbackPayment $callbackPayment)
    {
        $this->callbackPayment = $callbackPayment;
    }

    /**
     * @Api\Get
     * @Api\Response(
     *     response=200,
     *     description="Payment processing callback url for gateways."
     * )
     * @Api\Parameter(
     *     name="paymentId",
     *     in="path",
     *     type="string",
     * )
     * @Api\Tag(name="payments callback")
     */
    public function callbackPayment(Request $request, $paymentId)
    {
        $response = $this->callbackPayment->callbackPayment(
            Uuid::fromString($paymentId),
            $this->getAllParams($request)
        );

        return Response::create($response->getMessage(), $response->getCode());
    }

    private function getAllParams(Request $request)
    {
        return $request->query->all()
            + $request->request->all();
    }
}
