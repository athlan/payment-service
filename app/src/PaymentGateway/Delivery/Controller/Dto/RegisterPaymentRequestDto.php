<?php


namespace App\PaymentGateway\Delivery\Controller\Dto;


class RegisterPaymentRequestDto
{
    public $amount;
    public $currency;
    public $description;
    public $sourceSystem;
    public $paymentType;
}
