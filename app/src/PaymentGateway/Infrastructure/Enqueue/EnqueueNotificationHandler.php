<?php


namespace App\PaymentGateway\Infrastructure\Enqueue;


use App\PaymentGateway\Domain\Usecase\Notification\NotificationEvent;

class EnqueueNotificationHandler
{
    public function __invoke(NotificationEvent $notificationEvent) {

        echo "done i";
    }
}
