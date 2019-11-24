<?php


namespace App\PaymentGateway\Delivery\Controller\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class RequestLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(KernelEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $context = $this->createRequestContext($event);

        $this->logger->info("http request", $context);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $context = $this->createResponseContext($event);

        $this->logger->info("http response", $context);
    }

    private function createRequestContext(KernelEvent $event)
    {
        $request = $event->getRequest();

        $context = [];
        $context['url'] = $request->getRequestUri();
        $context['method'] = $request->getMethod();
        $context['ip'] = $request->getClientIp();

        $context['parameters'] = [];
        $context['parameters']['GET'] = $_GET;
        $context['parameters']['POST'] = $_POST;

        return $context;
    }

    private function createResponseContext(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $context = [];
        $context['body'] = $response->getContent();

        return $context;
    }
}
