<?php


namespace App\PaymentGateway\Domain\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentEventType CREATED()
 * @method static PaymentEventType PROCESS_START()
 * @method static PaymentEventType CALLBACK_NOTIFICATION()
 * @method static PaymentEventType COMPLETED_SUCCESS()
 * @method static PaymentEventType COMPLETED_FAILURE()
 */
class PaymentEventType extends Enum
{
    private const CREATED = 'CREATED';
    private const PROCESS_START = 'PROCESS_START';
    private const CALLBACK_NOTIFICATION = 'CALLBACK_NOTIFICATION';
    private const COMPLETED_SUCCESS = 'COMPLETED_SUCCESS';
    private const COMPLETED_FAILURE = 'COMPLETED_FAILURE';
}
