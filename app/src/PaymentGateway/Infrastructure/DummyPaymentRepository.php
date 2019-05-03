<?php


namespace App\PaymentGateway\Infrastructure;


use App\PaymentGateway\Domain\Model\Payment;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class DummyPaymentRepository implements PaymentRepository
{

    function getByPaymentId(UuidInterface $paymentId): ?Payment
    {
        return new Payment(Money::PLN(123), 'desc', new \DateTime(), 'a', 'transfer');
    }

    function save(Payment $payment)
    {
//        var_dump($payment);
//        var_dump($payment->getEvents());
//        exit;
        // TODO: Implement save() method.
    }
}
