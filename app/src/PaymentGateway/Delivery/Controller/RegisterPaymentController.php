<?php


namespace App\PaymentGateway\Delivery\Controller;

use App\PaymentGateway\Delivery\Controller\Dto\ErrorDto;
use FOS\RestBundle\View\View;
use Swagger\Annotations as Api;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentRequestDto;
use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentResponseDto;
use App\PaymentGateway\Domain\Usecase\RegisterPayment;
use Money\Currency;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegisterPaymentController extends AbstractController
{
    /**
     * @var RegisterPayment
     */
    private $registerPayment;

    /**
     * RegisterPaymentController constructor.
     * @param RegisterPayment $registerPayment
     */
    public function __construct(RegisterPayment $registerPayment)
    {
        $this->registerPayment = $registerPayment;
    }

    /**
     * @Api\Post
     * @Api\Response(
     *     response=200,
     *     description="Registers payment.",
     *     @Model(type=RegisterPaymentResponseDto::class)
     * )
     * @Api\Parameter(
     *     name="params",
     *     in="body",
     *     @Model(type=RegisterPaymentRequestDto::class)
     * )
     * @Api\Tag(name="payments")
     *
     * @ParamConverter("dto", converter="fos_rest.request_body")
     */
    public function registerPayment(RegisterPaymentRequestDto $dto)
    {
        $paymentId = $this->registerPayment->registerPayment(
            new Money((int)$dto->amount, new Currency($dto->currency)),
            $dto->description,
            $dto->sourceSystem,
            $dto->paymentType
        );

        $response = new RegisterPaymentResponseDto();
        $response->paymentId = $paymentId;

        return View::create($response, Response::HTTP_CREATED);
    }
}
