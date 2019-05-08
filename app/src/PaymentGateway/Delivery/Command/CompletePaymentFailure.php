<?php


namespace App\PaymentGateway\Delivery\Command;


use App\PaymentGateway\Domain\Usecase\CompletePayment;
use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompletePaymentFailure extends Command
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
            ->setName('app:payment:complete:failure')
            ->setDescription('Complete payment with failure.')
            ->addArgument("paymentId", InputArgument::REQUIRED)
            ->addOption("author", "a", InputOption::VALUE_REQUIRED, "Intervention author")
            ->addOption("message", "m", InputOption::VALUE_REQUIRED, "Intervention reason message")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $paymentId = $input->getArgument("paymentId");

        if(empty($input->getOption("author"))) {
            throw new \InvalidArgumentException("Missing author option");
        }
        if(empty($input->getOption("message"))) {
            throw new \InvalidArgumentException("Missing message option");
        }

        $metadata = [
            'author' => $input->getOption("author"),
            'interventionReason' => $input->getOption("message"),
        ];

        $now = new DateTime();
        $this->completePayment->markAsCompletedFailed(Uuid::fromString($paymentId), $now, $metadata);
    }
}
