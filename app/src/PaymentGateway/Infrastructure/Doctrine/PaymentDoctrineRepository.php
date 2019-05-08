<?php


namespace App\PaymentGateway\Infrastructure\Doctrine;


use App\PaymentGateway\Domain\Model\Payment;
use App\PaymentGateway\Domain\Model\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class PaymentDoctrineRepository implements PaymentRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PaymentDoctrineRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function getByPaymentId(UuidInterface $paymentId): ?Payment
    {
        return $this->em->find(Payment::class, $paymentId);
    }

    function save(Payment $payment)
    {
        $this->em->persist($payment);
        $this->em->flush();
    }
}
