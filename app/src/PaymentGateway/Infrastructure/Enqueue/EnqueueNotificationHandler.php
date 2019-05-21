<?php


namespace App\PaymentGateway\Infrastructure\Enqueue;


use App\PaymentGateway\Domain\Usecase\Notification\NotificationEvent;
use Enqueue\Client\ProducerInterface;
use Interop\Queue\Context;
use Interop\Queue\Topic;
use Symfony\Component\Serializer\SerializerInterface;

class EnqueueNotificationHandler
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var Topic
     */
    private $topic;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * EnqueueNotificationHandler constructor.
     */
    public function __construct(string $dsn, string $topicName, SerializerInterface $serializer)
    {
        $this->context = (new \Enqueue\ConnectionFactoryFactory())
            ->create($dsn)
            ->createContext();

        $this->producer = $this->context->createProducer();
        $this->topic = $this->context->createTopic($topicName);

        $this->serializer = $serializer;
    }

    public function __invoke(NotificationEvent $notificationEvent)
    {
        $event = $notificationEvent->getEvent();

        $body = $this->serializer->serialize($event, 'json');
        $properties = [
            'message_type' => get_class($event),
            'source_system' => $event->sourceSystem,
        ];
        $headers = [];

        $message = $this->context->createMessage($body, $properties, $headers);
        $this->producer->send($this->topic, $message);
    }
}
