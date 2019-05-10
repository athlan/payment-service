<?php


namespace App\PaymentGateway\Delivery\Command;


use App\PaymentGateway\Domain\Usecase\Notification\CompletePaymentNotification;
use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompletePaymentFailureNotification extends Command
{
    /**
     * @var CompletePaymentNotification
     */
    private $completePaymentNotification;

    /**
     * OfferActiveFlagSync constructor.
     * @param CompletePaymentNotification $completePaymentNotification
     */
    public function __construct(CompletePaymentNotification $completePaymentNotification)
    {
        $this->completePaymentNotification = $completePaymentNotification;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:payment:complete:failure:notification')
            ->setDescription('Complete payment with failure Notification only.')
            ->addArgument("paymentId", InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $paymentId = $input->getArgument("paymentId");

        $now = new DateTime();
        $this->completePaymentNotification->notifyCompletedFailed(Uuid::fromString($paymentId), $now);

        $output->writeln("Notification has been sent about payment completed with failure.");
    }
}
