<?php


namespace App\PaymentGateway\Delivery\Controller;

use App\PaymentGateway\Delivery\Controller\Dto\ErrorDto;
use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentRequestDto;
use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentResponseDto;
use App\PaymentGateway\Domain\Usecase\RegisterPayment;
use FOS\RestBundle\View\View;
use Money\Parser\DecimalMoneyParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as Api;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RegisterPaymentController extends AbstractController
{
    /**
     * @var RegisterPayment
     */
    private $registerPayment;

    /**
     * @var DecimalMoneyParser
     */
    private $moneyParser;

    /**
     * RegisterPaymentController constructor.
     * @param RegisterPayment $registerPayment
     * @param DecimalMoneyParser $moneyParser
     */
    public function __construct(RegisterPayment $registerPayment,
                                DecimalMoneyParser $moneyParser)
    {
        $this->registerPayment = $registerPayment;
        $this->moneyParser = $moneyParser;
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
            $this->moneyParser->parse((string) $dto->amount, $dto->currency),
            $dto->description,
            $dto->sourceSystem,
            $dto->paymentType
        );

        $response = new RegisterPaymentResponseDto();
        $response->paymentId = $paymentId->toString();

        return View::create($response, Response::HTTP_CREATED);
    }
}
