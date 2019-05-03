<?php


namespace App\PaymentGateway\Delivery\Controller;


use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentRequestDto;
use App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentResponseDto;
use App\PaymentGateway\Domain\Usecase\RegisterPayment;
use LogicException;
use Money\Currency;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function registerPayment(RegisterPaymentRequestDto $dto)
    {
        try {
            $paymentId = $this->registerPayment->registerPayment(
                new Money($dto->amount, new Currency($dto->currency)),
                $dto->description,
                $dto->sourceSystem,
                $dto->paymentType
            );

            $response = new RegisterPaymentResponseDto();
            $response->paymentId = $paymentId;
            return $response;
        }
        catch (LogicException $e) {
            // TODO
            echo $e->getMessage();
        }
    }
}
