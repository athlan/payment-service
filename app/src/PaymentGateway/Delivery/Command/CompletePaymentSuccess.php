<?php


namespace App\PaymentGateway\Delivery\Command;


use App\PaymentGateway\Domain\Usecase\CompletePayment;
use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompletePaymentSuccess extends Command
{
    /**
     * @var CompletePayment
     */
    private $completePayment;

    /**
     * OfferActiveFlagSync constructor.
     * @param CompletePayment $completePayment
     */
    public function __construct(CompletePayment $completePayment)
    {
        $this->completePayment = $completePayment;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:payment:complete:success')
            ->setDescription('Complete payment with success.')
            ->addArgument("paymentId", InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $paymentId = $input->getArgument("paymentId");

        $now = new DateTime();
        $this->completePayment->markAsCompletedSuccess(Uuid::fromString($paymentId), $now);
    }
}
