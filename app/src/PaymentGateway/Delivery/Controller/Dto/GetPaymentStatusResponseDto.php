<?php


namespace App\PaymentGateway\Delivery\Controller\Dto;

use Swagger\Annotations as Api;

/**
 * @Api\Definition(
 *   type="object",
 *   definition="App\PaymentGateway\Delivery\Controller\Dto\GetPaymentStatusResponseDto"
 * )
 */
class GetPaymentStatusResponseDto
{
    /**
     * @var string
     */
    public $paymentId;

    /**
     * @Api\Property(
     *     enum={"NEW", "IN_PROCESS", "WAITING_FOR_FINAL_CONFIRMATION", "COMPLETED_SUCCESS", "COMPLETED_FAILURE"}
     * )
     * @var string
     */
    public $status;
}
