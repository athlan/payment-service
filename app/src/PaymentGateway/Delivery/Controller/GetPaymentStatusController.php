<?php


namespace App\PaymentGateway\Delivery\Controller;

use App\PaymentGateway\Delivery\Controller\Dto\GetPaymentStatusResponseDto;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use FOS\RestBundle\View\View;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as Api;
use Nelmio\ApiDocBundle\Annotation\Model;

class GetPaymentStatusController extends AbstractController
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * GetPaymentStatusController constructor.
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @Api\Get
     * @Api\Response(
     *     response=200,
     *     description="Gets payment status.",
     *     @Model(type=GetPaymentStatusResponseDto::class)
     * )
     * @Api\Response(
     *     response=404,
     *     description="Payment does not exists.",
     * )
     * @Api\Parameter(
     *     name="paymentId",
     *     in="path",
     *     type="string",
     * )
     * @Api\Tag(name="payments")
     */
    public function getStatus(Request $request, $paymentId)
    {
        $payment = $this->paymentRepository->getByPaymentId(Uuid::fromString($paymentId));

        if (null === $payment) {
            throw $this->createNotFoundException();
        }

        $response = new GetPaymentStatusResponseDto();
        $response->paymentId = $payment->getPaymentId()->toString();
        $response->status = $payment->getStatus()->getValue();

        $return = View::create($response, Response::HTTP_OK);
        return $return;
    }
}
