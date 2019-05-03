<?php


namespace App\PaymentGateway\Domain\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static Status NEW()
 * @method static Status IN_PROCESS()
 * @method static Status WAITING_FOR_FINAL_CONFIRMATION()
 * @method static Status PROCESSED_SUCCESS()
 * @method static Status PROCESSED_FAILURE()
 */
class Status extends Enum
{
    private const NEW = 'NEW';
    private const IN_PROCESS = 'IN_PROCESS';
    private const WAITING_FOR_FINAL_CONFIRMATION = 'WAITING_FOR_FINAL_CONFIRMATION';
    private const COMPLETED_SUCCESS = 'COMPLETED_SUCCESS';
    private const COMPLETED_FAILURE = 'COMPLETED_FAILURE';
}
