<?php


namespace App\PaymentGateway\Domain\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentEventType CREATED()
 * @method static PaymentEventType PROCESS_START()
 */
class PaymentEventType extends Enum
{
    private const CREATED = 'CREATED';
    private const PROCESS_START = 'PROCESS_START';
}
