<?php


namespace App\PaymentGateway\Delivery\Controller\Dto;

use Swagger\Annotations as Api;

/**
 * @Api\Definition(
 *   type="object",
 *   definition="App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentRequestDto"
 * )
 */
class RegisterPaymentResponseDto
{
    /**
     * @var string
     */
    public $paymentId;
}
