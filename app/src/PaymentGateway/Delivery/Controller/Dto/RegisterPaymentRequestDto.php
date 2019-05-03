<?php


namespace App\PaymentGateway\Delivery\Controller\Dto;

use Swagger\Annotations as Api;

/**
 * @Api\Definition(
 *   type="object",
 *   definition="App\PaymentGateway\Delivery\Controller\Dto\RegisterPaymentRequestDto"
 * )
 */
class RegisterPaymentRequestDto
{
    /**
     * @var double
     *
     * @Api\Property(example=123.45)
     */
    public $amount;

    /**
     * Currency in ISO 4217
     *
     * @var string
     *
     * @Api\Property(example="PLN")
     */
    public $currency;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $sourceSystem;

    /**
     * @var string
     *
     * @Api\Property(example="transfer")
     */
    public $paymentType;
}
